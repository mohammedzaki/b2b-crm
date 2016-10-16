<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientProcessItem extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

    protected $fillable = [
    	'process_id',
		'description',
		'quantity',
		'unit_price'
    ];

    public function process()
    {
        return $this->belongsTo('App\ClientProcess');
    }
}
