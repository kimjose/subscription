<?php 

	header("Content-Type: application/json");
	

	$data = file_get_contents('php://input');

	$logFile = "transac_status.json";

	$log = fopen($logFile, 'a');

	fwrite($log, $data);
	fclose($log);
 ?>