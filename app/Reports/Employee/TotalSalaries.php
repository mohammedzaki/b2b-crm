<?php

namespace App\Reports\Employee;

use App\Reports\BaseReport;

class TotalSalaries extends BaseReport {

    public $employeeName;
    
    public $htmlContent = "";
    protected $reportName = "EmployeeSalaryReport.pdf";

    function setHtmlBody() {
        $this->employeeName = "Test Test";
        $employeeName = $this->employeeName;
        $this->setPageHeader();
        $this->setPageFooter();
        //TODO: update this line of code to new structure 
        //return view('reports.employee.test', compact(['employeeName']))->render();
        return $this->htmlContent;
    }

    function setCSS() {
        $path = public_path('ReportsHtml/Empolyee/Salary.css');
        return file_get_contents($path);
    }
    
    public function exportPDF() {
        parent::exportPDF();
        $this->mpdf->SetMargins(.1, 11, 10);
    }
}
