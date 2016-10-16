<?php

namespace App;

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
