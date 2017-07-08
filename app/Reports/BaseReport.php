<?php

namespace App\Reports;

use App\Http\Controllers\Controller;

abstract class BaseReport {

    protected $mpdf;
    protected $reportName = "BaseReport.pdf";
    public $withLetterHead;

    public function __construct($withLetterHead = true) {
        if ($withLetterHead) {
            $this->mpdf = new \mPDF('', 'A4', '', '', 8, 8, 28, 10, 10, 10);
        } else {
            $this->mpdf = new \mPDF('', 'A4', '', '', 8, 8, 8, 10, 10, 10);
        }
        $this->withLetterHead = $withLetterHead;
    }

    abstract function SetHtmlBody();

    abstract function SetCSS();

    function SetPageHeader() {
        if ($this->withLetterHead) {
            $path = public_path('ReportsHtml/letr.png');
            $this->mpdf->letrImg = file_get_contents($path);
            return '<!--mpdf
                    <htmlpageheader name="myheader">
                    <img src="var:letrImg" class="letrHead">
                    </htmlpageheader>

                    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
                    mpdf-->';
        } else {
            return '<!--mpdf
                    <htmlpageheader name="myheader">
                    </htmlpageheader>

                    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
                    mpdf-->';
        }
    }

    function SetPageFooter() {
        $path = public_path('ReportsHtml/footer.png');
        $this->mpdf->footerImg = file_get_contents($path);
        return '<!--mpdf
                <htmlpagefooter name="myfooter">
                <div class="reportPageFooterLine" ></div>
                <div class="reportPageFooterText">
                صفحة {PAGENO} من {nb}
                </div>
                </htmlpagefooter>

                <sethtmlpagefooter name="myfooter" value="on" />
                mpdf-->';
    }

    public function RenderReport() {
        $this->mpdf->autoScriptToLang = true;
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

    public function RenderTestReport() {
        $path = url('ReportsHtml/letr.png');
        $str = str_replace('var:letrImg', $path, $this->SetHtmlBody());
        $path = url('ReportsHtml/footer.png');
        $str = str_replace('var:footerImg', $path, $str);
        
        return $str;
    }

}
