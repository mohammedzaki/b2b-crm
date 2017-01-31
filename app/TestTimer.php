<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestTimer extends Model {
    public $timestamps = false;
    
    protected $fillable = [
        'name'
    ];

}
