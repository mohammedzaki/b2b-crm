<?php

namespace App\Reports\Client;

use App\Reports\BaseReport;

class ClientAnalyticalCenterDetailed extends BaseReport {

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

    function AddProcess($client) {
        $processHtml = '<div class="clientProcess">';
        $processHtml .= $this->SetProcessHeader($client['clientName'], $client['clientNum']);
        $processHtml .= '   <table class="tg">';
        $processHtml .= $this->AddProcessItems($client['processDetails']);
        $processHtml .= $this->AddProcessItemsFooter($client['clientTotalPrice'], $client['clientTotalPaid'], $client['clientTotalRemaining']);
        $processHtml .= '   </table>
                            <div class="lineBreak"></div>
                        </div>';
        return $processHtml;
    }

    function SetProcessHeader($clientName, $clientNum) {
        return '
                <div class="processHeader">
                    <table class="headerTable">
                        <tr>
                            <td class="noLabel">مسلسل :</td>
                            <td class="noValue" colspan="3">' . $clientNum . '</td>
                            <td class="clientLabel">اسم العميل :</td>
                            <td class="clientName">' . $clientName . '</td>
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
