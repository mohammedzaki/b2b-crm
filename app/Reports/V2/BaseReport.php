<?php

namespace App\Reports\V2;

use App\Extensions\DateTime;

abstract class BaseReport implements IReport
{

    public $withLetterHead;
    protected
           $mpdf,
           $reportName,
           $reportView,
           $reportPreviewView,
           $reportPDFView,
           $printRouteAction,
           $cssPath;

    public function __construct($withLetterHead = true)
    {
        $this->setReportRefs();
        $this->reportPreviewView = 'reports.preview';
        $this->reportPDFView = 'reports.pdf';
        $this->mpdf = new \Mpdf\Mpdf([
                                         'mode'                 => '',
                                         'format'               => 'A4',
                                         'orientation'          => 'P',
                                         'margin_left'          => 8,
                                         'margin_right'         => 8,
                                         'margin_top'           => $withLetterHead ? 28 : 10,
                                         'margin_bottom'        => 15,
                                         'margin_header'        => 10,
                                         'margin_footer'        => 5,
                                         'forcePortraitHeaders' => true
                                     ]);
        $this->withLetterHead = $withLetterHead;
    }

    public function getReportName()
    {
        $date = DateTime::todayDateformat();
        return "{$this->reportName}_{$date}.pdf";
    }

    public function preview()
    {
        return view($this->reportPreviewView)->with($this->getReportBaseData());
    }

    protected function setPageHeader()
    {
        $path = public_path('ReportsHtml/letr.jpg');
        $this->mpdf->imageVars['letrImg'] = file_get_contents($path);

    }

    protected function setPageFooter()
    {
        // $path = public_path('ReportsHtml/footer.jpg');
        // $this->mpdf->imageVars['footerImg'] = file_get_contents($path);
        $footer = "
        <table width=\"100%\">
            <tr>
                <td width=\"33%\">{DATE j-m-Y}</td>
                <td width=\"33%\" align=\"center\">{PAGENO}/{nbpg}</td>
                <td width=\"33%\" style=\"text-align: left; \">تقرير عميل مفصل</td>
            </tr>
        </table>";
        $this->mpdf->SetHTMLFooter($footer);
    }

    private function getReportBaseData()
    {
        return [
            'reportHTML'       => $this->getHtmlBody(),
            'printRouteAction' => $this->printRouteAction,
            'showLetterHead'   => $this->withLetterHead ? 'on' : 'off'
        ];
    }

    protected function getHtmlBody()
    {
        return view($this->reportView)->with($this->getReportData())->render();
    }

    public function exportPDF()
    {
        $this->mpdf->autoScriptToLang = true;
        $this->mpdf->autoVietnamese = true;
        $this->mpdf->autoArabic = true;
        $this->mpdf->autoLangToFont = true;
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->SetDirectionality('rtl');
        $this->mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
        $this->setPageHeader();
        $this->setPageFooter();
        // LOAD a stylesheet
        $this->mpdf->WriteHTML($this->getCSS(), 1); // The parameter 1 tells that this is css/style only and no body/html/text
        $this->mpdf->WriteHTML($this->generatePDFHTML(), 2);
        $this->mpdf->Output($this->reportName, 'I');
    }

    protected function getCSS()
    {
        $path = public_path($this->cssPath);
        return file_get_contents($path);
    }

    protected function generatePDFHTML()
    {
        return view($this->reportPDFView)->with($this->getReportBaseData());
    }

}
