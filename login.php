<?php
    session_start();
    session_unset();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<meta name="Accept" content="application/json" />-->
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/login.css" />
</head>

<body>


    <div class="container">

        <form id="myForm">
            <label style="font-weight: bold; font-size: 20px">Login to continue.</label><br>
            <label for="username">Username</label><br>
            <input name="username" type="text" id="username" placeholder="Enter user name" /><br>
            <label for="password">Password</label><br>
            <input name="password" type="password" id="password" placeholder="Enter user password" /><br>
            <input type="submit" value="Login" id="loginBtn" />
        </form>
    </div>


    <script src="js/jquery3_5_1.js" type="text/javascript"></script>
    <script src="js/login.js" type="text/javascript"></script>


</body>

</html>