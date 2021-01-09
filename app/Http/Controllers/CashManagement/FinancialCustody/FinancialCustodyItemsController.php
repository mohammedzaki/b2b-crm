<?php
/**
 * Created by PhpStorm.
 * User: mohamedzaki
 * Date: 12/7/20
 * Time: 6:27 PM
 */

namespace App\Http\Controllers\CashManagement\FinancialCustody;

use App\Constants\EmployeeActions;
use App\Constants\PaymentMethods;
use App\Exceptions\ValidationException;
use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DepositWithdraw;
use App\Models\FinancialCustody;
use App\Models\User;
use App\Models\ClientProcess;
use App\Models\FinancialCustodyItem;
use App\Models\Employee;
use App\Models\EmployeeBorrowBilling;
use App\Models\Expenses;
use App\Models\OpeningAmount;
use App\Models\Supplier;
use App\Models\SupplierProcess;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Helpers\Helpers;
use Illuminate\Auth\AuthenticationException;


/**
 * Description of DailyCashController
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 *
 * @Controller(prefix="/financial-custody-items")
 * @Resource("financialCustodyItems")
 * @Middleware({"web", "auth", "ability:admin,financial-custody-items"})
 */
class FinancialCustodyItemsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->hasRole('admin')) {
            return $this->getFinancialCustodyItems(1, $request);
        } else {
            return $this->getFinancialCustodyItems(0, $request);
        }
    }

    private function getFinancialCustodyItems($canEdit, Request $request = null, $id = null)
    {
        $today = DateTime::today()->startOfDay();
        if ($id == null) {
            $currentFinancialCustody = $this->getCurrentFinancialCustody($request);
        } else {
            $currentFinancialCustody = FinancialCustody::find($id);
        }
        //if ($currentFinancialCustody->approved_at != null) {
        $canEdit = 0;
        //}
        $financialCustodyDeposits      = $currentFinancialCustody->deposits;
        $amounts['current_dayOfWeek']  = $today->dayOfWeek;
        $amounts['current_dayOfMonth'] = $today->day;
        $amounts['current_month']      = $today->month - 1;
        $amounts['current_year']       = $today->year;
        $amounts['withdrawsAmount']    = $currentFinancialCustody->withdraws()->sum('withdrawValue');
        $amounts['depositsAmount']     = $currentFinancialCustody->deposits()->sum('withdrawValue');
        $amounts['currentAmount']      = $amounts['depositsAmount'] - $amounts['withdrawsAmount'];
        $financialCustodyItems         = $currentFinancialCustody->withdraws;

        $employees = Employee::all('id', 'name')->mapWithKeys(function ($emp) {
            return [$emp->id => $emp->name];
        });
        $expenses  = Expenses::all('id', 'name');

        $clients   = Client::all()->mapWithKeys(function ($client) {
            return [$client->id => [
                'name'           => $client->name,
                'hasOpenProcess' => $client->hasOpenProcess(),
                'processes'      => $client->processes->mapWithKeys(function ($process) {
                    return [$process->id => [
                        'name'   => $process->name,
                        'status' => $process->status
                    ]
                    ];
                })
            ]
            ];
        });
        $suppliers = Supplier::all()->mapWithKeys(function ($supplier) {
            return [$supplier->id => [
                'name'           => $supplier->name,
                'hasOpenProcess' => $supplier->hasOpenProcess(),
                'processes'      => $supplier->processes->mapWithKeys(function ($process) {
                    return [$process->id => [
                        'name'   => $process->name,
                        'status' => $process->status
                    ]
                    ];
                })
            ]
            ];
        });

        $payMethods      = PaymentMethods::all();
        $employeeActions = collect(EmployeeActions::all())->toJson();


        return view('financial-custody.expenses-items')->with([
                                                                  'employee_id'              => $currentFinancialCustody->employee->id,
                                                                  'employee_name'            => $currentFinancialCustody->employee->name,
                                                                  'approved_at'              => $currentFinancialCustody->approved_at,
                                                                  'approved_by'              => isset($currentFinancialCustody->approved_at) ? $currentFinancialCustody->approved_by_data->load('employee')->name : null,
                                                                  'amounts'                  => $amounts,
                                                                  'clients'                  => $clients,
                                                                  'employees'                => $employees,
                                                                  'suppliers'                => $suppliers,
                                                                  'expenses'                 => $expenses,
                                                                  'payMethods'               => $payMethods,
                                                                  'employeeActions'          => $employeeActions,
                                                                  'financialCustodyItems'    => $financialCustodyItems,
                                                                  'canEdit'                  => $canEdit,
                                                                  'financialCustodyDeposits' => $financialCustodyDeposits
                                                              ]);
    }

    /**
     * @param Request $request
     * @return FinancialCustody|null
     */
    private function getCurrentFinancialCustody(Request $request)
    {
        $employee_id     = $request->get('employee_id');
        $currentEmployee = Employee::find(auth()->user()->employee_id);
        if (isset($employee_id) && $employee_id != null) {
            $currentEmployee = Employee::find($employee_id);
        }
        $currentFinancialCustody = $currentEmployee->currentFinancialCustody();

        if ($currentFinancialCustody == null || count($currentFinancialCustody->deposits) <= 0) {
            abort(403, 'عفوا لا يوجد عهدة مسجلة. ');
        }
        return $currentFinancialCustody;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        try {
            $currentFinancialCustody = $this->getCurrentFinancialCustody($request);
            $request['due_date']     = DateTime::parse($request->due_date);
            $request['user_id']      = auth()->user()->id;
            $financialCustodyItem    = $currentFinancialCustody->withdraws()->create($request->all());
            return response()->json([
                                        'success'       => true,
                                        'id'            => $financialCustodyItem->id,
                                        'currentAmount' => $this->calculateCurrentAmount($request),
                                        'message'       => 'تم اضافة وارد جديد.'
                                    ]);
        } catch (\Exception $ex) {
            throw new ValidationException("حدث حطأ في حفظ البيانات. {$ex->getMessage()}");
        }
    }

    private function calculateCurrentAmount(Request $request)
    {
        $currentFinancialCustody = $this->getCurrentFinancialCustody($request);
        $depositValue            = $currentFinancialCustody->deposits()->sum('withdrawValue');
        $withdrawValue           = $currentFinancialCustody->withdraws()->sum('withdrawValue');
        return round(($depositValue - $withdrawValue), Helpers::getDecimalPointCount());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @Post("/acceptItems", as="financialCustodyItems.acceptItems")
     * @throws ValidationException
     */
    public function acceptItems(Request $request)
    {
        $c_user_id = auth()->user()->id;
        DB::beginTransaction();
        $all = $request->all();
        $rowsIds = [];
        try {
            $date = DateTime::parse($request->due_date);
            foreach ($all['rowsIds'] as $id) {
                $financialCustodyItem    = FinancialCustodyItem::find($id);
                $currentFinancialCustody = $financialCustodyItem->financialCustody;
                $depositWithdraw         = DepositWithdraw::create($financialCustodyItem->toArray());

                FinancialCustodyItem::where('id', $id)->update([
                                                                   'saveStatus'    => 2,
                                                                   'daily_cash_id' => $depositWithdraw->id,
                                                                   'approved_at'   => DateTime::now(),
                                                                   'approved_by'   => $c_user_id
                                                               ]);

                DepositWithdraw::create([
                                            'depositValue'         => $depositWithdraw->withdrawValue,
                                            'recordDesc'           => "رد {$currentFinancialCustody->description}",
                                            'employee_id'          => $currentFinancialCustody->employee_id,
                                            'expenses_id'          => EmployeeActions::FinancialCustodyRefund,
                                            'financial_custody_id' => $currentFinancialCustody->id,
                                            'user_id'              => $c_user_id,
                                            'payMethod'            => PaymentMethods::CASH,
                                            'due_date'             => $date
                                        ]);

                $rowsIds[$id] = "Done";
            }
            DB::commit();
            return response()->json(array(
                                        'success'       => true,
                                        'rowsIds'       => $rowsIds,
                                        'currentAmount' => $this->calculateCurrentAmount($request),
                                        'message'       => 'تم حفظ الوارد.',
                                        //'errors' => $validator->getMessageBag()->toArray()
                                    ));
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new ValidationException("حدث حطأ في حفظ البيانات. {$ex->getMessage()}");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @Post("/removeItems", as="financialCustodyItems.removeItems")
     * @throws \Exception
     */
    public function removeItems(Request $request)
    {
        $all = $request->all();

        $rowsIds = [];

        foreach ($all['rowsIds'] as $id) {
            FinancialCustodyItem::where('id', $id)->delete();
            $rowsIds[$id] = "Done";
        }

        return response()->json(array(
                                    'success'       => true,
                                    'rowsIds'       => $rowsIds,
                                    'currentAmount' => $this->calculateCurrentAmount($request),
                                    'message'       => 'تم حذف الوارد.',
                                    //'errors' => $validator->getMessageBag()->toArray()
                                ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @Post("/financialCustodyRefund", as="financialCustodyItems.financialCustodyRefund")
     * @throws \Exception
     */
    public function financialCustodyRefund(Request $request)
    {
        $c_user_id = auth()->user()->id;
        DB::beginTransaction();
        try {
            $currentFinancialCustody = $this->getCurrentFinancialCustody($request);

            foreach ($currentFinancialCustody->withdraws as $financialCustodyItem) {
                if (!isset($financialCustodyItem->approved_at)) {
                    $depositWithdraw = DepositWithdraw::create($financialCustodyItem->toArray());
                    $financialCustodyItem->update([
                                                      'saveStatus'    => 2,
                                                      'daily_cash_id' => $depositWithdraw->id,
                                                      'approved_at'   => DateTime::now(),
                                                      'approved_by'   => $c_user_id
                                                  ]);
                }
            }
            $date = DateTime::parse($request->due_date);
            DepositWithdraw::create([
                                        'depositValue'         => ($currentFinancialCustody->deposits()->sum('withdrawValue') - $currentFinancialCustody->refundedDeposits()->sum('depositValue')),
                                        'recordDesc'           => "رد {$currentFinancialCustody->description}",
                                        'employee_id'          => $currentFinancialCustody->employee_id,
                                        'expenses_id'          => EmployeeActions::FinancialCustodyRefund,
                                        'financial_custody_id' => $currentFinancialCustody->id,
                                        'user_id'              => $c_user_id,
                                        'payMethod'            => PaymentMethods::CASH,
                                        'due_date'             => $date
                                    ]);

            $currentFinancialCustody->approved_at = DateTime::now();
            $currentFinancialCustody->approved_by = auth()->user()->id;
            $currentFinancialCustody->save();
            DB::commit();

            return redirect()->route("financialCustodyItems.show", ['id' => $currentFinancialCustody->id])->with('success', 'تم تسوية العهدة.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', "حدث حطأ في حفظ البيانات. {$ex->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        if (auth()->user()->hasRole('admin')) {
            return $this->getFinancialCustodyItems(1, $request, $id);
        } else {
            return $this->getFinancialCustodyItems(0, $request, $id);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $financialCustodyItem = FinancialCustodyItem::findOrFail($id);
        // $request['due_date']  = DateTime::parse($request->due_date);
        $request['user_id'] = auth()->user()->id;
        unset($request['due_date']); // to prevent changing the date
        $financialCustodyItem->update($request->all());
        return response()->json(array(
                                    'success'       => true,
                                    'id'            => $financialCustodyItem->id,
                                    //'$financialCustodyItem->cbo_processe' => $financialCustodyItem->cbo_processes,
                                    'currentAmount' => $this->calculateCurrentAmount($request),
                                    'message'       => 'تم تعديل وارد جديد.',
                                    //'errors' => $validator->getMessageBag()->toArray()
                                ));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $all     = $request->all();
        $rowsIds = [];
        foreach ($all['rowsIds'] as $id) {
            FinancialCustodyItem::where('id', $id)->delete();
            $rowsIds[$id] = "Done";
        }
        return response()->json(array(
                                    'success'       => true,
                                    'rowsIds'       => $rowsIds,
                                    'currentAmount' => $this->calculateCurrentAmount($request),
                                    'message'       => 'تم حذف الوارد.',
                                    //'errors' => $validator->getMessageBag()->toArray()
                                ));
    }

}