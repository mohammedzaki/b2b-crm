<?php

namespace App\Reports\Process;

use App\Helpers\Helpers;
use App\Models\Client;
use App\Models\ClientProcess;
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
            $totalProcessSuppliers,
            $totalManpowerHoursCost,
            $totalProcessExpenses,
            $totalCompanyExpenses;

    function setReportRefs() {
        $this->reportName  = 'CostCenter.pdf';
        $this->previewView = 'reports.process.cost-center.preview';
        $this->pdfView     = 'reports.process.cost-center.pdf';
        $this->cssPath     = 'ReportsHtml/Process/CostCenter.css';
    }

    function reportData() {
        $this->client = Client::findOrFail($this->clientId);
        foreach ($this->processIds as $processId) {
            $process                       = ClientProcess::findOrFail($processId);
            $this->processes[$process->id] = [
                'processExpenses'   => $this->getProcessExpences($process),
                'processName'       => $process->name,
                'processNum'        => $process->id,
                'processDate'       => $process->created_at,
                'processSuppliers'  => $this->getProcessSuppliers($process),
                'manpowerHoursCost' => $this->getManpowerHoursCost($process),
                'companyExpenses'   => $this->getCompanyExpenses($process)
            ];
        }
        $data = [
            'clinetName'     => $this->client->name,
            'processes'      => $this->processes,
            'showLetterHead' => $this->withLetterHead ? 'on' : 'off',
        ];
        return $data;
    }

    private function getProcessExpences(ClientProcess $process) {
        $processExpences = $process->expenses->map(function ($item, $key) {
            return [
                'total' => $item['withdrawValue'],
                'desc'  => $item['recordDesc'],
                'date'  => $item['due_date'],
            ];
        });
        return $processExpences;
    }

    private function getProcessSuppliers(ClientProcess $process) {
        $processSuppliers = $process->suppliers->map(function ($item, $key) {
            return [
                'name'  => $item->supplier->name,
                'total' => $item['total_price'],
                'desc'  => $item['name'],
                'date'  => $item['created_at'],
            ];
        });
        return $processSuppliers;
    }

    private function getCompanyExpenses(ClientProcess $process) {
        
    }

    private function getManpowerHoursCost(ClientProcess $process) {
        $manpowerCost = $process->manpowerCost->map(function ($item, $key) {
            return [
                'name'        => $item->employee->name,
                'totalHours'  => Helpers::hoursMinutsToString($item->working_hours_in_seconds),
                'totalDays'   => $item->totalDays,
                'hourRate'    => $item->employee->salaryPerHour(),
                'totalSalary' => round($item->employee->salaryPerSecond() * $item->working_hours_in_seconds, 2),
            ];
        });
        return $manpowerCost;
    }

}
