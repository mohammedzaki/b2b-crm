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
        if ($withLetterHead) {
            $this->mpdf = new \Mpdf\Mpdf([
                                             'mode'          => '',
                                             'format'        => 'A4',
                                             'orientation'   => 'P',
                                             'margin_left'   => 8,
                                             'margin_right'  => 8,
                                             'margin_top'    => 28,
                                             'margin_bottom' => 10,
                                             'margin_header' => 10,
                                             'margin_footer' => 10
                                         ]);
        } else {
            $this->mpdf = new \Mpdf\Mpdf([
                                             'mode'          => '',
                                             'format'        => 'A4',
                                             'orientation'   => 'P',
                                             'margin_left'   => 8,
                                             'margin_right'  => 8,
                                             'margin_top'    => 8,
                                             'margin_bottom' => 10,
                                             'margin_header' => 10,
                                             'margin_footer' => 10
                                         ]);
        }
        $this->withLetterHead = $withLetterHead;
    }

    public function getReportName()
    {
        $date = DateTime::todayDateformat();
        return "{$this->reportName}_{$date}.pdf";
    }

    public function preview()
    {
        $this->setPageHeader();
        $this->setPageFooter();
        return view($this->reportPreviewView)->with(['reportHTML' => $this->getHtmlBody(), 'printRouteAction' => $this->printRouteAction]);
    }

    protected function setPageHeader()
    {
        $path = public_path('ReportsHtml/letr.jpg');
        $this->mpdf->imageVars['letrImg'] = file_get_contents($path);

    }

    protected function setPageFooter()
    {
        $path = public_path('ReportsHtml/footer.jpg');
        $this->mpdf->imageVars['footerImg'] = file_get_contents($path);
    }

    protected function getHtmlBody()
    {
        $this->setPageHeader();
        $this->setPageFooter();
        return view($this->reportView)->with($this->getReportData())->render();
    }

    protected function generatePDFHTML()
    {
        return view($this->reportPDFView)->with(['reportHTML' => $this->getHtmlBody(), 'printRouteAction' => $this->printRouteAction]);
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

}
