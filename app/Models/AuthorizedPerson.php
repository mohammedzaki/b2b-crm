<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
