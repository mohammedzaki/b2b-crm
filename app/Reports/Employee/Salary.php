<?php

namespace App\Reports\Employee;

use App\Reports\BaseReport;

class Salary extends BaseReport {

    public $employeeName;
    
    public $htmlContent = "";
    protected $reportName = "EmployeeSalaryReport.pdf";

    function SetHtmlBody() {
        $this->employeeName = "Test Test";
        $employeeName = $this->employeeName;
        $this->SetPageHeader();
        $this->SetPageFooter();
        //TODO: update this line of code to new structure 
        //return view('reports.employee.test', compact(['employeeName']))->render();
        return $this->htmlContent;
    }

    function SetCSS() {
        $path = public_path('ReportsHtml/Empolyee/Salary.css');
        return file_get_contents($path);
    }
    
    public function RenderReport() {
        parent::RenderReport();
        $this->mpdf->SetMargins(.1, 11, 10);
    }
}
