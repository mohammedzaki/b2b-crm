<?php

namespace App\Reports\Supplier;

use App\Reports\BaseReport;

class SupplierAnalyticalCenterDetailed extends BaseReport {

    public $supplierName,
            $proceses,
            $allProcessesTotalPrice,
            $allProcessTotalPaid,
            $allProcessTotalRemaining;
    
    protected $reportName = "SupplierTotalReport.pdf";

    function SetHtmlBody() {
        return '<!DOCTYPE html>
                <html>
                    <head>
                        
                    </head>
                    <body>
                        ' . $this->SetPageHeader() . '
                        ' . $this->SetPageFooter() . '
                        ' . $this->AddAllProcess() . '
                    </body>
                </html>';
    }

    function SetCSS() {
        $path = public_path('ReportsHtml/Supplier/SupplierDetailed.css');
        return file_get_contents($path);
    }

    function AddAllProcess() {
        $processesHtml = '';
        foreach ($this->proceses as $process) {
            $processesHtml .= $this->AddProcess($process);
        }
        return $processesHtml;
    }

    function AddProcess($supplier) {
        $processHtml = '<div class="clientProcess">';
        $processHtml .= $this->SetProcessHeader($supplier['supplierName'], $supplier['supplierNum']);
        $processHtml .= '   <table class="tg">';
        $processHtml .= $this->AddProcessItems($supplier['processDetails']);
        $processHtml .= $this->AddProcessItemsFooter($supplier['supplierTotalPrice'], $supplier['supplierTotalPaid'], $supplier['supplierTotalRemaining']);
        $processHtml .= '   </table>
                            <div class="lineBreak"></div>
                        </div>';
        return $processHtml;
    }

    function SetProcessHeader($supplierName, $supplierNum) {
        return '
                <div class="processHeader">
                    <table class="headerTable">
                        <tr>
                            <td class="noLabel">مسلسل :</td>
                            <td class="noValue" colspan="3">' . $supplierNum . '</td>
                            <td class="clientLabel">اسم المورد :</td>
                            <td class="clientName">' . $supplierName . '</td>
                        </tr>
                    </table>
                </div>';
    }

    function AddProcessItems($processDetails) {
        $items = '';
        $items .= $items . $this->AddProcessItemsHeader();
        foreach ($processDetails as $details) {
            $items .= $this->AddProcessItem($details['name'], $details['totalPrice'], $details['paid'], $details['remaining'], $details['date']);
        }
        return $items;
    }

    function AddProcessItemsHeader() {
        return '<tr>
                    <th>اسم العملية</th>
                    <th>الاجمالى</th>
                    <th>المدفوع</th>
                    <th>المتبقى</th>
                    <th>تاريـــــــــــخ</th>
                </tr>';
    }

    function AddProcessItem($name, $totalPrice, $paid, $remaining, $date) {
        return '<tr>
                    <td> ' . $name . ' </td>
                    <td> ' . $totalPrice . ' </td>
                    <td class="redColor" > ' . $paid . ' </td>
                    <td> ' . $remaining . ' </td>
                    <td> ' . $date . ' </td>
                </tr>
                ';
    }

    function AddProcessItemsFooter($total, $paid, $remaining) {
        return '<tr class="last">
                    <td class="redColor textLeft" colspan="2">' . $total . '</td>
                    <td class="redColor" colspan="1">' . $paid . '</td>
                    <td class="redColor " colspan="1">' . $remaining . '</td>
                    <td class="redColor" >الاجمالـــــــــــــــــــــــــــــــــــى</td>
                </tr>
                ';
    }
}
