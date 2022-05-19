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
class BankCashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @Get("", as="bankCash.index")
     */
    public function index(Request $request)
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        if (auth()->user()->hasRole('admin')) {
            return $this->getBankCashItem($startDate, $endDate, 1, $request->bankId);
        } else {
            return $this->getBankCashItem($startDate, $endDate, 0, $request->bankId);
        }
    }

    private function getBankCashItem(DateTime $startDate, DateTime $endDate, $canEdit, $bankId, $chequeBookId = null, $viewName = 'cash.journal.bank-cash', $dwField = null)
    {
        $banks = BankProfile::allAsList();
        if ($bankId == null) {
            return view($viewName)->with([
                                             'banks'  => $banks,
                                             'bankId' => $bankId
                                         ]);
        }
        if ($chequeBookId == null) {
            if (!empty($dwField)) {
                $bankCashItemsItems = BankCashItem::whereIn('cheque_status', [ChequeStatuses::POSTDATED, ChequeStatuses::POSTPONED])
                                                  ->whereNotNull($dwField)->get();
            } else {
                $bankCashItemsItems = BankCashItem::whereBetween('due_date', [$startDate, $endDate])->get();
            }
            $chequeBookName = '';
        } else {
            $chequeBook         = BankChequeBook::find($chequeBookId);
            $chequeBookName     = $chequeBook->name;
            $bankCashItemsItems = $chequeBook->getBankCashItemsItems();
        }
        switch ($viewName) {
            case "cash.journal.cheque-book":
            case "cash.journal.withdraw-cheque":
                $chequeStatuses = ChequeStatuses::withdrawStatuses();
                break;
            case "cash.journal.deposit-cheque":
                $chequeStatuses = ChequeStatuses::depositStatuses();
                break;
            default:
                $chequeStatuses = ChequeStatuses::all();
                break;
        }
        $numbers['clients_number']         = Client::count();
        $numbers['suppliers_number']       = Supplier::count();
        $numbers['process_number']         = ClientProcess::count();
        $numbers['Supplierprocess_number'] = SupplierProcess::count();
        $numbers['current_dayOfWeek']      = $startDate->dayOfWeek;
        $numbers['current_dayOfMonth']     = $startDate->day;
        $numbers['current_month']          = $startDate->month - 1;
        $numbers['current_year']           = $startDate->year;
        $numbers['withdrawsAmount']        = BankCashItem::whereBetween('due_date', [$startDate, $endDate])->sum('withdrawValue');
        $numbers['depositsAmount']         = BankCashItem::whereBetween('due_date', [$startDate, $endDate])->sum('depositValue');
        $numbers['currentAmount']          = $this->calculateCurrentAmount($endDate);
        $numbers['previousDayAmount']      = $this->calculateCurrentAmount($endDate->addDay(-1));
        $employees                         = Employee::allAsList();
        $expenses                          = Expenses::all('id', 'name');
        $clients                           = Client::allAsList();
        $suppliers                         = Supplier::allAsList();
        $employeeActions                   = collect(EmployeeActions::all())->toJson();
        $bankProfile                       = BankProfile::findOrFail($bankId);
        return view($viewName)->with([
                                         'banks'           => $banks,
                                         'numbers'         => $numbers,
                                         'clients'         => $clients,
                                         'employees'       => $employees,
                                         'suppliers'       => $suppliers,
                                         'expenses'        => $expenses,
                                         'bankCashItems'   => $bankCashItemsItems,
                                         'chequeStatuses'  => $chequeStatuses,
                                         'canEdit'         => $canEdit,
                                         'employeeActions' => $employeeActions,
                                         'bankId'          => $bankId,
                                         'bankName'        => $bankProfile->name,
                                         'chequeBookName'  => $chequeBookName,
                                         'chequeBookId'    => $chequeBookId
                                     ]);
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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $chequeBookId
     * @return \Illuminate\Http\Response
     * @Get("/{chequeBookId}/index", as="bankCash.chequeBooks")
     */
    public function chequeBooks(Request $request, $chequeBookId)
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        if (auth()->user()->hasRole('admin')) {
            return $this->getBankCashItem($startDate, $endDate, 1, $request->bankId, $chequeBookId, 'cash.journal.cheque-book');
        } else {
            return $this->getBankCashItem($startDate, $endDate, 0, $request->bankId, $chequeBookId, 'cash.journal.cheque-book');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @Get("/deposit-cheque", as="bankCash.depositChequeBook")
     */
    public function depositChequeBook(Request $request)
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        if (auth()->user()->hasRole('admin')) {
            return $this->getBankCashItem($startDate, $endDate, 1, $request->bankId, null, 'cash.journal.deposit-cheque', 'depositValue');
        } else {
            return $this->getBankCashItem($startDate, $endDate, 0, $request->bankId, null, 'cash.journal.deposit-cheque', 'depositValue');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @Get("/withdraw-cheque", as="bankCash.withdrawChequeBook")
     */
    public function withdrawChequeBook(Request $request)
    {
        $startDate = DateTime::today()->startOfDay();
        $endDate   = DateTime::today()->endOfDay();
        if (auth()->user()->hasRole('admin')) {
            return $this->getBankCashItem($startDate, $endDate, 1, $request->bankId, null, 'cash.journal.withdraw-cheque', 'withdrawValue');
        } else {
            return $this->getBankCashItem($startDate, $endDate, 0, $request->bankId, null, 'cash.journal.withdraw-cheque', 'withdrawValue');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     * @Post("", as="bankCash.store")
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     * @Post("/lockSaveAll", as="bankCash.lockSaveAll")
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
     * @Post("/removeSelected", as="bankCash.removeSelected")
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
     * @Get("/search", as="bankCash.search")
     * @Middleware({"ability:admin,bank-cash-edit"})
     */
    public function search(Request $request)
    {
        $startDate = DateTime::parse($request['targetdate'])->startOfDay();
        $endDate   = DateTime::parse($request['targetdate'])->endOfDay();
        if (auth()->user()->hasRole('admin')) {
            return $this->getBankCashItem($startDate, $endDate, 1, $request->bankId);
        } else {
            return $this->getBankCashItem($startDate, $endDate, 0, $request->bankId);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     * @throws ValidationException
     * @PUT("{id}", as="bankCash.update")
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