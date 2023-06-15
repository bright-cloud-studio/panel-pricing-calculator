<?php

    // Starts the session and connects to the database
    include_once("prepend.cart.endpoint.php");

	// turn the form values into $vars
	$vars = $_POST;
	
	unset($_SESSION['asdf'][$vars['entry_id']]);
	$$_SESSION['asdf'] = array_values($$_SESSION['asdf']);
	echo count($_SESSION['asdf']);
