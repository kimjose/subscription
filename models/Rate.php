<?php 
	/**
	 * 
	 */
	namespace models;
	require __DIR__."/../bootdb.php";

	use Illuminate\Database\Eloquent\Model;


	class Rate extends Model
	{
		public $table = 'rates';

		protected $fillable = ['productId', 'rate'];
	
	}

 ?>