<?php

namespace models;

require __DIR__ . "/../bootdb.php";

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
    public $table = 'users';

    protected $fillable = ['username', 'password', 'deleted'];

}
