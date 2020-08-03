<?php 
    require __DIR__."/../models/Transaction.php";
    use models\Transaction;
	header("Content-Type: application/json");
	

	$data = file_get_contents('php://input');

	$logFile = "response.txt";

	$log = fopen($logFile, 'a');

	fwrite($log, $data);
	fclose($log);
	
	$arr = json_decode($data, true);
    $body = $arr['Body'];
    $callBack = $body['stkCallback'];
    $resultCode = $callBack['ResultCode'];
    if ($resultCode == 0) {
        $metaData = $callBack['CallbackMetadata'];
        $item = $metaData['Item'];
        $amount = $item[0]['Value'];
        $transId = $item[1]['Value'];
        $balance= $item[2]['Value'];
        $date= $item[3]['Value'];
        $phoneNo= $item[4]['Value'];
        Transaction::create([
            'TransactionType'=>'Lipa Online',
            'TransID'=>$transId,
            'TransTime'=>$date,
            'TransAmount'=>$amount,
            'BusinessShortCode'=>25420,
            'BillRefNumber'=>214,
            'InvoiceNumber'=>214,
            'OrgAccountBalance'=>$balance,
            'ThirdPartyTransID'=>986,
            'MSISDN'=>$phoneNo,
            'FirstName'=>'Lipa',
            'MiddleName'=>'Till',
            'LastName'=>'Online',
            ]);
    }

 ?>