<?php

namespace App\Reports\Expenses;

use App\Reports\BaseReport;

class ExpensesTotal extends BaseReport {

    public $expenseName,
            $expenses,
            $allExpensesTotalPaid;
    
    protected $reportName = "ExpenseTotalReport.pdf";

    function SetHtmlBody() {
        return '<!DOCTYPE html>
                <html>
                    <head>
                    
                    </head>
                    <body>
                        ' . $this->SetPageHeader() . '
                        ' . $this->SetPageFooter() . '
                        ' . $this->SetReportHeader($this->expenseName) . '
                        <table class="tg">
                            ' . $this->AddExpense() . '
                        </table>
                    </body>
                </html>
                ';
    }
    
    function SetCSS() {
        $path = public_path('ReportsHtml/Expense/ExpenseTotal.css');
        return file_get_contents($path);
    }
    
    function SetReportHeader($expenseName) {
        return '<div class="expenseHeader">
                    <table class="headerTable">
                        <tr>
                            <td class="expenseLabel">استعلام عن المصروفات</td>
                        </tr>
                    </table>
                </div>
                ';
    }
    
    function AddExpense() {
        $items = '';
        $items .= $items . $this->AddExpenseItemHeader();
        foreach ($this->expenses as $expense) {
            $items .= $this->AddExpenseItem($expense['expenseName'], $expense['expenseTotalPaid']);
        }
        $items .= $this->AddExpenseItemFooter($this->allExpensesTotalPaid);
        return $items;
    }
    
    function AddExpenseItemHeader() {
        return '<tr>
                    <th>اسم المصروف</th>
                    <th>اجمالى المدفوع</th>
                </tr>
                ';
    }

    function AddExpenseItem($name, $paid) {
        return '<tr>
                    <td>' . $name . '</td>
                    <td>' . $paid . '</td>
                </tr>
                ';
    }
    
    function AddExpenseItemFooter($paid) {
        return '<tr class="last">
                    <td class="redColor">الاجمالى</td>
                    <td class="redColor">' . $paid . '</td>
                </tr>
                ';
    }

}
