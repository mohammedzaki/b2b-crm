<?php

namespace App\Reports\Client;

class ClientDetailed {

    public $clientName,
            $proceses,
            $allProcessesTotalPrice,
            $allProcessTotalPaid,
            $allProcessTotalRemaining;
    private $mpdf;
    private $reportName = "ClientDetailedReport.pdf";

    public function __construct() {
        $this->mpdf = new \mPDF('', 'A4');
    }

    function SetHtmlBody() {
        return '<!DOCTYPE html>
                <html>
                    <head>
                        <link href="myReport_1.css" rel="stylesheet">
                    </head>
                    <body>
                        ' . $this->AddAllProcess() . '
                    </body>
                </html>';
    }

    function SetCSS() {
        $path = public_path('Reports/Client/ClientDetailed.css');
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
        $processHtml .= $this->SetProcessHeader($this->clientName, $process['processName'], $process['processDate']);
        $processHtml .= '       <table class="tg">';
        $processHtml .= $this->AddProcessItems($process['processDetails']);
        $processHtml .= $this->AddProcessItemsFooter($process['processTotalRemaining'], $process['processTotalPaid'], $process['processTotalPrice']);
        $processHtml .= '       </table>
                    <div class="lineBreak"></div>
                </div>';
        return $processHtml;
    }

    function SetProcessHeader($clientName, $processName, $processDate) {
        return '<br />
                <span class="reportHeader">اسم العميل : <span>' . $clientName . '</span></span>
                <span class="reportHeader">اسم العملية : <span>' . $processName . '</span></span>
                <span class="reportHeader">تاريخ : <span>' . $processDate . '</span></span>
                <br />
                <br />';
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
                    <th class="tg-8fc1">تاريخ</th>
                    <th class="tg-8fc1">المتبقى</th>
                    <th class="tg-8fc1">المدفوع</th>
                    <th class="tg-8fc1">الاجمالى</th>
                    <th class="tg-8fc1">سعر الوحدة</th>
                    <th class="tg-8fc1">القيمة</th>
                    <th class="tg-8fc1">بيان</th>
                </tr>
                ';
    }

    function AddProcessItem($date, $remaining, $paid, $totalPrice, $unitPrice, $quantity, $desc) {
        return '<tr>
                    <td class="tg-h31u"> ' . $date . ' </td>
                    <td class="tg-h31u"> ' . $remaining . ' </td>
                    <td class="tg-h31u"> ' . $paid . ' </td>
                    <td class="tg-h31u"> ' . $totalPrice . ' </td>
                    <td class="tg-h31u"> ' . $unitPrice . ' </td>
                    <td class="tg-h31u"> ' . $quantity . ' </td>
                    <td class="tg-h31u"> ' . $desc . ' </td>
                </tr>
                ';
    }

    function AddProcessItemsFooter($total, $paid, $remaining) {
        return '<tr>
                    <td class="tg-c2s1" colspan="2" style="text-align: left;">' . $remaining . '</td>
                    <td class="tg-c2s1">' . $paid . '</td>
                    <td class="tg-c2s1" colspan="3" style="text-align: right;">' . $total . '</td>
                    <td class="tg-c2s1">الاجمالى</td>
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
