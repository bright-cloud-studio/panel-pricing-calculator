<?php

// start a session
session_start(); // php function
error_reporting(E_ALL ^ E_NOTICE); // php function with pre defined number


// removing the connection to the database, no longer needed. Data will flow to and from Contao instead of the database directly.

try {
    $dbh = new PDO("mysql:host=localhost;dbname=ampersan_cms49", 'ampersan_dbadmin', 'Y06ZCg9BiAh2Uv#@#@', array(
    PDO::ATTR_PERSISTENT => true
));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}


// include all of the functions this will use
include_once('class.panel.calculator.endpoint.php');

// create a new object using the database connection
$PanelCalculator = new PanelCalculator($dbh);
//$PanelCalculator = new PanelCalculator(); // removed the $dbh connection
