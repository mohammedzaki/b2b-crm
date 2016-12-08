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

    public function closedProcess() {
        return $this->hasManyThrough($related, $through);
    }

}
