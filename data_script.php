<?php
    require_once __DIR__ . "/models/Transaction.php";
    require_once __DIR__ . "/models/Activation.php";
    require_once __DIR__ . "/models/Client.php";

    use models\Transaction; 
    use models\Activation;
    use models\Client;

    $type = $_GET["request"];
    try{
        $response = array();
        if ($type == "activations"){
            $activations = Activation::all();
            $response['code'] = 0;
            $response['message'] = "Request processed successfully.";
            $response['data'] = $activations;
            echo json_encode($response);
        } elseif ($type == "transactions") {
            $transactions = Transaction::all();
            $response['code'] = 0;
            $response['message'] = "Request processed successfully.";
            $response['data'] = $transactions;
            echo json_encode($response);
        } elseif ($type == "clients") {
            $clients = Client::all();
            $response['code'] = 0;
            $response['message'] = "Request processed successfully.";
            $response['data'] = $clients;
            echo json_encode($response);
        } elseif ($type == "add_client") {
            Client::create([
                "name"=>$_POST['name'],
                'email'=>$_POST['email'],
                'phoneNumber'=>$_POST['phoneNumber'],
                'location'=>$_POST['location'],
                'businessDescription' => $_POST['desc'],
            ]);
            $response['code'] = 0;
            $response['message'] = "Request processed successfully.";
            $response['data'] = Client::all();
            echo json_encode($response);
        }
        else {
            throw new Exception("Invalid Request", 1);
        }
    } catch(Exception $e){
        $response = [];
        $response['code'] = 1;
        $response['message'] = "We are unable to proces your request";
        $response['error'] = $e->getMessage();
        echo json_encode($response);
    }