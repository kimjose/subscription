<?php


$data = file_get_contents('php://input');



$logFile = "b2c_response.json";



$log = fopen($logFile, 'a');



fwrite($log, $data);

fclose($log);