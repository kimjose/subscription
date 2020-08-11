<?php
require '../MobilePayment.php';


//	header("Content-Type: application/json");


use MobilePayment as Payment;


$data = file_get_contents('php://input');



$logFile = "confirmation.txt";



$log = fopen($logFile, 'a');



fwrite($log, $data);

fclose($log);

Payment::insertResponse($data);
