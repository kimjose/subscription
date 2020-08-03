<?php 
	require '../Subscribe.php';


//	header("Content-Type: application/json");

	
	use Subscribe as Subscriber;


	$data = file_get_contents('php://input');



	$logFile = "confirmation.txt";



	$log = fopen($logFile, 'a');



	fwrite($log, $data);

	fclose($log);

	Subscriber::insertResponse($data);

 