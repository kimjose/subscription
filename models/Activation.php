<?php

	/**
	 * 
	 */
	namespace models;
	require __DIR__."/../bootdb.php";

	use Illuminate\Database\Eloquent\Model;

	/**
	 * 
	 */
	class Activation extends Model
	{
		
		public $table = 'activations';

		protected $fillable = ['transacId', 'days', 'expiresOn', 'clientName', 'rateId'];
	}
	