<?php

namespace models;

require __DIR__ . "/../bootstrap.php";

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
    public $table = 'users';

    protected $fillable = ['username', 'password', 'deleted'];

}
