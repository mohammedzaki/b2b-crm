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
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DepositWithdraw;
use App\Models\Employee;
use App\Models\Expenses;
use App\Models\FinancialCustody;
use App\Models\FinancialCustodyItem;
use App\Models\Loans;
use App\Models\Supplier;
use App\Models\SupplierProcess;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


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
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        $id        = $request['id'];
        return $this->getFinancialCustodyItems($startDate, $endDate, auth()->user()->hasRole('admin'), $request, false, $id);
    }

    private function getFinancialCustodyItems(DateTime $startDate, DateTime $endDate, $canEdit, Request $request, $viewAll = false, $id = null)
    {
        if (!isset($id) & empty($id)) {
            $currentFinancialCustody = $this->getCurrentFinancialCustody($request);
        } else {
            $currentFinancialCustody = FinancialCustody::find($id);
        }
        //if ($currentFinancialCustody->approved_at != null) {
        // $canEdit = 0;
        //}
        $financialCustodyDeposits      = $currentFinancialCustody->deposits;
        $depositValue                  = $currentFinancialCustody->totalDeposits();
        $withdrawValue                 = $currentFinancialCustody->totalWithdraws();
        $amounts['current_dayOfWeek']  = $startDate->dayOfWeek;
        $amounts['current_dayOfMonth'] = $startDate->day;
        $amounts['current_month']      = $startDate->month - 1;
        $amounts['current_year']       = $startDate->year;
        $amounts['withdrawsAmount']    = $withdrawValue;
        $amounts['depositsAmount']     = $depositValue;
        $amounts['currentAmount']      = round(($depositValue - $withdrawValue), Helpers::getDecimalPointCount());
        if (!$viewAll) {
            $financialCustodyItems = $currentFinancialCustody->withdraws()->whereBetween('due_date', [
                $startDate,
                $endDate,
            ])->get();
        } else {
            $financialCustodyItems = $currentFinancialCustody->withdraws;
        }
        return view('financial-custody.expenses-items')->with([
            'employee_id'              => $currentFinancialCustody->employee->id,
            'employee_name'            => $currentFinancialCustody->employee->name,
            'approved_at'              => $currentFinancialCustody->approved_at,
            'approved_by'              => isset($currentFinancialCustody->approved_at) ? $currentFinancialCustody->approved_by_data->load('employee')->name : null,
            'amounts'                  => $amounts,
            'clients'                  => Client::allAsList(),
            'employees'                => Employee::allAsList(),
            'suppliers'                => Supplier::allAsList(),
            'expenses'                 => Expenses::allAsList(),
            'loans'                    => Loans::allAsList(),
            'payMethods'               => PaymentMethods::all(),
            'employeeActions'          => collect(EmployeeActions::allForFc())->toJson(),
            'financialCustodyItems'    => $financialCustodyItems,
            'canEdit'                  => $canEdit ? 1 : 0,
            'targetDate'               => $startDate,
            'financialCustodyId'       => $id,
            'financialCustodyDeposits' => $financialCustodyDeposits,
        ]);
    }

    /**
     * @param Request $request
     * @return FinancialCustody|null
     */
    private function getCurrentFinancialCustody(Request $request, $checkMonth = true)
    {
        $employee_id        = $request->get('employee_id');
        $financialCustodyId = $request->get('f_id');
        $currentEmployee    = Employee::find(auth()->user()->employee_id);
        if (isset($employee_id) && $employee_id != null) {
            $currentEmployee = Employee::find($employee_id);
        }
        if (isset($financialCustodyId)) {
            $currentFinancialCustody = FinancialCustody::find($financialCustodyId);
        } else {
            $currentFinancialCustody = $currentEmployee->currentFinancialCustody();
        }
        if ($currentFinancialCustody == null) { // || count($currentFinancialCustody->deposits) <= 0) {
            abort(422, 'عفوا لا يوجد عهدة مسجلة. ');
        }
        if ($checkMonth) {
            $date = DateTime::parse($request->due_date);
            if ($date->month !== DateTime::parse($currentFinancialCustody->due_date)->month) {
                abort(422, 'عفوا العهدة الحالية لا تصلح. يجب ان يكون التاريخ موافق لشهر العهدة برجاء ترحيل  باقي العهدة و فتح عهدة للشهر الجديد ');
            }
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
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        try {
            $currentFinancialCustody = $this->getCurrentFinancialCustody($request);
            $request['due_date']     = DateTime::parse($request->due_date);
            $request['user_id']      = auth()->user()->id;
            $request['saveStatus']   = 1;
            $financialCustodyItem    = $currentFinancialCustody->withdraws()->create($request->all());
            return response()->json([
                'success'       => true,
                'id'            => $financialCustodyItem->id,
                'currentAmount' => $this->calculateCurrentAmount($request),
                'message'       => 'تم اضافة وارد جديد.',
            ]);
        } catch (\Exception $ex) {
            throw new ValidationException("حدث حطأ في حفظ البيانات. {$ex->getMessage()}");
        }
    }

    private function calculateCurrentAmount(Request $request)
    {
        $currentFinancialCustody = $this->getCurrentFinancialCustody($request);
        $depositValue            = $currentFinancialCustody->totalDeposits();
        $withdrawValue           = $currentFinancialCustody->totalWithdraws();
        return round(($depositValue - $withdrawValue), Helpers::getDecimalPointCount());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @Post("/acceptItems", as="financialCustodyItems.acceptItems")
     * @throws ValidationException
     */
    public function acceptItems(Request $request)
    {
        $c_user_id = auth()->user()->id;
        DB::beginTransaction();
        $all     = $request->all();
        $rowsIds = [];
        try {
            $date = DateTime::parse($request->due_date);
            foreach ($all['rowsIds'] as $id) {
                FinancialCustodyItem::where('id', $id)->update([
                    'saveStatus'  => 2,
                    'approved_at' => DateTime::now(),
                    'approved_by' => $c_user_id,
                ]);
                $this->checkProcessClosed($id);
                $rowsIds[$id] = "Done";
            }
            DB::commit();
            return response()->json([
                'success'       => true,
                'rowsIds'       => $rowsIds,
                'currentAmount' => $this->calculateCurrentAmount($request),
                'message'       => 'تم حفظ الوارد.',
                //'errors' => $validator->getMessageBag()->toArray()
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new ValidationException("حدث حطأ في حفظ البيانات. {$ex->getMessage()}");
        }
    }


    private function checkProcessClosed($financialCustodyItemId)
    {
        $fItem = FinancialCustodyItem::where('id', $financialCustodyItemId)->first();
        try {
            if (!empty($fItem->cbo_processes) && $fItem->cbo_processes != '-1') {
                if (!empty($fItem->supplier_id)) {
                    $process = SupplierProcess::withTrashed()->findOrFail($fItem->cbo_processes);
                    $process->checkProcessMustClosed();
                }
            }
        } catch(\Exception $ex) {
            dd($fItem);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @Post("/unlockItems", as="financialCustodyItems.unlockItems")
     * @throws ValidationException
     */
    public function unlockItems(Request $request)
    {
        $c_user_id = auth()->user()->id;
        DB::beginTransaction();
        $all     = $request->all();
        $rowsIds = [];
        try {
            foreach ($all['rowsIds'] as $id) {
                $financialCustodyItem = FinancialCustodyItem::find($id);
                DepositWithdraw::where('id', $financialCustodyItem->daily_cash_id)->delete();
                DepositWithdraw::where('id', $financialCustodyItem->daily_cash_refund_id)->delete();

                FinancialCustodyItem::where('id', $id)->update([
                    'saveStatus'  => 1,
                    // 'daily_cash_id'        => null,
                    // 'daily_cash_refund_id' => null,
                    'approved_at' => null,
                    'approved_by' => null,
                ]);
                $rowsIds[$id] = "Done";
            }
            DB::commit();
            return response()->json([
                'success'       => true,
                'rowsIds'       => $rowsIds,
                'currentAmount' => $this->calculateCurrentAmount($request),
                'message'       => 'تم حفظ الوارد.',
                //'errors' => $validator->getMessageBag()->toArray()
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new ValidationException("حدث حطأ في حفظ البيانات. {$ex->getMessage()}");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
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

        return response()->json([
            'success'       => true,
            'rowsIds'       => $rowsIds,
            'currentAmount' => $this->calculateCurrentAmount($request),
            'message'       => 'تم حذف الوارد.',
            //'errors' => $validator->getMessageBag()->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @Post("/financialCustodyRefund", as="financialCustodyItems.financialCustodyRefund")
     * @throws \Exception
     */
    public function financialCustodyRefund(Request $request)
    {
        $c_user_id = auth()->user()->id;
        DB::beginTransaction();
        try {
            $id = $request['id'];
            $date = DateTime::parse($request->due_date);
            if (!isset($id) & empty($id)) {
                $currentFinancialCustody = $this->getCurrentFinancialCustody($request, false);
            } else {
                $currentFinancialCustody = FinancialCustody::find($id);
            }
            if ($request['transfer'] != '2') {
                foreach ($currentFinancialCustody->withdraws as $financialCustodyItem) {
                    if (!isset($financialCustodyItem->approved_at)) {
                        $financialCustodyItem->update([
                            'saveStatus'  => 2,
                            'approved_at' => DateTime::now(),
                            'approved_by' => $c_user_id,
                        ]);
                        $this->checkProcessClosed($financialCustodyItem->id);
                    }
                }
                $currentFinancialCustody->approved_at = DateTime::now();
                $currentFinancialCustody->approved_by = auth()->user()->id;
            }
            $fcRemaining = ($currentFinancialCustody->totalDeposits() - $currentFinancialCustody->totalWithdraws());
            $currentFinancialCustody->save();
            if ($fcRemaining > 0) {
                DepositWithdraw::create([
                    'depositValue'         => $fcRemaining,
                    'recordDesc'           => "رد {$currentFinancialCustody->description}",
                    'employee_id'          => $currentFinancialCustody->employee_id,
                    'expenses_id'          => EmployeeActions::FinancialCustodyRefund,
                    'financial_custody_id' => $currentFinancialCustody->id,
                    'user_id'              => $c_user_id,
                    'payMethod'            => PaymentMethods::CASH,
                    'due_date'             => $date,
                ]);
                if ($request['transfer'] != '0') {
                    //transferred_from
                    $employee = Employee::findOrFail($currentFinancialCustody->employee_id);
                    $monthName               = $date->getMonthName();
                    $transferredFinancialCustody = [
                        'user_id'     => $c_user_id,
                        'description' => "عهدة شراء شهر {$monthName} ",
                        'notes'       => '',
                        'approved_by' => null,
                        'approved_at' => null,
                        'due_date'    => $request->due_date
                    ];
                    $transferredFinancialCustody = $employee->financialCustodies()->create($transferredFinancialCustody);
                    DepositWithdraw::create([
                        'withdrawValue'         => $fcRemaining,
                        'recordDesc'           => "باقي عهدة مرحلة {$currentFinancialCustody->description}",
                        'employee_id'          => $currentFinancialCustody->employee_id,
                        'expenses_id'          => EmployeeActions::FinancialCustodyRefund,
                        'financial_custody_id' => $currentFinancialCustody->id,
                        'user_id'              => $c_user_id,
                        'payMethod'            => PaymentMethods::CASH,
                        'due_date'             => $date,
                    ]);
                    $currentFinancialCustody->transferred_from = $transferredFinancialCustody->id;
                    $currentFinancialCustody->save();
                }
            }
            DB::commit();
            return redirect()->route("financialCustodyItems.index", ['id' => $currentFinancialCustody->id])->with('success', 'تم تسوية العهدة.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', "حدث حطأ في حفظ البيانات. {$ex->getMessage()}");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @Post("/financialCustodyReopen", as="financialCustodyItems.financialCustodyReopen")
     * @Middleware({"web", "auth", "ability:admin,financial-custody-reopen"})
     * @throws \Exception
     */
    public function financialCustodyReopen(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request['id'];
            if (!isset($id) & empty($id)) {
                $currentFinancialCustody = $this->getCurrentFinancialCustody($request);
            } else {
                $currentFinancialCustody = FinancialCustody::find($id);
            }
            DepositWithdraw::where([
                [
                    'financial_custody_id',
                    '=',
                    $currentFinancialCustody->id,
                ],
                [
                    'expenses_id',
                    '=',
                    EmployeeActions::FinancialCustodyRefund,
                ],
                [
                    'withdrawValue',
                    '=',
                    NULL,
                    'or',
                ],
                [
                    'withdrawValue',
                    '=',
                    0,
                ],
            ])->forceDelete();
            $currentFinancialCustody->approved_at = null;
            $currentFinancialCustody->approved_by = null;
            $currentFinancialCustody->save();
            DB::commit();
            return redirect()->route("financialCustodyItems.index", ['id' => $currentFinancialCustody->id])->with('success', 'تم إعادة فتح العهدة.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', "حدث حطأ في حفظ البيانات. {$ex->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        return $this->getFinancialCustodyItems($startDate, $endDate, auth()->user()->hasRole('admin'), $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $financialCustodyItem = FinancialCustodyItem::findOrFail($id);
        // $request['due_date']  = DateTime::parse($request->due_date);
        $request['user_id'] = auth()->user()->id;
        unset($request['due_date']); // to prevent changing the date
        $financialCustodyItem->update($request->all());
        return response()->json([
            'success'       => true,
            'id'            => $financialCustodyItem->id,
            //'$financialCustodyItem->cbo_processe' => $financialCustodyItem->cbo_processes,
            'currentAmount' => $this->calculateCurrentAmount($request),
            'message'       => 'تم تعديل وارد جديد.',
            //'errors' => $validator->getMessageBag()->toArray()
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $all     = $request->all();
        $rowsIds = [];
        foreach ($all['rowsIds'] as $id) {
            FinancialCustodyItem::where('id', $id)->first()->delete();
            $rowsIds[$id] = "Done";
        }
        return response()->json([
            'success'       => true,
            'rowsIds'       => $rowsIds,
            'currentAmount' => $this->calculateCurrentAmount($request),
            'message'       => 'تم حذف الوارد.',
            //'errors' => $validator->getMessageBag()->toArray()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     * @Get("/search", as="financialCustodyItems.search")
     */
    public function search(Request $request)
    {
        $viewAll = isset($request['view']) & $request['view'] == "1";
        $id      = $request['id'];
        if (!$viewAll) {
            $startDate = DateTime::parse($request['targetdate'])->startOfDay();
            $endDate   = DateTime::parse($request['targetdate'])->endOfDay();
        } else {
            $startDate = DateTime::today()->startOfDay();
            $endDate   = DateTime::today()->endOfDay();
        }
        return $this->getFinancialCustodyItems($startDate, $endDate, auth()->user()->hasRole('admin'), $request, $viewAll, $id);
    }

}