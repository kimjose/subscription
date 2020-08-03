<?php 

// Genartes access token
	 function generateToken()
	{
		$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		$credentials = base64_encode('AegN61gHQQHDgJNGZXs9BfHQjrKp4EFw:T8RkWXM7GX1Ol2yp');
		$headers = ['Content-Type:application/json: charset=utf8'];
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization:Basic '.$credentials));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl, CURLOPT_USERPWD, 'LfHOyC6ysjhhZn6A6SgzG6gjEH2VvVfc:ohp94CE0zbe1I07V');
		//curl_setopt($curl, CURLOPT_HEADER, false);


		$curl_response = curl_exec($curl);
		$decode = json_decode($curl_response);
		$token = $decode->access_token;
		return $token;
	}
//Registering confrimation and validation urls...
	 function registerURL()
	{
		$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.generateToken()));
		$post_data = array(
			'ShortCode'=>'600730',
			'ResponseType'=>'Cancelled',
			'ConfirmationURL'=>'https://infinitops.co.ke/subscribe/confirmation/',
			'ValidationURL'=>'https://infinitops.co.ke/subscribe/validation/',
		);
		$encoded_data = json_encode($post_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded_data);
		$curl_response = curl_exec($curl);
		return $curl_response;
	}
	//Simulating a c2b transaction
	 function simulateC2B($phone, $amount, $refNo)
	{
		$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
  
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.generateToken())); //setting custom header
	  
	  //'Msisdn' => 254708374149,
	    $curl_post_data = array(
	            //Fill in the request parameters with valid values
	           'ShortCode' => '600730',
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
	  
	    return  $curl_response;
	}
	
	//initiates a transaction via stk push
	function lipaNaMpesa($amount, $phoneNo){
	    $timeStamp = date('YmdGis');
	    $BusinessShortCode = '174379';
	    $PassKey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
	    $Password = base64_encode($BusinessShortCode . $PassKey . $timeStamp);
	    
	    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.generateToken())); //setting custom header
        
        
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
        print_r($curl_response);
        
        echo $curl_response;
	}
	
	
	function insertResponse($jsonMpesaResponse){
		try{
	    $server = 'localhost';
	    $dbName = 'patakeja_tax';
	    $pdo = null;
		$conStr = sprintf("mysql:host=%s;dbname=%s", $server, $dbName);
		$pdo = new PDO($conStr, 'patakeja_patakeja', 'virtualcvcreator.io.ke');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->beginTransaction();
		//add transaction details to db
		$insert = "INSERT INTO `mpesa_transactions`(`TransID`, `TransactionType`, `TransTime`, `TransAmount`, `BusinessShortCode`, `BillRefNumber`, `InvoiceNumber`, `OrgAccountBalance`, `ThirdPartyTransID`, `MSISDN`, `FirstName`, `MiddleName`, `LastName`) VALUES(TransID, TransactionType, TransTime, TransAmount, BusinessShortCode, BillRefNumber, InvoiceNumber, OrgAccountBalance, ThirdPartyTransID, MSISDN, FirstName, MiddleName, LastName)";
		$stmt = $pdo->prepare($insert);
		$stmt->execute(array($jsonMpesaResponse));
		$value = array(); 
		}catch(PDOException $e){
		    $response['status'] = false;
			$response['message'] = "Unable to proceed with you transaction";
			$handle = fopen('errors.txt', 'a');
			fwrite($handle, $e->getMessage());
			fclose($handle);
			$failedTransacs = fopen('failedTransacs.txt', 'a');
			fwrite($failedTransacs, $jsonMpesaResponse);
			fclose($failedTransacs);
		    //die("unable to proceed with you transaction")
		}
	}
	

 ?>