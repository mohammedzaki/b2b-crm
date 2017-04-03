<?php

namespace App\Reports\Invoice;

use App\Reports\BaseReport;

class Invoice extends BaseReport {

    public $clientName,
            $proceses,
            $allProcessesTotalPrice,
            $allProcessTotalPaid,
            $allProcessTotalRemaining;
    
    public $employeeName;
    
    protected $reportName = "Invoice.pdf";
    //public function __construct($mode = '', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 16, $mgb = 16, $mgh = 9, $mgf = 9, $orientation = 'P')
    public function __construct($withLetterHead = true) {
        if ($withLetterHead) {
            $this->mpdf = new \mPDF('', 'A4', 0, '', 8, 8, 28, 8, 10, 10);
        } else {
            $this->mpdf = new \mPDF('', 'A4', '', 0, '', 8, 8, 8, 8, 10, 10);
        }
        $this->withLetterHead = $withLetterHead;
    }

    function SetHtmlBody() {
        $this->employeeName = "Test Test";
        $employeeName = $this->employeeName;
        $this->SetPageHeader();
        $this->SetPageFooter();
        //TODO: update this line of code to new structure 
        return view('reports.invoice.invoice', compact(['employeeName']))->render();
    }

    function SetCSS() {
        $path = public_path('ReportsHtml/Invoice/Invoice.css');
        return file_get_contents($path);
    }
    
    public function RenderReport() {
        parent::RenderReport();
        //$this->mpdf->SetMargins(.1, 11, 10);
    }
}
