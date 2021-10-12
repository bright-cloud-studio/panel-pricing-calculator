<?php
// Starts the session and connects to the database
include_once("prepend.cart.endpoint.php");

	// turn the form values into $vars
	$vars = $_POST;
	
	
	//unset($_SESSION['asdf'][$vars['entry_id']]);
	
	//$$_SESSION['asdf'] = array_values($$_SESSION['asdf']);
		
	//echo count($_SESSION['asdf']);
	
	
	

	// SEND EMAIL
	$to = "mark@brightcloudstudio.com";
	$subject = "Panel Calculator Quote Request";

	$message_start = "
		<html>
		<head>
		<title>Panel Calculator Quote Request</title>
		</head>
		<body>
		<h2>These are this quotes items:</h2>
		";
		
	$message_contents = "";
		
	$message_end = "
		</body>
		</html>
		";

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: <webmaster@example.com>' . "\r\n";
	$headers .= 'Cc: mark@brightcloudstudio.com' . "\r\n";
	
	
	foreach ($_SESSION['asdf'] as $key => $result){
		$clean = unserialize($result);
		$message_contents = $message_contents . '<p>Panel: ' .$clean['panel_id']. '</p><p>Thickness: ' .$clean['flat_id']. '</p><p>Cradle: ' .$clean['cradle_id']. '</p><p>Size: ' .$clean['width']. ' X ' .$clean['height']. '</p><p>Quantity: ' .$clean['quantity']. '</p><br><br>';
	}
	
	
	

	mail($to,$subject,($message_start . $message_contents . $message_end),$headers);
	
	
		
	echo "Email Sent!";
