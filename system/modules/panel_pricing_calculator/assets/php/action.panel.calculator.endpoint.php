<?php

    // Starts the session and connects to the database
    include_once("prepend.endpoint.php");

    // object created in prepend.endpoint.php, set our total price to the value returned from the processForm() function
    $total_price = $PanelCalculator->processForm();

    // if we have a total price, push it onto the page to be grabbed by the ajax call
    if ($total_price) {
        echo $total_price;
    }

?>
