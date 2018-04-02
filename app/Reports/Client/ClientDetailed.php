<?php

namespace App\Reports\Client;

use App\Reports\BaseReport;

class ClientDetailed extends BaseReport {

    public $clientName,
            $proceses,
            $allProcessesTotalPrice,
            $allProcessTotalPaid,
            $allProcessTotalRemaining;
    
    protected $reportName = "ClientTotalReport.pdf";

    function setHtmlBody() {
        return '<!DOCTYPE html>
                <html>
                    <head>
                        
                    </head>
                    <body>
                        ' . $this->setPageHeader() . '
                        ' . $this->setPageFooter() . '
                        ' . $this->AddAllProcess() . '
                    </body>
                </html>';
    }

    function setCSS() {
        $path = public_path('ReportsHtml/Client/ClientDetailed.css');
        return file_get_contents($path);
    }

    function AddAllProcess() {
        $processesHtml = '';
        foreach ($this->proceses as $process) {
            $processesHtml .= $this->AddProcess($process);
        }
        return $processesHtml;
    }

    function AddProcess($process) {
        $processHtml = '<div class="clientProcess">';
        $processHtml .= $this->SetProcessHeader($this->clientName, $process['processName'], $process['processDate'], $process['processNum']);
        $processHtml .= '   <table class="tg">';
        $processHtml .= $this->AddProcessItems($process['processDetails']);
        $processHtml .= $this->AddProcessItemsFooter($process['processTotalPrice'], $process['processTotalPaid'], $process['processTotalRemaining']);
        $processHtml .= '   </table>
                            <div class="lineBreak"></div>
                        </div>';
        return $processHtml;
    }

    function SetProcessHeader($clientName, $processName, $processDate, $processNum) {
        return '
                <div class="processHeader">
                    <table class="headerTable">
                        <tr>
                            <td class="noLabel">مسلسل :</td>
                            <td class="noValue" colspan="3">' . $processNum . '</td>
                            <td class="clientLabel">اسم العميل :</td>
                            <td class="clientName">' . $clientName . '</td>
                            <td class="processLabel">اسم العملية :</td> 
                            <td class="processName">' . $processName . '</td>
                            <td class="dateLabel">تاريخ</td>
                            <td class="dateValue">' . $processDate . '</td>
                        </tr>
                    </table>
                </div>';
    }

    function AddProcessItems($processDetails) {
        $items = '';
        $items .= $items . $this->AddProcessItemsHeader();
        foreach ($processDetails as $details) {
            $items .= $this->AddProcessItem($details['date'], $details['remaining'], $details['paid'], $details['totalPrice'], $details['unitPrice'], $details['quantity'], $details['desc']);
        }
        return $items;
    }

    function AddProcessItemsHeader() {
        return '<tr>
                    <th>تاريـــــــــــخ</th>
                    <th>المتبقى</th>
                    <th>المدفوع</th>
                    <th>الاجمالى</th>
                    <th>سعر الوحدة</th>
                    <th>الكمية</th>
                    <th>بيـــــــان</th>
                </tr>';
    }

    function AddProcessItem($date, $remaining, $paid, $totalPrice, $unitPrice, $quantity, $desc) {
        return '<tr>
                    <td> ' . $date . ' </td>
                    <td> ' . $remaining . ' </td>
                    <td class="redColor" > ' . $paid . ' </td>
                    <td> ' . $totalPrice . ' </td>
                    <td> ' . $unitPrice . ' </td>
                    <td> ' . $quantity . ' </td>
                    <td> ' . $desc . ' </td>
                </tr>
                ';
    }

    function AddProcessItemsFooter($total, $paid, $remaining) {
        return '<tr class="last">
                    <td class="redColor textLeft" colspan="2">' . $remaining . '</td>
                    <td class="redColor" >' . $paid . '</td>
                    <td class="redColor textRight" colspan="3">' . $total . '</td>
                    <td class="redColor" >الاجمالـــــــــــــــــــــــــــــــــــى</td>
                </tr>
                ';
    }
}
