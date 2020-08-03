<?php 
	namespace models;
	require __DIR__."/../bootstrap.php";

	/**
	 * 
	 */

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;

	class Transaction extends Model
	{
		
		public $table = 'transactions';

		protected $fillable = ['TransactionType','TransID','TransTime','TransAmount','BusinessShortCode','BillRefNumber','InvoiceNumber',
		'OrgAccountBalance','ThirdPartyTransID','MSISDN','FirstName','MiddleName','LastName','status','active',];

		public $timestamps = false;

	}

 ?>