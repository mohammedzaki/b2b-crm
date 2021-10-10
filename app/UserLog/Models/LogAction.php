<?php

namespace App\UserLog\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LogAction extends Model
{
    const insert   = 1;
    const update   = 2;
    const delete   = 3;
    const login    = 4;
    const view     = 5;
    const print    = 6;
    const restored = 7;

    public    $timestamps = false;
    protected $table      = 'log_actions';
    protected $fillable
                          = [
            'action_name',
            'display_name',
        ];
}
