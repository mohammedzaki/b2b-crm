<?php
/**
 * Created by PhpStorm.
 * User: mohamedzaki
 * Date: 12/7/20
 * Time: 6:27 PM
 */

namespace App\Http\Controllers\CashManagement\Journal;

use App\Constants\ChequeStatuses;
use App\Constants\EmployeeActions;
use App\Exceptions\ValidationException;
use App\Extensions\DateTime;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BankCashItem;
use App\Models\BankChequeBook;
use App\Models\BankProfile;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Models\Employee;
use App\Models\Expenses;
use App\Models\Loans;
use App\Models\OpeningAmount;
use App\Models\Supplier;
use App\Models\SupplierProcess;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * Description of BankCashItemController
 *
 * @author Mohammed Zaki mohammedzaki.dev@gmail.com
 *
 * @Controller(prefix="/bank-cash")
 * @Middleware({"web", "auth", "ability:admin,bank-cash"})
 */
class BankCashController extends
    Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @Get("", as="bank-cash.index")
     */
    public function index(Request $request)
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        return $this->getBankCashItem($startDate, $endDate, auth()->user()->hasRole('admin'), $request->bankId);
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param $canEdit
     * @param $bankId
     * @param null $chequeBookId
     * @param string $viewName
     * @param null $depositOrWithdrawField
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function getBankCashItem(DateTime $startDate, DateTime $endDate, $canEdit, $bankId, $chequeBookId = null, $viewName = 'cash.journal.bank-cash', $depositOrWithdrawField = null)
    {
        $banks = BankProfile::allAsList();
        if ($bankId == null) {
            return view($viewName)->with([
                                             'banks'  => $banks,
                                             'bankId' => $bankId
                                         ]);
        }
        if ($chequeBookId == null) {
            $id             = $bankId === 'all' ? null : $bankId;
            $bankCashItems = BankCashItem:: //whereIn('cheque_status', [ChequeStatuses::POSTDATED, ChequeStatuses::POSTPONED])
                when($depositOrWithdrawField == 'guarantee', function ($query) {
                    return $query->whereIn('cheque_status', [ChequeStatuses::GUARANTEE]);
                })
                ->when($depositOrWithdrawField != 'guarantee', function ($query) {
                    return $query->whereIn('cheque_status', [ChequeStatuses::POSTDATED, ChequeStatuses::POSTPONED]);
                })
                ->when(($depositOrWithdrawField && $depositOrWithdrawField != 'guarantee'),
                    function ($query) use ($depositOrWithdrawField) {
                        return $query->whereNotNull($depositOrWithdrawField);
                    },
                    function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('due_date', [$startDate, $endDate]);
                    }
                )
                ->when($id, function ($query, $id) {
                    return $query->where('bank_profile_id', $id);
                })
                ->get();
            $chequeBookName = '';
        } else {
            $chequeBook     = BankChequeBook::find($chequeBookId);
            $chequeBookName = $chequeBook->name;
            $bankCashItems  = $chequeBook->getBankCashItemsItems();
        }
        switch ($viewName) {
            case "cash.journal.cheque-book":
            case "cash.journal.withdraw-cheque":
                $chequeStatuses        = ChequeStatuses::withdrawStatuses();
                $depositDefaultStatus  = ChequeStatuses::POSTDATED;
                $withdrawDefaultStatus = ChequeStatuses::POSTDATED;
                break;
            case "cash.journal.deposit-cheque":
                $chequeStatuses        = ChequeStatuses::depositStatuses();
                $depositDefaultStatus  = ChequeStatuses::POSTDATED;
                $withdrawDefaultStatus = ChequeStatuses::POSTDATED;
                break;
            case "cash.journal.guarantee-cheque":
                $chequeStatuses        = ChequeStatuses::depositStatuses();
                $depositDefaultStatus  = ChequeStatuses::GUARANTEE;
                $withdrawDefaultStatus = ChequeStatuses::POSTDATED;
                break;
            default:
                $chequeStatuses        = ChequeStatuses::all();
                $depositDefaultStatus  = ChequeStatuses::BANK_DEPOSIT;
                $withdrawDefaultStatus = ChequeStatuses::BANK_WITHDRAW;
                break;
        }
        $numbers['current_dayOfWeek']  = $startDate->dayOfWeek;
        $numbers['current_dayOfMonth'] = $startDate->day;
        $numbers['current_month']      = $startDate->month - 1;
        $numbers['current_year']       = $startDate->year;

        if ($bankId == 'all') {
            $bankProfileName                     = 'الكل';
            $numbers['postdatedWithdrawCheques'] = 0;
            $numbers['postdatedDepositCheques']  = 0;
            $numbers['currentAmount']            = 0;
            $numbers['cashBalance']              = 0;
            $BankProfiles                        = BankProfile::all();
            foreach ($BankProfiles as $index => $bank) {
                $numbers['postdatedWithdrawCheques'] += $bank->totalPostdatedWithdrawCheques();
                $numbers['postdatedDepositCheques']  += $bank->totalPostdatedDepositCheques();
                $numbers['currentAmount']            += $bank->currentAmount();
                $numbers['cashBalance']              += $bank->cashBalance();
            }
        } else {
            $bankProfile                         = BankProfile::findOrFail($bankId);
            $bankProfileName                     = $bankProfile->name;
            $numbers['postdatedWithdrawCheques'] = $bankProfile->totalPostdatedWithdrawCheques();
            $numbers['postdatedDepositCheques']  = $bankProfile->totalPostdatedDepositCheques();
            $numbers['currentAmount']            = $bankProfile->currentAmount();
            $numbers['cashBalance']              = $bankProfile->cashBalance();
        }

        return view($viewName)->with([
                                         'banks'                 => $banks,
                                         'bankProfiles'          => BankProfile::allAsList(false),
                                         'numbers'               => $numbers,
                                         'clients'               => Client::allAsList(),
                                         'employees'             => Employee::allAsList(),
                                         'suppliers'             => Supplier::allAsList(),
                                         'expenses'              => Expenses::allAsList(),
                                         'loans'                 => Loans::allAsList(),
                                         'bankCashItems'         => $bankCashItems,
                                         'chequeStatuses'        => $chequeStatuses,
                                         'canEdit'               => $canEdit ? 1 : 0,
                                         'employeeActions'       => collect(EmployeeActions::all())->toJson(),
                                         'bankId'                => $bankId,
                                         'bankName'              => $bankProfileName,
                                         'chequeBookName'        => $chequeBookName,
                                         'chequeBookId'          => $chequeBookId,
                                         'depositDefaultStatus'  => $depositDefaultStatus,
                                         'withdrawDefaultStatus' => $withdrawDefaultStatus
                                     ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $chequeBookId
     * @return \Illuminate\Http\Response
     * @Get("/{chequeBookId}/index", as="bank-cash.chequeBooks")
     */
    public function chequeBooks(Request $request, $chequeBookId)
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        return $this->getBankCashItem($startDate, $endDate, auth()->user()->hasRole('admin'), $request->bankId, $chequeBookId, 'cash.journal.cheque-book');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @Get("/deposit-cheque", as="bank-cash.depositChequeBook")
     */
    public function depositChequeBook(Request $request)
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        return $this->getBankCashItem($startDate, $endDate, auth()->user()->hasRole('admin'), $request->bankId, null, 'cash.journal.deposit-cheque', 'depositValue');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @Get("/guarantee-cheque", as="bank-cash.guaranteeChequeBook")
     */
    public function guaranteeChequeBook(Request $request)
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        return $this->getBankCashItem($startDate, $endDate, auth()->user()->hasRole('admin'), $request->bankId, null, 'cash.journal.guarantee-cheque', 'guarantee');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @Get("/withdraw-cheque", as="bank-cash.withdrawChequeBook")
     */
    public function withdrawChequeBook(Request $request)
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        return $this->getBankCashItem($startDate, $endDate, auth()->user()->hasRole('admin'), $request->bankId, null, 'cash.journal.withdraw-cheque', 'withdrawValue');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     * @Post("", as="bank-cash.store")
     */
    public function store(Request $request)
    {
        $request['user_id'] = auth()->user()->id;
        DB::beginTransaction();
        $request['due_date'] = DateTime::parse($request->due_date);
        //dd($request->all());
        $bankCashItem = BankCashItem::create();
        if (isset($request->employee_id)) {
            $this->checkEmployeeAction($request, $bankCashItem->id);
        }
        $request['saveStatus'] = 1;
        $bankCashItem->update($request->all());
        $this->checkProcessClosed($bankCashItem);
        DB::commit();
        return response()->json([
                                    'success'       => true,
                                    'id'            => $bankCashItem->id,
                                    'currentAmount' => $this->calculateCurrentAmount(),
                                    'message'       => 'تم اضافة وارد جديد.'
                                ]);
    }

    function checkEmployeeAction(Request &$request, $id, $is_update = FALSE)
    {
        $employee = Employee::findOrFail($request->employee_id);
        if (!isset($request->expenses_id)) {
            throw new ValidationException('يجب اختيار اسم المصروف');
        }
        switch ($request->expenses_id) {
            case EmployeeActions::FinancialCustody:
                $this->setFinancialCustody($employee, $request);
                break;
            case EmployeeActions::PayLongBorrow:
                $this->payLongBorrow($employee, $request->depositValue, DateTime::parse($request->due_date), $id, $is_update);
                break;
            case EmployeeActions::SmallBorrow:
                break;
            default:
                DB::rollBack();
                throw new ValidationException('يجب اختيار اسم المصروف');
        }
    }

    function setFinancialCustody(Employee $employee, Request &$request)
    {
        $currentFinancialCustody = $employee->currentFinancialCustody();
        if ($currentFinancialCustody == null) {
            $date                    = DateTime::parse($request->due_date);
            $monthName               = $date->getMonthName();
            $currentFinancialCustody = [
                'user_id'     => $request->user_id,
                'description' => "عهدة شراء شهر {$monthName} ",
                'notes'       => '',
                'approved_by' => null,
                'approved_at' => null,
                'due_date'    => $request->due_date
            ];
            $currentFinancialCustody = $employee->financialCustodies()->create($currentFinancialCustody);
        }
        $request['financial_custody_id'] = $currentFinancialCustody->id;
    }

    function payLongBorrow(Employee $employee, $depositValue, $due_date, $id, $is_update)
    {
        if ($is_update) {
            $this->resetDiscountBorrows($id);
        }
        if ($employee->hasUnpaidBorrow()) {
            if ($employee->totalUnpaidBorrow() >= $depositValue) {
                $this->discountBorrows($employee, $depositValue, $due_date, $id);
            } else {
                DB::rollBack();
                throw new ValidationException("القيمة المردودة اكبر من اجمالى الدفعات المتبقية {$employee->totalUnpaidBorrow()}");
            }
        } else {
            DB::rollBack();
            throw new ValidationException('هذا الموظف ليس له دفعات متبقية');
        }
    }

    function resetDiscountBorrows($bankCashItem)
    {
        foreach ($bankCashItem->employeeLogBorrowBillings as $borrow) {
            $borrow->paying_status = EmployeeBorrowBilling::UN_PAID;
            $borrow->paid_amount   = null;
            $borrow->paid_date     = null;
            $borrow->deposit_id    = null;
            $borrow->save();
        }
    }

    function discountBorrows(Employee $employee, $depositValue, $due_date, $id)
    {
        foreach ($employee->unpaidBorrows() as $borrow) {
            if ($depositValue >= $borrow->pay_amount) {
                $depositValue          -= $borrow->pay_amount;
                $borrow->paying_status = EmployeeBorrowBilling::PAID;
                $borrow->paid_amount   = $borrow->pay_amount;
                $borrow->paid_date     = $due_date;
                $borrow->deposit_id    = $id;
                $borrow->save();
            } else if ($depositValue == 0) {
                break;
            } else {
                //$borrow->pay_amount -= $depositValue;
                $borrow->paid_amount = $depositValue;
                $borrow->paid_date   = $due_date;
                $borrow->deposit_id  = $id;
                $borrow->save();
                break;
            }
        }
    }

    private function checkProcessClosed(BankCashItem $bankCashItem)
    {
        if (!empty($bankCashItem->cbo_processes)) {
            if (!empty($bankCashItem->client_id)) {
                $process = ClientProcess::findOrFail($bankCashItem->cbo_processes);
                $process->checkProcessMustClosed();
            } else if (!empty($bankCashItem->supplier_id)) {
                $process = SupplierProcess::findOrFail($bankCashItem->cbo_processes);
                $process->checkProcessMustClosed();
            }
        }
    }

    private function calculateCurrentAmount($endDate = null, $startDate = '2000-01-01 00:00:00')
    {
        if (!isset($endDate)) {
            $endDate = DateTime::today()->format('Y-m-d 00:00:00');
        }
        $depositValue  = BankCashItem::whereBetween('due_date', [$startDate, $endDate])->sum('depositValue');
        $withdrawValue = BankCashItem::whereBetween('due_date', [$startDate, $endDate])->sum('withdrawValue');
        $openingAmount = OpeningAmount::whereBetween('deposit_date', [$startDate, $endDate])->sum('amount');
        return round(($depositValue + $openingAmount) - $withdrawValue, Helpers::getDecimalPointCount());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @Post("/lockSaveAll", as="bank-cash.lockSaveAll")
     */
    public function lockSaveAll(Request $request)
    {
        $all = $request->all();

        $rowsIds = [];

        foreach ($all['rowsIds'] as $id) {
            BankCashItem::where('id', $id)->update(['saveStatus' => 2]);
            $rowsIds[$id] = "Done";
        }
        return response()->json(array(
                                    'success'       => true,
                                    'rowsIds'       => $rowsIds,
                                    'currentAmount' => $this->calculateCurrentAmount(),
                                    'message'       => 'تم حفظ الوارد.',
                                    //'errors' => $validator->getMessageBag()->toArray()
                                ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @Post("/removeSelected", as="bank-cash.removeSelected")
     * @throws \Exception
     */
    public function removeSelected(Request $request)
    {
        $validator = $this->validator($request->all());
        $all       = $request->all();

        $rowsIds = [];

        foreach ($all['rowsIds'] as $id) {
            $bankCashItem = BankCashItem::findOrFail($id);
            BankCashItem::where('id', $id)->first()->delete();
            $this->checkProcessClosed($bankCashItem);
            $this->resetDiscountBorrows($bankCashItem);
            $rowsIds[$id] = "Done";
        }
        return response()->json(array(
                                    'success'       => true,
                                    'rowsIds'       => $rowsIds,
                                    'currentAmount' => $this->calculateCurrentAmount(),
                                    'message'       => 'تم حذف الوارد.',
                                    //'errors' => $validator->getMessageBag()->toArray()
                                ));
    }

    protected function validator(array $data, $id = null)
    {
        $validator = Validator::make($data, [
            /* 'depositValue' => 'numeric',
              'withdrawValue' => 'numeric',
              'recordDesc' => 'required|string',
              'cbo_processes' => 'numeric',
              'client_id' => 'exists:clients,id',
              'employee_id' => 'exists:employees,id',
              'supplier_id' => 'exists:suppliers,id',
              //'expenses_id' => 'exists:expenses,id',
              'payMethod' => 'required|numeric',
              'notes' => 'string' */
        ]);

        $validator->setAttributeNames([
                                          /* 'depositValue' => 'قيمة الوارد',
                                            'withdrawValue' => 'قيمة المنصرف',
                                            'recordDesc' => 'البيان',
                                            'cbo_processes' => 'اسم العملية',
                                            'client_id' => 'اسم العميل',
                                            'employee_id' => 'مشرف العملية',
                                            'expenses_id' => 'اسم المصروف',
                                            'payMethod' => 'طريقة الدفع',
                                            'supplier_id' => 'اسم المورد',
                                            'notes' => 'ملاحظات' */
                                      ]);

        return $validator;
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  int $id
     * @return Response
     * @Get("/search", as="bank-cash.search")
     * @Middleware({"ability:admin,bank-cash-edit"})
     */
    public function search(Request $request)
    {
        $startDate = DateTime::parse($request['targetdate'])->startOfDay();
        $endDate   = DateTime::parse($request['targetdate'])->endOfDay();
        return $this->getBankCashItem($startDate, $endDate, auth()->user()->hasRole('admin'), $request->bankId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     * @throws ValidationException
     * @PUT("{id}", as="bank-cash.update")
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $request->user_id = auth()->user()->id;
        $bankCashItem     = BankCashItem::findOrFail($id);
        if (isset($request->employee_id)) {
            $this->checkEmployeeAction($request, $bankCashItem->id, TRUE);
        }
        unset($request['due_date']); // to prevent changing the date
        $bankCashItem->update($request->all());
        $this->checkProcessClosed($bankCashItem);
        DB::commit();
        return response()->json(array(
                                    'success'       => true,
                                    'id'            => $bankCashItem->id,
                                    //'$bankCashItem->cbo_processe' => $bankCashItem->cbo_processes,
                                    'currentAmount' => $this->calculateCurrentAmount(),
                                    'message'       => 'تم تعديل وارد جديد.',
                                    //'errors' => $validator->getMessageBag()->toArray()
                                ));
    }

}