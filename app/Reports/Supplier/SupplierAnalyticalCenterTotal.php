<?php

namespace App\Reports\Supplier;

use App\Reports\BaseReport;

class SupplierAnalyticalCenterTotal extends BaseReport {

    public $supplierName,
            $proceses,
            $allProcessesTotalPrice,
            $allProcessTotalPaid,
            $allProcessTotalRemaining;
    
    protected $reportName = "SupplierTotalReport.pdf";

    function SetHtmlBody() {
        return '<!DOCTYPE html>
                <html>
                    <head>
                    
                    </head>
                    <body>
                        ' . $this->SetPageHeader() . '
                        ' . $this->SetPageFooter() . '
                        ' . $this->SetReportHeader($this->supplierName) . '
                        <table class="tg">
                            ' . $this->AddProcess() . '
                        </table>
                    </body>
                </html>
                ';
    }
    
    function SetCSS() {
        $path = public_path('ReportsHtml/Supplier/SupplierTotal.css');
        return file_get_contents($path);
    }
    
    function SetReportHeader($supplierName) {
        return '<div class="processHeader">
                    <table class="headerTable">
                        <tr>
                            <td class="clientLabel">المركز التحليلى للعملاء</td>
                        </tr>
                    </table>
                </div>
                ';
    }
    
    function AddProcess() {
        $items = '';
        $items .= $items . $this->AddProcessItemHeader();
        foreach ($this->proceses as $process) {
            $items .= $this->AddProcessItem($process['processName'], $process['processTotalPrice'], $process['processTotalPaid'], $process['processTotalRemaining']);
        }
        $items .= $this->AddProcessItemFooter($this->allProcessesTotalPrice, $this->allProcessTotalPaid, $this->allProcessTotalRemaining);
        return $items;
    }
    
    function AddProcessItemHeader() {
        return '<tr>
                    <th>اسم المورد</th>
                    <th>الاجمالى</th>
                    <th>المدفوع</th>
                    <th>المتبقى</th>
                </tr>
                ';
    }

    function AddProcessItem($name, $total, $paid, $remaining) {
        return '<tr>
                    <td>' . $name . '</td>
                    <td>' . $total . '</td>
                    <td>' . $paid . '</td>
                    <td>' . $remaining . '</td>
                </tr>
                ';
    }
    
    function AddProcessItemFooter($total, $paid, $remaining) {
        return '<tr class="last">
                    <td class="redColor">الاجمالى</td>
                    <td class="redColor">' . $total . '</td>
                    <td class="redColor">' . $paid . '</td>
                    <td class="redColor">' . $remaining . '</td>
                </tr>
                ';
    }
}
