<?php

    // start a session
    session_start(); // php function
    error_reporting(E_ALL ^ E_NOTICE); // php function with pre defined number
    
    // removing the connection to the database, no longer needed. Data will flow to and from Contao instead of the database directly.
    try {
        $dbh = new PDO("mysql:host=localhost;dbname=ampers_cms413", 'ampers_cms_db_admin', 'JKDlS*3klsd9sk', array(
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
