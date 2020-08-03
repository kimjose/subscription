<?php 


	require __DIR__."/../models/Transaction.php";
	use models\Transaction;


	header("Content-Type: application/json");

	



	$data = file_get_contents('php://input');



	$logFile = "reversal.json";



	$log = fopen($logFile, 'a');



	fwrite($log, $data);
	//fwrite($log, "ID: ");


	$json_data = json_decode($data);
	$code = $json_data->Result->ResultCode;
	if ($code == 0) {
		$params = $json_data->Result->ResultParameters->ResultParameter;
		for($i = 0; $i<sizeof($params); $i++){
		    $param = $params[$i];
		    $key = $param->Key;
		    if($key == "OriginalTransactionID"){
		        $transID = $param->Value;
		        $transaction = Transaction::where('TransID','LIKE', $transId)->first();
				$transaction->status = "Reversed";
				$transaction->save();
		    }
		}
	}
	
		
	

	fclose($log);
 ?>