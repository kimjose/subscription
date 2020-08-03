<?php
    namespace models;

    require __DIR__ . "/../bootstrap.php";

    use Illuminate\Database\Eloquent\Model;

    class Client extends Model
    {
        public $table = 'clients';

        protected $fillable = ['name', 'email', 'phoneNumber', 'location', 'businessDescription'];
    }
    