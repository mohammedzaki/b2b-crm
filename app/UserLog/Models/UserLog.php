<?php

namespace App\UserLog\Models;

use App\Models\User;
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
class UserLog extends Model
{
    protected $table = 'user_logs';
    protected $fillable
                     = [
            'entity_id',
            'action_id',
            'row_id',
            'user_id',
            'log_data'
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entity()
    {
        return $this->belongsTo(EntityName::class);
    }

    public function action()
    {
        return $this->belongsTo(LogAction::class);
    }

    public function getLogData() {
        if ($this->action_id === LogAction::insert) {
            return '';
        } else {
            return $this->log_data;
        }
    }
}
