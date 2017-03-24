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
        return $this->hasMany('App\Models\ClientProcess');
    }

    public function unInvoiceProcesses() {
        return $this->hasMany('App\Models\ClientProcess')->where([
                    ['invoice_billed', "=", ClientProcess::invoiceUnBilled],
                    ['require_invoice', "=", TRUE],
        ]);
    }

    public function closedProcess() {
        return $this->hasMany(ClientProcess::class)->where('status', ClientProcess::statusClosed);
    }

    public function openProcess() {
        return $this->hasMany(ClientProcess::class)->where('status', ClientProcess::statusOpened);
    }

    public static function allHasOpenProcess() {
        $clients = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->select('clients.*')->where('client_processes.status', ClientProcess::statusOpened)
                ->get();
        return $clients;
    }

    public function getTotalPaid() {
        /*
         * 
         */
        /*return DB::table('clients')
                ->join('client_processes', 'client_processes.client_id', '=', 'clients.id')
                ->join('deposit_withdraws', 'client_processes.id', '=', 'deposit_withdraws.cbo_processes')
                ->on('clients.id', 'deposit_withdraws.client_id')
                ->where('client_processes.status', ClientProcess::statusOpened)
                ->where('clients.id', $this->id)
                ->sum('depositValue');*/
        return DB::select('SELECT 
          sum(deposit_withdraws.depositValue) as depositValue
          FROM clients
          join client_processes on client_processes.client_id = clients.id
          join deposit_withdraws on client_processes.id = deposit_withdraws.cbo_processes and clients.id = deposit_withdraws.client_id
          WHERE `status` = "active" and clients.id = ' . $this->id)[0]->depositValue;
        
        //return $this->hasMany(DepositWithdraw::class)->where([['depositValue', '>', 0]])->sum('depositValue');
    }

    public function getTotalRemaining() {
        return $this->getTotalDeal() - $this->getTotalPaid();
    }

    public function getTotalDeal() {
        return $this->openProcess()->sum('total_price_taxes');
    }

}
