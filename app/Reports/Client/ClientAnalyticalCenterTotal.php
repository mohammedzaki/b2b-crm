<?php

namespace App\Reports\Client;

use App\Reports\BaseReport;

class ClientAnalyticalCenterTotal extends BaseReport {

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
                        ' . $this->SetReportHeader($this->clientName) . '
                        <table class="tg">
                            ' . $this->AddProcess() . '
                        </table>
                    </body>
                </html>
                ';
    }
    
    function setCSS() {
        $path = public_path('ReportsHtml/Client/ClientTotal.css');
        return file_get_contents($path);
    }
    
    function SetReportHeader($clientName) {
        return '<div class="processHeader">
                    <table class="headerTable">
                        <tr>
                            <td class="clientLabel">المركز التحليلى للعملاء</td>
                        </tr>
                    </table>
                </div>
                ';
    }
    
    function AddProcess() {
        $items = '';
        $items .= $items . $this->AddProcessItemHeader();
        foreach ($this->proceses as $process) {
            $items .= $this->AddProcessItem($process['processName'], $process['processTotalPrice'], $process['processTotalPaid'], $process['processTotalRemaining']);
        }
        $items .= $this->AddProcessItemFooter($this->allProcessesTotalPrice, $this->allProcessTotalPaid, $this->allProcessTotalRemaining);
        return $items;
    }
    
    function AddProcessItemHeader() {
        return '<tr>
                    <th>اسم العميل</th>
                    <th>الاجمالى</th>
                    <th>المدفوع</th>
                    <th>المتبقى</th>
                </tr>
                ';
    }

    function AddProcessItem($name, $total, $paid, $remaining) {
        return '<tr>
                    <td>' . $name . '</td>
                    <td>' . $total . '</td>
                    <td>' . $paid . '</td>
                    <td>' . $remaining . '</td>
                </tr>
                ';
    }
    
    function AddProcessItemFooter($total, $paid, $remaining) {
        return '<tr class="last">
                    <td class="redColor">الاجمالى</td>
                    <td class="redColor">' . $total . '</td>
                    <td class="redColor">' . $paid . '</td>
                    <td class="redColor">' . $remaining . '</td>
                </tr>
                ';
    }
}
