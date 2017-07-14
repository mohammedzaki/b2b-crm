<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Client extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'address',
        'telephone',
        'mobile',
        'referral_id',
        'referral_percentage',
        'credit_limit',
        'is_client_company'
    ];

    public function processes() {
        return $this->hasMany(ClientProcess::class);
    }

    public function unInvoiceProcesses() {
        return $this->hasMany(ClientProcess::class)->where([
                    ['invoice_billed', "=", ClientProcess::invoiceUnBilled],
                    ['require_invoice', "=", TRUE],
        ]);
    }

    public function invoiceProcesses() {
        return $this->hasMany(ClientProcess::class)->where([
                    //['invoice_billed', "=", ClientProcess::invoiceUnBilled],
                    ['require_invoice', "=", TRUE],
        ]);
    }

    public function closedProcess() {
        return $this->hasMany(ClientProcess::class)->where('status', ClientProcess::statusClosed);
    }

    public function openProcess() {
        return $this->hasMany(ClientProcess::class)->where('status', ClientProcess::statusOpened);
    }

    public function hasOpenProcess() {
        $client = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where([
                    ['client_processes.status', "=", ClientProcess::statusOpened],
                    ['clients.id', '=', $this->id]])
                ->distinct()
                ->get();
        
        if ($client->count() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function hasClosedProcess() {
        $client = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where([
                    ['client_processes.status', "=", ClientProcess::statusClosed],
                    ['clients.id', '=', $this->id]])
                ->distinct()
                ->get();
        
        if ($client->count() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public static function allHasOpenProcess() {
        $clients = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where('client_processes.status', ClientProcess::statusOpened)
                ->distinct()
                ->get();
        return $clients;
    }

    public static function allHasUnBilledInvoiceProcess() {
        $clients = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where([
                    //['client_processes.status', ClientProcess::statusOpened],
                    ['client_processes.invoice_billed', "=", ClientProcess::invoiceUnBilled],
                    ['client_processes.require_invoice', "=", TRUE],
                ])
                ->distinct()
                ->get();
        return $clients;
    }

    public static function allHasInvoiceProcess() {
        $clients = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where([
                    //['client_processes.status', ClientProcess::statusOpened],
                    //['client_processes.invoice_billed', "=", ClientProcess::invoiceUnBilled],
                    ['client_processes.require_invoice', "=", TRUE],
                ])
                ->distinct()
                ->get();
        return $clients;
    }
    
    public function getTotalPaid() {
        return DB::table('clients')
                        ->join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                        ->join('deposit_withdraws', 'client_processes.id', '=', 'deposit_withdraws.cbo_processes')
                        ->join('deposit_withdraws as dw', 'clients.id', '=', 'deposit_withdraws.client_id')
                        ->distinct()
                        ->where('client_processes.status', ClientProcess::statusOpened)
                        ->where('clients.id', $this->id)
                        ->sum('deposit_withdraws.depositValue');
    }

    public function getTotalRemaining() {
        return $this->getTotalDeal() - $this->getTotalPaid();
    }

    public function getTotalDeal() {
        return $this->openProcess()->sum('total_price_taxes');
    }

}
