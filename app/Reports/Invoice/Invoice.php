<?php

namespace App\Reports\Invoice;

use App\Reports\BaseReport;

class Invoice extends BaseReport {

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
        $this->reportName = "SupplierDetailedReport.pdf";
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
        $path = public_path('ReportsHtml/Supplier/SupplierDetailed.css');
        return file_get_contents($path);
    }
}
