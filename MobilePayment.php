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

class MobilePayment
{
	protected $access_token;
	function __construct($consumerKey, $consumerSecret)
	{
		$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		$credentials = base64_encode($consumerKey . ':' . $consumerSecret);
		$headers = ['Content-Type:application/json: charset=utf8'];
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization:Basic ' . $credentials));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl, CURLOPT_USERPWD, 'LfHOyC6ysjhhZn6A6SgzG6gjEH2VvVfc:ohp94CE0zbe1I07V');
		//curl_setopt($curl, CURLOPT_HEADER, false);


		$curl_response = curl_exec($curl);
		$decode = json_decode($curl_response);
		$this->access_token = $decode->access_token;
	}

	public function registerUrl()
	{
		$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
		
		$curl_post_data = array(
			'ShortCode' => '600158',
			'ResponseType' => 'Cancelled',
			'ConfirmationURL' => 'https://infinitops.co.ke/MobilePayment/confirmation/',
			'ValidationURL' => 'https://infinitops.co.ke/MobilePayment/validation/',
		);

		return $this->runTransac($url, json_encode($curl_post_data));
	}

	public function simulateC2B($phone, $amount, $refNo)
	{
		$url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

		//'Msisdn' => 254708374149,
		$curl_post_data = array(
			//Fill in the request parameters with valid values
			'ShortCode' => '600158',
			'CommandID' => 'CustomerPayBillOnline',
			'Amount' => $amount,
			'Msisdn' => $phone,
			'BillRefNumber' => $refNo
		);

		return $this->runTransac($url, json_encode($curl_post_data));
	}

	public function simulateB2C(){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';


		$curl_post_data = array(
			//Fill in the request parameters with valid values
			'InitiatorName' => 'testapi',
			'SecurityCredential' => 'dreeEqlRd1aLeaBG4BjEZa7L4Sbk3uik2WbWWuvh8U2cxbe+SOYEGznFPTd3cNsAIw0JDNixhFQ+1NeeyLMO2iSr8grqIcIaPEPi6jDFkPEqiTRL/jzs1bheAZBdCxXFg3h8SxX5z8CVOz3ElIGJqdrtM046pnE32REO4N1+n5DiLoVc6HkDan8PNd6XNiV6LJW6qsM/5c1UW+aNVEwTmI6mrOX1YgJZXidM89AmVoEOmqcVNesxIdbv4GWD75mp60XeOZDRqGe+DfR21r8B9tabYUfgh60iMHkQzYGaSLEypbQsIsTeP6pBqDUXzDG27Nf+YO1YCcIOeoOL0kUyRg==',
			'CommandID' => 'BusinessPayment',
			'Amount' => '100',
			'PartyA' => '600158',
			'PartyB' => '254708374149',
			'Remarks' => 'Payment sent.',
			'QueueTimeOutURL' => 'https://infinitops.co.ke/MobilePayment/b2c/',
			'ResultURL' => 'https://infinitops.co.ke/MobilePayment/b2c/',
			'Occasion' => 'Goods delivered'
		);

		return $this->runTransac($url, json_encode($curl_post_data));
	}

	//Not done
	public function simulateB2B($amount, $shortCode1, $shortCode2){
		$url = 'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';

		$curl_post_data = array(
			//Fill in the request parameters with valid values
			'Initiator' => 'testapi',
			'SecurityCredential' => 'dreeEqlRd1aLeaBG4BjEZa7L4Sbk3uik2WbWWuvh8U2cxbe+SOYEGznFPTd3cNsAIw0JDNixhFQ+1NeeyLMO2iSr8grqIcIaPEPi6jDFkPEqiTRL/jzs1bheAZBdCxXFg3h8SxX5z8CVOz3ElIGJqdrtM046pnE32REO4N1+n5DiLoVc6HkDan8PNd6XNiV6LJW6qsM/5c1UW+aNVEwTmI6mrOX1YgJZXidM89AmVoEOmqcVNesxIdbv4GWD75mp60XeOZDRqGe+DfR21r8B9tabYUfgh60iMHkQzYGaSLEypbQsIsTeP6pBqDUXzDG27Nf+YO1YCcIOeoOL0kUyRg==',
			'CommandID' => ' ',
			'SenderIdentifierType' => ' ',
			'RecieverIdentifierType' => ' ',
			'Amount' => ' ',
			'PartyA' => ' ',
			'PartyB' => ' ',
			'AccountReference' => ' ',
			'Remarks' => ' ',
			'QueueTimeOutURL' => 'https://infinitops.co.ke/MobilePayment/b2b/',
			'ResultURL' => 'https://infinitops.co.ke/MobilePayment/b2b/'
		);

		return $this->runTransac($url, json_encode($curl_post_data));
	}

	public function lipaNaMpesa($amount, $phoneNo)
	{
		$timeStamp = date('Ymdhis');
		$BusinessShortCode = '174379';
		$PassKey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
		$Password = base64_encode($BusinessShortCode . $PassKey . $timeStamp);

		$url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

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
			'CallBackURL' => 'https://infinitops.co.ke/MobilePayment/callback/',
			'AccountReference' => 'INV008',
			'TransactionDesc' => 'Subscription renewal'
		);


		$data =	$this->runTransac($url, json_encode($curl_post_data));
		if ($data->ResponseCode == 0) {
			$response = array(
				'responseCode' => 200,
				'message' => 'Request has been processed successfully. Enter pin on your phone.'
			);
			return json_encode($response);
		}
		$response = array(
			'responseCode' => 500,
			'message' => 'We encountered an error while processing your request. Try again later.'
		);
		return json_encode($response);
	}

	public function transacStatus($transacId)
	{
		$url = 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';

		$curl_post_data = array(
			//Fill in the request parameters with valid values
			'Initiator' => 'testapi',
			'SecurityCredential' => 'PuCHTzOVrUs0jpf6eAd5r8f2vS/B1v/KKqkugqp8HFM5X4rnwGGQj09ROp0A5t9bjbTzoQSRZyokjSr9+MpDoFPbd7DwOe/X5PgOzXxQGVSfFlnSiMdH2BXwMIZd5smcewYOmg6BLNHy3fw4/XXCkYriipuyiIxeEMxemwdaguIg26ZH+iiVIJNpXgCFeRDHhmXQWMCeqvxJpZMXXAjR17dC8eqcauyoiM2eAdIWWZKyHm2vMZKon0X8uslABIXIYm9jDo//1G0PQYcEN6YWb7GIOUAYN/i81eQxnYA0ijwJ63yc9C6OW4f5VzqAIRjioLjTJhk6hSFznBBUyD6qfA==',
			'CommandID' => 'TransactionStatusQuery',
			'TransactionID' => $transacId,
			'PartyA' => '600158',
			'IdentifierType' => '4',
			'ResultURL' => 'https://infinitops.co.ke/MobilePayment/status/',
			'QueueTimeOutURL' => 'https://infinitops.co.ke/MobilePayment/status/',
			'Remarks' => 'Paybill',
			'Occasion' => 'INfiNItops'
		);

		return $this->runTransac($url, json_encode($curl_post_data));;
	}

	public function accBalance()
	{
		$url = 'https://sandbox.safaricom.co.ke/mpesa/accountbalance/v1/query';

		$curl_post_data = array(
			//Fill in the request parameters with valid values
			'Initiator' => 'testapi',
			'SecurityCredential' => 'WvRQcxD1dMyn1HzRiw2xrBt5iB1G0wg0F0eIRYnpnjnNrtO+7TrNjioumJnzI0Gr0nuE4uuTReLw6J1dzK/tITPyCgY9Lsxtp2abn2P4DMYaVsNY35lI30lyPhLDDYYDZy9z4hVliF3FYVvNX0OyCxC1nQt7VIW6E6eJeFCP7wyEGLm8b/NzzduQ6rr4amnRtwwLgczl72xFld4L42fMrLSZdYiZCCHCUdQTqBLdsY0PAEu/eDpv5aIzOE2rhW1bpJgQdD0UvPCcryGKZwRuRZ9pHL5PMoIiCMF5N+nLDMrtgbKWMoKPdvXjXdjTELgPhexkSrOELxl7LfKocOq1dQ==',
			'CommandID' => 'AccountBalance',
			'PartyA' => '600158',
			'IdentifierType' => '4',
			'Remarks' => 'Balance checked.',
			'QueueTimeOutURL' => 'https://infinitops.co.ke/MobilePayment/balance/',
			'ResultURL' => 'https://infinitops.co.ke/MobilePayment/balance/'
		);
		return $this->runTransac($url, json_encode($curl_post_data));
	}


	/**
	 * Runs almost every transaction via this api
	 * @param String $url The url link
	 * @param String $postData The encodes data
	 * 
	 * @return Mixed curl-response
	 */
	private function runTransac($url, $postData)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->access_token)); //setting custom header
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

		$curl_response = curl_exec($curl);
		return $curl_response;
	}

	public function getRate()
	{
		$rates = Rater::all();
		return $rates;
	}
	public static function insertResponse($rawData)
	{
		$data = json_decode($rawData, true);
		Transaction::insert($data);
		return "Success";
	}

	public function renew($productId, $transId, $lastDate)
	{
		try {
			$rates = Rater::where('productId', $productId)->get();
			$rate = 0;
			if (sizeof($rates)) {
				$rate = $rates[0];
			} else $rate = Rater::find(1);
			$charge = $rate->rate;
			$transac = Transaction::where('TransID', $transId)->firstOrFail();
			if ($transac->active == 0) {
				$activation = Activation::where('transacId', $transId)->firstOrFail();
				$response = array(
					'responseCode' => 200,
					'message' => 'Your request has been processed successfully. Thank you.',
					'expiresOn' => $activation->expiresOn
				);
				return json_encode($response);
			}
			$amount = $transac->TransAmount;
			$days = $amount / $charge;
			$days = floor($days);
			$date = date('Y-m-d', strtotime($lastDate . '+' . $days . ' day'));
			$response = array(
				'responseCode' => 200,
				'message' => 'Your request has been processed successfully. Thank you.',
				'expiresOn' => $date
			);
			$transac->active = 0;
			$transac->save();
			Activation::create([
				'transacId' => $transId,
				'days' => $days,
				'expiresOn' => $date,
				'clientName' => 'This client',
				'rateId' => $rate->id,
			]);
			return json_encode($response);
		} catch (Exception $e) {
			$response = array(
				'responseCode' => 500,
				'message' => 'We encountered an error while processing your request. Contact support.' . $e->getMessage()
			);
			return json_encode($response);
		}
	}
	public function reverse($transId)
	{
		$url = 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->access_token)); //setting custom header

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
			'ResultURL' => 'https://infinitops.co.ke/MobilePayment/reversal/',
			'QueueTimeOutURL' => 'https://infinitops.co.ke/MobilePayment/reversal/',
			'Remarks' => 'Customer request for reversal.',
			'Occasion' => 'Trans Reversal',
		);

		$data_string = json_encode($curl_post_data);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		$curl_response = curl_exec($curl);

		return $curl_response;
	}
}
$handle = fopen('credentials.json', 'r');
$data = fread($handle, filesize('credentials.json'));
$decoded_data = json_decode($data);
$consumerKey = $decoded_data->consumerKey;
$consumerSecret = $decoded_data->consumerSecret;
$mobilePayment = new MobilePayment($consumerKey, $consumerSecret);
$request = $_GET['request'];
if ($request == "lipa") {
	$phoneNo = $_POST['phoneNo'];
	$amount = $_POST['amount'];
	/*if (!$phoneNo == null) {
		}
		if ($amount == null || $amount < 10) {
			
		}*/
	echo $mobilePayment->lipaNaMpesa($amount, $phoneNo);
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
	echo $mobilePayment->renew($productId, $transId, $lastDate);
} else if ($request == "simulate") {
	echo $_POST['amount'] . $request;
	$mobilePayment->simulateC2B(254708374149, 400, 002);
} else if ($request == "register") {
	$mobilePayment->registerUrl();
} else if ($request == "balance") {
	$mobilePayment->accBalance();
} else if ($request == "status") {
	$transacId = $_POST['transacId'];
	$mobilePayment->transacStatus($transacId);
} else if ($request == "reverse") {
	$transacId = $_POST['transacId'];
	$mobilePayment->reverse($transacId);
} else if ($request == "transac") {
	$transacId = $_POST['transacId'];
	//$MobilePayment->reverse($transacId);
	echo json_encode(Transaction::where('TransID', $transacId)->get());
}
	//echo $MobilePayment->renew('IMV01', 'OFI72HC7YB', '2020-07-06');