<?php

namespace App\Reports\Invoice;

use App\Reports\BaseReport;
use App\Helpers\Helpers;

class Invoice extends BaseReport {

    public $invoiceId,
            $clientName,
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

    public function __construct($withLetterHead = true) {
        if ($withLetterHead) {
            $this->mpdf = new \Mpdf\Mpdf([
                'mode'          => '',
                'format'        => 'A4',
                'orientation'   => 'P',
                'margin_left'   => 5,
                'margin_right'  => 5,
                'margin_top'    => 20,
                'margin_bottom' => 5,
                'margin_header' => 5,
                'margin_footer' => 5
            ]);
        } else {
            $this->mpdf = new \Mpdf\Mpdf([
                'mode'          => '',
                'format'        => 'A4',
                'orientation'   => 'P',
                'margin_left'   => 5,
                'margin_right'  => 5,
                'margin_top'    => 24,
                'margin_bottom' => 8,
                'margin_header' => 10,
                'margin_footer' => 10
            ]);
        }
        $this->withLetterHead = $withLetterHead;
    }

    function setHtmlBody() {
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
        $this->setPageHeader();
        $this->setPageFooter();
        $showLetterHead = $this->withLetterHead ? 'on' : 'off';
        return view('reports.invoice.invoice', compact(['clinetName', 'totalPriceAfterTaxes', 'arabicPriceAfterTaxes', 'invoiceItems', 'discountPrice', 'discountReason', 'sourceDiscountPrice', 'totalPrice', 'totalTaxes', 'invoiceDate', 'invoiceNo', 'showLetterHead']))->render();
    }

    function preview() {
        $invoiceId = $this->invoiceId;
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
        $this->setPageHeader();
        $this->setPageFooter();
        return view('reports.invoice.preview', compact(['invoiceId', 'clinetName', 'totalPriceAfterTaxes', 'arabicPriceAfterTaxes', 'invoiceItems', 'discountPrice', 'discountReason', 'sourceDiscountPrice', 'totalPrice', 'totalTaxes', 'invoiceDate', 'invoiceNo']));
    }

    function setCSS() {
        $path = public_path('ReportsHtml/Invoice/Invoice.css');
        return file_get_contents($path);
    }

    public function exportPDF() {
        parent::exportPDF();
        //$this->mpdf->SetMargins(.1, 11, 10);
    }

}
