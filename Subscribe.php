<?php 

	/**
	 * 
	 */
	require "models/Rate.php";
	require "models/Activation.php";
	require "models/Transaction.php";
	use models\Rate as Rater;
	use models\Transaction;
	use models\Activation;

	class Subscribe
	{
		protected $access_token;
		function __construct($consumerKey, $consumerSecret)
		{
			$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			$credentials = base64_encode($consumerKey.':'.$consumerSecret);
			$headers = ['Content-Type:application/json: charset=utf8'];
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization:Basic '.$credentials));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			//curl_setopt($curl, CURLOPT_USERPWD, 'LfHOyC6ysjhhZn6A6SgzG6gjEH2VvVfc:ohp94CE0zbe1I07V');
			//curl_setopt($curl, CURLOPT_HEADER, false);


			$curl_response = curl_exec($curl);
			$decode = json_decode($curl_response);
			$this->access_token = $decode->access_token;
		}

		public function registerUrl(){
			$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
			$curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$this->access_token));
			$post_data = array(
				'ShortCode'=>'600158',
				'ResponseType'=>'Cancelled',
				'ConfirmationURL'=>'https://infinitops.co.ke/subscribe/confirmation/',
				'ValidationURL'=>'https://infinitops.co.ke/subscribe/validation/',
			);
			$encoded_data = json_encode($post_data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded_data);
			$curl_response = curl_exec($curl);
			echo $curl_response;
		}

		public function simulateC2B($phone, $amount, $refNo){
			$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
  
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->access_token)); //setting custom header
		  
		  //'Msisdn' => 254708374149,
		    $curl_post_data = array(
		            //Fill in the request parameters with valid values
		           'ShortCode' => '600158',
		           'CommandID' => 'CustomerPayBillOnline',
		           'Amount' => $amount,
		           'Msisdn' => $phone,
		           'BillRefNumber' => $refNo
		    );
		  
		    $data_string = json_encode($curl_post_data);
		  
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_POST, true);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		  
		    $curl_response = curl_exec($curl);
		    print_r($curl_response);
	  
		}

		public function lipaNaMpesa($amount, $phoneNo){
			$timeStamp = date('Ymdhis');
		    $BusinessShortCode = '174379';
		    $PassKey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
		    $Password = base64_encode($BusinessShortCode . $PassKey . $timeStamp);
		    
		    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
	        
	        $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->access_token)); //setting custom header
	        
	        
	        $curl_post_data = array(
	        //Fill in the request parameters with valid values
	        'BusinessShortCode' => $BusinessShortCode,
	        'Password' => $Password,
	        'Timestamp' => $timeStamp,
	        'TransactionType' => 'CustomerPayBillOnline',
	        'Amount' => $amount,
	        'PartyA' => $phoneNo,
	        'PartyB' => $BusinessShortCode,
	        'PhoneNumber' => $phoneNo,
	        'CallBackURL' => 'https://infinitops.co.ke/subscribe/callback/',
	        'AccountReference' => 'INV008',
	        'TransactionDesc' => 'Subscription renewal'
	        );
	        
	        $data_string = json_encode($curl_post_data);
	        
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_POST, true);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	        
	        $curl_response = curl_exec($curl);
	        
	        $data = json_decode($curl_response);
	        if ($data->ResponseCode == 0) {
	        	$response = array('responseCode' => 200,
				 'message' => 'Request has been processed successfully. Enter pin on your phone.');
	        	return json_encode($response);
	        }
	        $response = array('responseCode' => 500,
				 'message' => 'We encountered an error while processing your request. Try again later.');
	        return json_encode($response);
		}
		
		public function transacStatus($transacId){
		    $url = 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';
              
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->access_token)); //setting custom header
              
              
            $curl_post_data = array(
            //Fill in the request parameters with valid values
                'Initiator' => 'testapi',
                'SecurityCredential' => 'PuCHTzOVrUs0jpf6eAd5r8f2vS/B1v/KKqkugqp8HFM5X4rnwGGQj09ROp0A5t9bjbTzoQSRZyokjSr9+MpDoFPbd7DwOe/X5PgOzXxQGVSfFlnSiMdH2BXwMIZd5smcewYOmg6BLNHy3fw4/XXCkYriipuyiIxeEMxemwdaguIg26ZH+iiVIJNpXgCFeRDHhmXQWMCeqvxJpZMXXAjR17dC8eqcauyoiM2eAdIWWZKyHm2vMZKon0X8uslABIXIYm9jDo//1G0PQYcEN6YWb7GIOUAYN/i81eQxnYA0ijwJ63yc9C6OW4f5VzqAIRjioLjTJhk6hSFznBBUyD6qfA==',
                'CommandID' => 'TransactionStatusQuery',
                'TransactionID' => $transacId,
                'PartyA' => '600158',
                'IdentifierType' => '4',
                'ResultURL' => 'https://infinitops.co.ke/subscribe/status/',
                'QueueTimeOutURL' => 'https://infinitops.co.ke/subscribe/status/',
                'Remarks' => 'Paybill',
                'Occasion' => 'INfiNItops'
            );
              
            $data_string = json_encode($curl_post_data);
              
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
              
            $curl_response = curl_exec($curl);
            print_r($curl_response);
              
            echo $curl_response;
		}

		public function getRate()
		{
			$rates = Rater::all();
			return $rates;
		}
		public static function insertResponse($rawData){
	        $data = json_decode($rawData, true);
	        Transaction::insert($data);
	        return "Success";
		}

		public function renew($productId, $transId, $lastDate){
			try{
				$rates = Rater::where('productId', $productId)->get();
				$rate;
				if (sizeof($rates)) {
					$rate = $rates[0];
				}else $rate = Rater::find(1);
				$charge = $rate->rate;
				$transac = Transaction::where('TransID', $transId)->firstOrFail();
				if ($transac->active == 0) {
					$activation = Activation::where('transacId', $transId)->firstOrFail();
					$response = array('responseCode' => 200, 
							'message' => 'Your request has been processed successfully. Thank you.',
							'expiresOn' => $activation->expiresOn);
					return json_encode($response);
				}
				$amount = $transac->TransAmount;
				$days = $amount / $charge;
				$days = floor($days);
				$date = date('Y-m-d', strtotime($lastDate . '+'.$days.' day'));
				$response = array('responseCode' => 200, 
							'message' => 'Your request has been processed successfully. Thank you.',
							'expiresOn' => $date);
				$transac->active = 0;
				$transac->save();
				Activation::create([
					'transacId' => $transId,
					'days' =>$days,
					'expiresOn' => $date,
					'clientName' => 'This client',
					'rateId' => $rate->id,
				]);
				return json_encode($response);
			}catch (Exception $e){		
				$response = array('responseCode' => 500,
				 'message' => 'We encountered an error while processing your request. Contact support.'.$e->getMessage());
				return json_encode($response);
			}
				
		}
		public function reverse($transId)
		{
			$url = 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';
  
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->access_token)); //setting custom header

			$transaction = Transaction::where('TransID', $transId)->firstOrFail();

			$curl_post_data = array(
			//Fill in the request parameters with valid values
			'Initiator' => 'testapi',
			'SecurityCredential' => 'PuCHTzOVrUs0jpf6eAd5r8f2vS/B1v/KKqkugqp8HFM5X4rnwGGQj09ROp0A5t9bjbTzoQSRZyokjSr9+MpDoFPbd7DwOe/X5PgOzXxQGVSfFlnSiMdH2BXwMIZd5smcewYOmg6BLNHy3fw4/XXCkYriipuyiIxeEMxemwdaguIg26ZH+iiVIJNpXgCFeRDHhmXQWMCeqvxJpZMXXAjR17dC8eqcauyoiM2eAdIWWZKyHm2vMZKon0X8uslABIXIYm9jDo//1G0PQYcEN6YWb7GIOUAYN/i81eQxnYA0ijwJ63yc9C6OW4f5VzqAIRjioLjTJhk6hSFznBBUyD6qfA==',
			'CommandID' => 'TransactionReversal',
			'TransactionID' => $transId,
			'Amount' => $transaction->TransAmount,
			'ReceiverParty' => '600158',
			'RecieverIdentifierType' => '11',
			'ResultURL' => 'https://infinitops.co.ke/subscribe/reversal/',
			'QueueTimeOutURL' => 'https://infinitops.co.ke/subscribe/reversal/',
			'Remarks'=>'Customer request for reversal.',
			'Occasion' => 'Trans Reversal',
			);

			$data_string = json_encode($curl_post_data);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

			$curl_response = curl_exec($curl);
			print_r($curl_response);

			echo $curl_response;
		}
	}
	$consumerKey = 'AegN61gHQQHDgJNGZXs9BfHQjrKp4EFw';
	$consumerSecret = 'T8RkWXM7GX1Ol2yp';
	$subscribe = new Subscribe($consumerKey, $consumerSecret); 
	$request = $_GET['request'];
	if ($request == "lipa") {
		$phoneNo = $_POST['phoneNo'];
		$amount = $_POST['amount'];
		/*if (!$phoneNo == null) {
		}
		if ($amount == null || $amount < 10) {
			
		}*/
		echo $subscribe->lipaNaMpesa($amount, $phoneNo);
	} else if ($request == "renew") {
		$transId = $_POST['transId'];
		$lastDate = $_POST['lastDate'];
		$productId = $_POST['productId'];
		if ($transId == null || $transId == "") {
			
		}
		if ($lastDate == null) {
			
		}
		if ($productId == null) {
			
		}
		echo $subscribe->renew($productId, $transId, $lastDate);
	} else if ($request == "simulate"){
	    echo $_POST['amount'] . $request;
		$subscribe->simulateC2B(254708374149, 400, 002);
	} else if ($request == "register"){
		$subscribe->registerUrl();
	} else if ($request == "insert"){
		$subscribe->insertResponse();
	}  else if ($request == "status"){
	    $transacId = $_POST['transacId'];
		$subscribe->transacStatus($transacId);
	}  else if ($request == "reverse"){
	    $transacId = $_POST['transacId'];
		$subscribe->reverse($transacId);
	}else if ($request == "transac"){
	    $transacId = $_POST['transacId'];
		//$subscribe->reverse($transacId);
		echo json_encode(Transaction::where('TransID', $transacId)->get());
	}
	//echo $subscribe->renew('IMV01', 'OFI72HC7YB', '2020-07-06');