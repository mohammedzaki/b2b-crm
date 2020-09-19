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
                                         'mode'                => '',
                                         'format'              => 'A4',
                                         'orientation'         => 'P',
                                         'margin_left'         => 8,
                                         'margin_right'        => 8,
                                         'margin_top'          => 8,
                                         'margin_bottom'       => 8,
                                         'setAutoTopMargin'    => 'stretch',
                                         'setAutoBottomMargin' => 'stretch',
                                         'autoMarginPadding'   => 2,
                                         //'margin_header'        => 10,
                                         'margin_footer'       => 5
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
        $arr = array_merge(['reportLayout' => $this->reportPreviewView], $this->getReportBaseData(), $this->getReportData());
        return view($this->reportView)->with($arr);
    }

    private function getReportBaseData()
    {
        return [
            'printRouteAction' => $this->printRouteAction,
            'showLetterHead'   => $this->withLetterHead ? 'on' : 'off'
        ];
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
        // $this->mpdf->SetHTMLHeaderByName('pageHeader');
        // LOAD a stylesheet
        $this->mpdf->WriteHTML($this->getCSS(), 1); // The parameter 1 tells that this is css/style only and no body/html/text
        $this->mpdf->WriteHTML($this->generatePDFHTML(), 2);
        $this->mpdf->SetHTMLHeaderByName('pageHeader');
        $this->mpdf->Output($this->reportName, 'I');
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
    }

    protected function getCSS()
    {
        if ($this->cssPath !== null) {
            $path = public_path($this->cssPath);
            return file_get_contents($path);
        } else {
            return '';
        }
    }

    protected function generatePDFHTML()
    {
        $arr = array_merge(['reportLayout' => $this->reportPDFView], $this->getReportBaseData(), $this->getReportData());
        return view($this->reportView)->with($arr)->render();
    }

}
