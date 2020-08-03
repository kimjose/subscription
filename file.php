<?php

require_once __DIR__ . "/models/User.php";

use models\User;

if (isset($_POST["username"])) {
    try {
        $name = $_POST["username"];
        $password = $_POST["password"];
        $user = User::where('username', $name)->where('password', $password)->firstOrFail();
        session_start();
        $_SESSION['user'] = $user;
        echo "success";
    } catch (Exception $e) {
        echo "Unable to proceed" . $e->getMessage();
    }
} else {
    echo "Fuck ";
}