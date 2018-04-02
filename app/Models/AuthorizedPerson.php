<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AuthorizedPerson
 *
 * @property int $id
 * @property int $client_id
 * @property string|null $name
 * @property string|null $jobtitle
 * @property string|null $telephone
 * @property string|null $email
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthorizedPerson whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthorizedPerson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthorizedPerson whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthorizedPerson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthorizedPerson whereJobtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthorizedPerson whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthorizedPerson whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuthorizedPerson whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AuthorizedPerson extends Model
{
    protected $fillable = [
        'name', 
        'jobtitle', 
        'telephone', 
        'email',
        'client_id'
    ];
}
