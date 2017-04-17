<?php

namespace App\Reports\Invoice;

use App\Reports\BaseReport;
use App\Helpers\Helpers;

class Invoice extends BaseReport {

    public $clientName,
            $proceses,
            $allProcessesTotalPrice,
            $allProcessTotalPaid,
            $allProcessTotalRemaining;
    
    public $clinetName;
    public $invoiceItems;
    public $discountPrice;
    public $discountReason;
    public $sourceDiscountPrice;
    public $totalPrice;
    public $totalPriceAfterTaxes;
    public $totalTaxes;
    public $invoiceDate;
    public $invoiceNo;

    protected $reportName = "Invoice.pdf";
    //public function __construct($mode = '', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 16, $mgb = 16, $mgh = 9, $mgf = 9, $orientation = 'P')
    public function __construct($withLetterHead = true) {
        if ($withLetterHead) {
            $this->mpdf = new \mPDF('', 'A4', 0, '', 5, 5, 24, 8, 8, 8);
        } else {
            $this->mpdf = new \mPDF('', 'A4', '', 0, '', 5, 5, 8, 8, 10, 10);
        }
        $this->withLetterHead = $withLetterHead;
    }

    function SetHtmlBody() {
        $clinetName = $this->clinetName;
        $totalPriceAfterTaxes = $this->totalPriceAfterTaxes;
        $arabicPriceAfterTaxes = Helpers::numberToArabicWords($this->totalPriceAfterTaxes);
        $invoiceItems = $this->invoiceItems;
        $discountPrice = $this->discountPrice;
        $discountReason = $this->discountReason;
        $sourceDiscountPrice = $this->sourceDiscountPrice;
        $totalPrice = $this->totalPrice;
        $totalTaxes = $this->totalTaxes;
        $invoiceDate = $this->invoiceDate;
        $invoiceNo = $this->invoiceNo;
        $this->SetPageHeader();
        $this->SetPageFooter();
        return view('reports.invoice.invoice', compact(['clinetName', 'totalPriceAfterTaxes', 'arabicPriceAfterTaxes', 'invoiceItems', 'discountPrice', 'discountReason', 'sourceDiscountPrice', 'totalPrice', 'totalTaxes', 'invoiceDate', 'invoiceNo']))->render();
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
