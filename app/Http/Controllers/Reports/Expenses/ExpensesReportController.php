<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Reports\Expenses;

use App\Extensions\DateTime;
use App\Http\Controllers\Controller;
use App\Models\Expenses;
use App\Reports\Expenses\expensesDetailed;
use App\Reports\Expenses\expensesTotal;
use Illuminate\Http\Request;

/**
 * @Controller(prefix="/reports/expenses")
 * @Middleware({"web", "auth"})
 */
class ExpensesReportController extends Controller {

    /**
     * Show the Index Page
     * @Get("/", as="reports.expenses.index")
     */
    public function index() {
        $expenses = Expenses::all(['id', 'name']);
        return view('reports.expenses.index', compact("expenses"));
    }

    /**
     * Show the Index Page
     * @Any("/view-report", as="reports.expenses.viewReport")
     */
    public function viewReport(Request $request) {
        //{"ch_detialed":"0","expense_id":"1","processes":["1","2"]}
        $expenseName = "";
        $allExpensesTotalPrice = 0;
        $allExpensesTotalPaid = 0;
        $allExpensesTotalRemaining = 0;

        $expenses = [];
        foreach ($request->selectedIds as $id) {
            $expense = Expenses::findOrFail($id);
            $expense->startDate = DateTime::parse($request->startDate)->startOfDay();
            $expense->endDate = DateTime::parse($request->endDate)->endOfDay();

            $expenses[$id]['expenseName'] = $expense->name;
            $expenses[$id]['expenseTotalPaid'] = $expense->getTotalPaid();

            $allExpensesTotalPaid += $expenses[$id]['expenseTotalPaid'];

            if ($request->ch_detialed == TRUE) {
                $index = 0;
                $expenses[$id]['expenseDetails'] = [];
                foreach ($expense->paidItems as $item) {
                    $expenses[$id]['expenseDetails'][$index]['desc'] = $item->recordDesc;
                    $expenses[$id]['expenseDetails'][$index]['paid'] = $item->withdrawValue;
                    $expenses[$id]['expenseDetails'][$index]['date'] = $item->due_date;
                    $index++;
                }
            }
        }

        session([
            'expenseName' => "",
            'expenses' => $expenses,
            'allExpensesTotalPaid' => $allExpensesTotalPaid,
        ]);
        if ($request->ch_detialed == FALSE) {
            return view("reports.expenses.total", compact('expenseName', 'expenses', 'allExpensesTotalPaid'));
        } else {
            return view("reports.expenses.detialed", compact('expenseName', 'expenses', 'allExpensesTotalPaid'));
        }
    }

    /**
     * Show the Index Page
     * @Get("/print-total-pdf", as="reports.expenses.printTotalPDF")
     */
    public function printTotalPDF(Request $request) {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('expenseName'), session('expenses'), session('allExpensesTotalPaid'));
    }

    /**
     * Show the Index Page
     * @Get("/print-detailed-pdf", as="reports.expenses.printDetailedPDF")
     */
    public function printDetailedPDF(Request $request) {
        return $this->exportPDF($request->ch_detialed, $request->withLetterHead, session('expenseName'), session('expenses'), session('allExpensesTotalPaid'));
    }

    private function exportPDF($ch_detialed, $withLetterHead, $expenseName, $expenses, $allExpensesTotalPaid) {
        if ($ch_detialed == FALSE) {
            $pdfReport = new expensesTotal($withLetterHead);
        } else {
            $pdfReport = new expensesDetailed($withLetterHead);
        }
        $pdfReport->expenseName = $expenseName;
        $pdfReport->expenses = $expenses;
        $pdfReport->allExpensesTotalPaid = $allExpensesTotalPaid;
        return $pdfReport->exportPDF();
    }

}
