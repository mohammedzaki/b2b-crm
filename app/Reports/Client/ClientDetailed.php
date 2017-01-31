<?php

namespace App\Reports\Client;

use App\Reports\BaseReport;

class ClientDetailed extends BaseReport {

    public $clientName,
            $proceses,
            $allProcessesTotalPrice,
            $allProcessTotalPaid,
            $allProcessTotalRemaining;

    public function __construct($withLetterHead = true) {
        if ($withLetterHead) {
            $this->mpdf = new \mPDF('', 'A4', '', '', 8, 8, 28, 10, 10, 10);
        } else {
            $this->mpdf = new \mPDF('', 'A4', '', '', 8, 8, 8, 10, 10, 10);
        }
        $this->reportName = "ClientDetailedReport.pdf";
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
                        ' . $this->AddAllProcess() . '
                    </body>
                </html>';
    }

    function SetCSS() {
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
        $this->mpdf->SetMargins(.1, 11, 10);

        $this->mpdf->Output($this->reportName, 'I');
    }

}
