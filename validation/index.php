<?php 
	

	$data = file_get_contents('php://input');

	$logFile = "validation.txt";

	$log = fopen($logFile, 'a');

	fwrite($log, $data);
	fclose($log);
	
	$json = json_decode($data);
	//$amount = $data['TransAmount'];
	
	
	header("Content-Type: application/json");
	
    $response = array(
            "ResultCode"=>0,
            "ResultDesc"=>"Confirmed"
        );
    $json_response = json_encode($response);
    echo $json_response;
	
	
 ?>