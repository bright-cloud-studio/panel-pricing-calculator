<?php

// start a session
session_start();
error_reporting(E_ALL ^ E_NOTICE);

//$_SESSION['asdf']=array();


if(isset($_SESSION['asdf'])) {
} else {
    $_SESSION['asdf']=array();
}




try {
    $dbh = new PDO("mysql:host=localhost;dbname=ampers_cms413", 'ampers_cms_db_admin', 'JKDlS*3klsd9sk', array(
    PDO::ATTR_PERSISTENT => true
));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

// include all of the functions this will use
include_once('class.cart.endpoint.php');

// create a new object using the database connection
$QuoteCart = new QuoteCart($dbh);
