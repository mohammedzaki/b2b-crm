<?php

namespace App\Reports\V2\Process;

use App\Extensions\DateTime;
use App\Helpers\Helpers;
use App\Models\Client;
use App\Models\ClientProcess;
use App\Reports\V2\BaseReport;
use App\Reports\V2\IReport;

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

    public function __construct($withLetterHead = true, $clientId = null, $processIds = null)
    {
        parent::__construct($withLetterHead);
        $this->processIds = $processIds;
        $this->clientId   = $clientId;
    }

    public function setReportRefs()
    {
        $this->reportName       = 'Cost_Center_Report';
        $this->reportView       = 'reports.process.cost-center.preview';
        $this->cssPath          = 'reports/css/process/cost-center.css';
        $this->printRouteAction = 'reports.process.costCenter.printPDF';
    }

    public function getReportData($withUserLog = false)
    {
        $this->client = Client::findOrFail($this->clientId);
        $date         = DateTime::todayDateformat();
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
            'clientName'  => $this->client->name,
            'date'        => $date,
            'withUserLog' => $withUserLog,
            'processes'   => $this->processes
        ];
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
        return $processExpences;
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
        $manpowerCost = $process->manpowerCost
            ->each(function ($item) {
                $item->working_hours_in_seconds = $item->workingHoursToSeconds();
            })
            ->groupBy('employee_id')
            ->map(function ($gg) {
                return [
                    'name'       => $gg->first()->employee->name,
                    'totalHours' => Helpers::hoursMinutsToString($gg->sum('working_hours_in_seconds')),
                    'totalDays'  => $gg->count(),
                    'hourRate'   => $gg->first()->employee->salaryPerHour(),
                    'totalCost'  => round($gg->first()->employee->salaryPerSecond() * $gg->sum('working_hours_in_seconds'), Helpers::getDecimalPointCount()),
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
