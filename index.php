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
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="css/index.css" />
    <script src='js/fa-icons.js'></script>
</head>

<body>
    <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Hero</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li id="li_transactions" class="current">Transactions</li>
        <li id="li_activations">Activations</li>
        <li id="li_clients">Clients</li>
      </ul>
      <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" id="search_input" placeholder="Search Table...">
        </div>
        <i class="fas fa-search" style='font-size:24 px'></i>
      </form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
    <!--
    <header>
        <ul>
            <li id="li_transactions" class="current">Transactions</li>
            <li id="li_activations">Activations</li>
            <li id="li_clients">Clients</li>
        </ul>
    </header>-->
    <dialog id="clientDialog">
        <label>New Client Form.</label>
        <form id="clientForm">
            <div class="form-group">
                <label for="">Name:</label>
                <input type="text" name="name" id="inputName" class="form-control" placeholder="Client Name..." />
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="someone@example.com..." />
            </div>
            <div class="form-group">
                <label for="">Phone</label>
                <input type="number" name="num" id="inputNum" class="form-control" placeholder="Client contact number..." />
            </div>
            <div class="form-group">
                <label for="">Location</label>
                <input type="text" name="location" id="inputLocation" class="form-control" placeholder="Client Location"... />
            </div>
            <div class="form-group">
                <label for="">B. Desc</label>
                <input type="text" name="desc" id="inputDesc" class="form-control" placeholder="Business description..." />
            </div>
        </form>
        <button id="addClient" class="btn btn-success">Add Client</button>
        <button id="closeDialog" class="btn btn-danger">Close</button>
    </dialog>
    <div>
        <button id="newClient" class="btn btn-primary" value="New Client" style="display: none;">New Client</button>
    </div>
    <section class="middle_section">
        <table id="table" class="table table-bordered">
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
        <p>&copy; Hero 
            <script>
                let dt = new Date;
                let currentYear = dt.getFullYear();

                document.write(currentYear);
            </script>
        </p>
    </footer>
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>-->
    <script src="js/jquery3_5_1.js" type="text/javascript"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
    <script src="js/index.js" type="text/javascript"></script>
</body>

</html>