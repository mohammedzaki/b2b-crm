<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TestTimer
 *
 * @property int $id
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestTimer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TestTimer whereName($value)
 * @mixin \Eloquent
 */
class TestTimer extends Model {
    public $timestamps = false;
    
    protected $fillable = [
        'name'
    ];

}
