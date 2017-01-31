<?php

namespace App\Reports\Employee;

use App\Reports\BaseReport;

class Salary extends BaseReport {

    public $employeeName;
    
    public $vars = array();
    public $htmlContent = "";

    public function __construct($withLetterHead = true) {
        if ($withLetterHead) {
            $this->mpdf = new \mPDF('', 'A4', '', '', 8, 8, 28, 10, 10, 10);
        } else {
            $this->mpdf = new \mPDF('', 'A4', '', '', 8, 8, 8, 10, 10, 10);
        }
        $this->reportName = "EmployeeSalaryReport.pdf";
        $this->withLetterHead = $withLetterHead;
    }

    function SetHtmlBody() {
        /*$vars = [
            "employeeName" => $this->employeeName
                ];*/
        $this->SetPageHeader();
        $this->SetPageFooter();
        return $this->htmlContent; //view('reports.employee.salary')->with($this->vars)->render();
    }

    function SetCSS() {
        $path = public_path('ReportsHtml/Empolyee/Salary.css');
        return file_get_contents($path);
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
