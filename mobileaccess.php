<?php 
/**
methods included create entry
INSERT INTO `tax_payers`(`id`, `owner`, `bs_name`, `location`, `phone`, `amount`, `email`, `bs_id`, `status`, `date_payment`, `latitude`, `longitude`, `specific_location`, `category`)


fetch 

****/

include 'db.php';
include 'functions.php';

$response = array();
$type = $_GET['type'];
if ($type=="register") {
	$owner = mysqli_real_escape_string($conn,$_POST['owner']);
	$bs_name = mysqli_real_escape_string($conn, $_POST['bs_name']);
	$location = $_POST['location'];
	$phone = $_POST['phone'];
	$amount = $_POST['amount'];
	$email = $_POST['email'];
	$bs_id = $_POST['bs_id'];
	$status = "not paid";
	$date_payment = "0000-00-00";
	$latitude = $_POST['latitude'];
	$longitude = $_POST['longitude'];
	$specific_location = $_POST['specific_location'];
	$category = $_POST['category'];
	$insert = "INSERT INTO `tax_payers` VALUES(0, '$owner','$bs_name','$location','$phone','$amount','$email','$bs_id','$status','$date_payment','$latitude','$longitude','$specific_location','$category')";
	$result = mysqli_query($conn, $insert);
	if ($result) {
		$response['status'] = true;
		$response['message'] = "Business was successfully registered ";
	}else {
		$response['status'] = false;
		$response['message'] = "Business was not registered ".mysqli_error($conn);
	}
}elseif ($type=="getcategories") {
	$sel = "SELECT * FROM `category`";
	$result = mysqli_query($conn, $sel);
	while ($row = mysqli_fetch_assoc($result)) {
		$data = array();
		$data['id'] = $row['id'];
		$data['name'] = $row['name'];
		array_push($response, $data);
	}
}elseif ($type=="getbusinesses") {
	$sel = "SELECT * FROM `tax_payers`";
	$result = mysqli_query($conn, $sel);
	while ($row = mysqli_fetch_assoc($result)) {
		$data = array();
		$data['id'] = $row['id'];
		$data['name'] = $row['bs_name'];
		$data['bsRef'] = $row['bs_id'];
		$data['category'] = $row['category'];
		$data['location'] = $row['location'];
		$data['phone'] = $row['phone'];
		$data['email'] = $row['email'];
		$data['status'] = $row['status'];
		$data['amount'] = $row['amount'];
		$data['lat'] = $row['latitude'];
		$data['lon'] = $row['longitude'];
		array_push($response, $data);
	}
}elseif ($type=="makepayment") {
	//get phone amount bsid
	//make payment
	//check if successful and create a record of the transaction
	//return feedback
	$phone = $_POST['phone'];
	//$amount = $_POST['amount'];
	$amount = 100;
	//$refNo = $_POST['refNo'];
	$refNo = "INV213";
	$curlResponse = simulateC2B($phone, $amount, $refNo);
	echo $curlResponse;

/*

	if ($result) {
		$response['status'] = true;
		$response['message'] = "Payment was successfully made ";
	}else {
		$response['status'] = false;
		$response['message'] = "Payment could not made ".mysqli_error($conn);
	}*/
}



 echo json_encode($response);
 ?>