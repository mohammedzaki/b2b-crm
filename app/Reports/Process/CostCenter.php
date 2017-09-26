<?php

namespace App\Reports\Process;

use App\Models\Client;
use App\Reports\BaseReport;
use App\Reports\IReport;
use Debugbar;

class CostCenter extends BaseReport implements IReport {

    public
            $clientId,
            $processIds;
    private
            $client,
            $processes,
            $processExpenses,
            $suppliers,
            $employees,
            $companyExpenses;

    function setReportRefs() {
        $this->reportName  = 'CostCenter.pdf';
        $this->previewView = 'reports.process.cost-center.preview';
        $this->pdfView     = 'reports.process.cost-center.pdf';
        $this->cssPath     = 'ReportsHtml/Process/CostCenter.css';
    }

    function reportData() {
        $this->client = Client::findOrFail($this->clientId);
        foreach ($this->processIds as $processId) {
            $process         = \App\Models\ClientProcess::findOrFail($processId);
            $processExpenses = $process->expenses->map(function ($item, $key) {
                return [
                    'total' => $item['withdrawValue'],
                    'desc'  => $item['recordDesc'],
                    'date'  => $item['due_date'],
                ];
            });
            $suppliers = $process->suppliers->map(function ($item, $key) {
                return [
                    'name'  => $item->supplier->name,
                    'total' => $item['total_price'],
                    'desc'  => $item['name'],
                    'date'  => $item['created_at'],
                ];
            });
            //Debugbar::info($processExpenses);
            //Debugbar::info($process->expenses);
            $this->processes[$process->id] = [
                'processExpenses' => $processExpenses,
                'processName'     => $process->name,
                'processNum'      => $process->id,
                'processDate'     => $process->created_at,
                'suppliers'       => $suppliers,
                    //'employees'       => $this->employees,
                    //'companyExpenses' => $this->companyExpenses
            ];
        }
        $data = [
            'clinetName'     => $this->client->name,
            'processes'      => $this->processes,
            'showLetterHead' => $this->withLetterHead ? 'on' : 'off',
        ];
        return $data;
    }

}
