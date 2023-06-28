<?php

    // Starts the session and connects to the database
    include_once("prepend.cart.endpoint.php");

	// turn the form values into $vars
	$vars = $_POST;
	
	// Unset removes the entry in the $_SESSION['asdf'] array at the index of the entry_id
	unset($_SESSION['asdf'][$vars['entry_id']]);
	// idk what this line is doing anymore so I wont be removing it
	$_SESSION['asdf'] = array_values($_SESSION['asdf']);
	// echo out the total number of entries in our session array
	echo count($_SESSION['asdf']);
