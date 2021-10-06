<?php

namespace App\Reports\Process;

use App\Helpers\Helpers;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Reports\BaseReport;
use App\Reports\IReport;
use Debugbar;

class CostCenter extends BaseReport implements IReport
{

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

    public function setReportRefs()
    {
        $this->reportName  = 'CostCenter.pdf';
        $this->previewView = 'reports.process.cost-center.preview';
        $this->pdfView     = 'reports.process.cost-center.pdf';
        $this->cssPath     = 'ReportsHtml/Process/CostCenter.css';
    }

    public function reportData()
    {
        $this->client = Client::findOrFail($this->clientId);
        foreach ($this->processIds as $processId) {
            $process                       = ClientProcess::findOrFail($processId);
            $this->processes[$process->id] = [
                'processName'            => $process->name,
                'processNum'             => $process->id,
                'processDate'            => $process->created_at,
                'processExpenses'        => $this->getProcessExpences($process),
                'processSuppliers'       => $this->getProcessSuppliers($process),
                'manpowerHoursCost'      => $this->getManpowerHoursCost($process),
                'companyExpenses'        => $this->getCompanyExpenses($process),
                'totalProcessSuppliers'  => $this->totalProcessSuppliers,
                'totalManpowerHoursCost' => $this->totalManpowerHoursCost,
                'totalProcessExpenses'   => $this->totalProcessExpenses,
                'totalCompanyExpenses'   => $this->totalCompanyExpenses,
                'totalProcessCost'       => $this->getTotalProcessCost(),
            ];
            $this->resetTotals();
        }
        $data = [
            'clientName'     => $this->client->name,
            'processes'      => $this->processes,
            'showLetterHead' => $this->withLetterHead ? 'on' : 'off',
        ];
        Debugbar::info($data);
        return $data;
    }

    private function getProcessExpences(ClientProcess $process)
    {
        $processExpences            = $process->expenses()->map(function ($item, $key) {
            return [
                'pending'   => $item['pendingStatus'],
                'desc'      => $item['recordDesc'],
                'date'      => $item['due_date'],
                'totalCost' => $item['withdrawValue'],
            ];
        });
        $this->totalProcessExpenses = $processExpences->sum('totalCost');
        return $processExpences->toArray();
    }

    private function getProcessSuppliers(ClientProcess $process)
    {
        $processSuppliers            = $process->suppliers->map(function ($item, $key) {
            return [
                'name'      => $item->supplier->name,
                'desc'      => $item['name'],
                'date'      => $item['created_at'],
                'totalCost' => $item['total_price'],
            ];
        });
        $this->totalProcessSuppliers = $processSuppliers->sum('totalCost');
        return $processSuppliers;
    }

    private function getManpowerHoursCost(ClientProcess $process)
    {
        $manpowerCost                 = $process->manpowerCost->map(function ($item, $key) {
            return [
                'name'       => $item->employee->name,
                'totalHours' => Helpers::hoursMinutsToString($item->working_hours_in_seconds),
                'totalDays'  => $item->totalDays,
                'hourRate'   => $item->employee->salaryPerHour(),
                'totalCost'  => round($item->employee->salaryPerSecond() * $item->working_hours_in_seconds, Helpers::getDecimalPointCount()),
            ];
        });
        $this->totalManpowerHoursCost = $manpowerCost->sum('totalCost');
        return $manpowerCost;
    }

    private function getCompanyExpenses(ClientProcess $process)
    {
        $companyExpenses            = collect([
                                                  [
                                                      "name"      => "كهرباء",
                                                      "totalDays" => "2",
                                                      "totalCost" => 20.45],
                                                  [
                                                      "name"      => "sample",
                                                      "totalDays" => "3",
                                                      "totalCost" => 20.45],
                                                  [
                                                      "name"      => "مثال",
                                                      "totalDays" => "2",
                                                      "totalCost" => 20.45]
                                              ]);
        $this->totalCompanyExpenses = $companyExpenses->sum('totalCost');
        return $companyExpenses;
    }

    private function getTotalProcessCost()
    {
        return ($this->totalProcessSuppliers + $this->totalManpowerHoursCost + $this->totalProcessExpenses + $this->totalCompanyExpenses);
    }

    private function resetTotals()
    {
        $this->totalProcessSuppliers  = 0;
        $this->totalManpowerHoursCost = 0;
        $this->totalProcessExpenses   = 0;
        $this->totalCompanyExpenses   = 0;
    }

}
