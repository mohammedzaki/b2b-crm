<?php

namespace App\Reports\Expenses;

use App\Reports\BaseReport;

class ExpensesDetailed extends BaseReport {

    public $expenseName,
            $expenses,
            $allExpensesTotalPaid;
    
    protected $reportName = "ExpensesDetailedReport.pdf";

    function SetHtmlBody() {
        return '<!DOCTYPE html>
                <html>
                    <head>
                        
                    </head>
                    <body>
                        ' . $this->SetPageHeader() . '
                        ' . $this->SetPageFooter() . '
                        ' . $this->AddAllExpense() . '
                    </body>
                </html>';
    }

    function SetCSS() {
        $path = public_path('ReportsHtml/Expense/ExpenseDetailed.css');
        return file_get_contents($path);
    }

    function AddAllExpense() {
        $expenseesHtml = '';
        foreach ($this->expenses as $expense) {
            $expenseesHtml .= $this->AddExpense($expense);
        }
        return $expenseesHtml;
    }

    function AddExpense($expense) {
        $expenseHtml = '<div class="expense">';
        $expenseHtml .= $this->SetExpenseHeader($expense['expenseName'], $expense['expenseNum']);
        $expenseHtml .= '   <table class="tg">';
        $expenseHtml .= $this->AddExpenseItems($expense['expenseDetails']);
        $expenseHtml .= $this->AddExpenseItemsFooter($expense['expenseTotalPaid']);
        $expenseHtml .= '   </table>
                            <div class="lineBreak"></div>
                        </div>';
        return $expenseHtml;
    }

    function SetExpenseHeader($expenseName, $expenseNum) {
        return '
                <div class="processHeader">
                    <table class="headerTable">
                        <tr>
                            <td class="expenseLabel">اسم المصروف :</td>
                            <td class="expenseName">' . $expenseName . '</td>
                        </tr>
                    </table>
                </div>';
    }

    function AddExpenseItems($expenseDetails) {
        $items = '';
        $items .= $items . $this->AddExpenseItemsHeader();
        foreach ($expenseDetails as $details) {
            $items .= $this->AddExpenseItem($details['desc'], $details['paid'], $details['date']);
        }
        return $items;
    }

    function AddExpenseItemsHeader() {
        return '<tr>
                    <th style="width: 50%">البيان</th>
                    <th style="width: 20%;">الاجمالى</th>
                    <th style="width: 30%">تاريـــــــــــخ</th>
                </tr>';
    }

    function AddExpenseItem($name, $paid, $date) {
        return '<tr>
                    <td> ' . $name . ' </td>
                    <td class="redColor" > ' . $paid . ' </td>
                    <td> ' . $date . ' </td>
                </tr>
                ';
    }

    function AddExpenseItemsFooter($paid) {
        return '<tr class="last">
                    <td> </td>
                    <td class="redColor" colspan="1">' . $paid . '</td>
                    <td class="redColor" >الاجمالـــــــــــــــــــــــــــــــــــى</td>
                </tr>
                ';
    }
}
