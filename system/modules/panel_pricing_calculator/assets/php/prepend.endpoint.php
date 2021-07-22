<?php

// start a session
session_start();
error_reporting(E_ALL ^ E_NOTICE);

// connect to the database
try {
    $dbh = new PDO("mysql:host=localhost;dbname=ampersand_calc", 'ampersand_calc', 'u9kzfsAZES51', array(
    PDO::ATTR_PERSISTENT => true
));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

// include all of the functions this will use
include_once(dirname(__FILE__) . '/class.panel.calculator.endpoint.php');

// create a new object using the database connection
$PanelCalculator = new PanelCalculator($dbh);
