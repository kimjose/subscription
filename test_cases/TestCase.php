<?php
require __DIR__ . "/../MobilePayment.php";

use MobilePayment;

class TestCase extends MobilePayment
{
    public function __construct($consumerKey, $consumerSecret)
    {
        parent::__construct($consumerKey, $consumerSecret);
    }
}
