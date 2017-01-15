<?php

namespace App\Reports\Client;

use App\Reports\BaseReport;

class ClientTotal extends BaseReport {

    public $clientName,
            $proceses,
            $allProcessesTotalPrice,
            $allProcessTotalPaid,
            $allProcessTotalRemaining;
    private $mpdf;
    private $reportName = "ClientTotalReport.pdf";
    private $withLetterHead;

    public function __construct($withLetterHead = true) {
        if ($withLetterHead) {
            $this->mpdf = new \mPDF('', 'A4', '', '', 8, 8, 28, 10, 10, 10);
        } else {
            $this->mpdf = new \mPDF('', 'A4', '', '', 8, 8, 8, 10, 10, 10);
        }
        $this->withLetterHead = $withLetterHead;
    }

    function SetHtmlBody() {
        return '<!DOCTYPE html>
                <html>
                    <head>
                    
                    </head>
                    <body>
                        ' . $this->SetPageHeader() . '
                        ' . $this->SetPageFooter() . '
                        ' . $this->SetReportHeader($this->clientName) . '
                        <table class="tg">
                            ' . $this->AddProcess() . '
                        </table>
                    </body>
                </html>
                ';
    }
    
    function SetCSS() {
        $path = public_path('ReportsHtml/Client/ClientTotal.css');
        return file_get_contents($path);
    }
    
    function SetReportHeader($clientName) {
        return '<div class="processHeader">
                    <table class="headerTable">
                        <tr>
                            <td class="clientLabel">اسم العميل :</td>
                            <td class="clientName">' . $clientName . '</td>
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
                    <th>اسم العملية</th>
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

    public function RenderReport() {
        $this->mpdf->autoScriptToLang = true;
        //$mpdf->baseScript = 1;	// Use values in classes/ucdn.php  1 = LATIN
        $this->mpdf->autoVietnamese = true;
        $this->mpdf->autoArabic = true;

        $this->mpdf->autoLangToFont = true;
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->setAutoTopMargin = 'margin';
        $this->mpdf->SetDirectionality('rtl');
        $this->mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
        // LOAD a stylesheet
        $this->mpdf->WriteHTML($this->SetCSS(), 1); // The parameter 1 tells that this is css/style only and no body/html/text

        $this->mpdf->WriteHTML($this->SetHtmlBody(), 2);

        $this->mpdf->Output($this->reportName, 'I');
    }

}
