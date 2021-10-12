<?php
	
	include_once("prepend.cart.endpoint.php");
	
	// Start our session
	session_start();

	// return our carts total
	echo count($_SESSION['asdf']);


?>
