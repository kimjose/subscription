<?php 
session_start();
require_once __DIR__ . "/models/User.php";

use models\User;
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
} 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe | Details</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" />
    <script src='js/fa-icons.js'></script>
</head>

<body>

    <header>
        <ul>
            <li id="li_transactions" class="current">Transactions</li>
            <li id="li_activations">Activations</li>
            <li id="li_clients">Clients</li>
        </ul>
    </header>
    <dialog id="clientDialog">
        <label>New Client Form.</label>
        <form id="clientForm">
            <input type="text" name="name" id="inputName" placeholder="Enter client name" /><br>
            <input type="email" name="email" id="inputEmail" placeholder="Enter client email" /><br>
            <input type="number" name="num" id="inputNum" placeholder="Enter client contact number" /><br>
            <input type="text" name="location" id="inputLocation" placeholder="Enter client Location" /><br>
            <input type="text" name="desc" id="inputDesc" placeholder="Enter business description" /><br>
        </form>
        <button id="addClient" class="btn-success">Add Client</button>
        <button id="closeDialog" class="btn-cancel">Close</button>
    </dialog>
    <div>
        <input id="search_input" placeholder="Search table" type="text" />
        <i class="fas fa-search" style='font-size:24 px'></i>
        <button id="newClient" class="btn-primary" value="New Client" style="display: none;">New Client</button>
    </div>
    <section class="middle_section">
        <table id="table">
            <th>Transaction Id</th>
            <th>Name</th>
            <th>Mobile Number</th>
            <th>Amount</th>
            <?php
            require_once __DIR__ . "/models/Transaction.php";

            use models\Transaction;

            $transactions = Transaction::all();
            for ($i = 0; $i < sizeof($transactions); $i++) {
                $transac = $transactions[$i];
                $id = $transactions[$i]->TransID;
                $number = $transac->MSISDN;
                $name = $transac->FirstName . " " . $transac->MiddleName . " " . $transac->LastName;
                $amount = $transac->TransAmount;
                echo "<tr><td>$id</td><td>$name</td><td>$number</td><td>$amount</td></tr>";
            }
            ?>

        </table>
    </section>

    <footer>
        <p>Copyright hero</p>
    </footer>
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>-->
    <script src="js/jquery3_5_1.js" type="text/javascript"></script>
    <script src="js/index.js" type="text/javascript"></script>
</body>

</html>