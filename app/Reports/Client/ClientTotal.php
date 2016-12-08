<?php

namespace App\Reports\Client;

class ClientTotal {

    public $clientName,
            $proceses,
            $allProcessesTotalPrice,
            $allProcessTotalPaid,
            $allProcessTotalRemaining;
    private $mpdf;
    private $reportName = "ClientTotalReport.pdf";

    public function __construct() {
        $this->mpdf = new \mPDF('', 'A4');
    }

    function SetHtmlBody() {
        return '<!DOCTYPE html>
                <html>
                    <head>

                    </head>
                    <body>
                        <h5 class="reportHeader">اسم العميل : <span>' . $this->clientName . '</span></h5>
                        <table class="tg">
                            ' . $this->AddProcess() . '
                        </table>
                    </body>
                </html>
                ';
    }
    
    function SetCSS() {
        $path = public_path('Reports/Client/ClientTotal.css');
        return file_get_contents($path);
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
                    <th class="tg-8fc1">اسم العملية</th>
                    <th class="tg-8fc1">الاجمالى</th>
                    <th class="tg-8fc1">المدفوع</th>
                    <th class="tg-8fc1">المتبقى</th>
                </tr>
                ';
    }

    function AddProcessItem($name, $total, $paid, $remaining) {
        return '<tr>
                    <td class="tg-h31u">' . $name . '</td>
                    <td class="tg-h31u">' . $total . '</td>
                    <td class="tg-h31u">' . $paid . '</td>
                    <td class="tg-h31u">' . $remaining . '</td>
                </tr>
                ';
    }
    
    function AddProcessItemFooter($total, $paid, $remaining) {
        return '<tr>
                    <td class="tg-c2s1">الاجمالى</td>
                    <td class="tg-c2s1">' . $total . '</td>
                    <td class="tg-c2s1">' . $paid . '</td>
                    <td class="tg-c2s1">' . $remaining . '</td>
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
        $this->mpdf->SetDirectionality('rtl');
        $this->mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
        // LOAD a stylesheet
        $this->mpdf->WriteHTML($this->SetCSS(), 1); // The parameter 1 tells that this is css/style only and no body/html/text

        $this->mpdf->WriteHTML($this->SetHtmlBody(), 2);

        $this->mpdf->Output($this->reportName, 'I');
    }

}
