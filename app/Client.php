<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->hasMany('App\ClientProcess');
    }
    
    public function unBilledProcesses() {
        return $this->hasMany('App\ClientProcess')->where('is_billed', ClientProcess::unBilled);
    }

    public function closedProcess() {
        //return $this->hasM //hasManyThrough($related, $through);
    }
    
    public static function allHasOpenProcess() {
        $clients = Client::join('client_processes', 'client_processes.client_id', '=', 'clients.id')
            ->select('clients.*')->where('client_processes.status', 'active')
            ->get();
        return $clients;
    }

}
