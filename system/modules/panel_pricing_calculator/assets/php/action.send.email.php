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
		<h2>User Details:</h2>
		";
	
	$message_user_contents = "";
	$message_panel_contents = "";
		
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
	

	// build the html for the users contents
	$message_user_contents = $message_user_contents . '<p>Name: ' . $vars['user_first_name'] . " " . $vars['user_last_name'] . '</p><p>Email: ' . $vars['user_email'] . '</p><p>Phone: ' . $vars['user_phone'] . '</p><p>Address Line 1: ' . $vars['user_address_1'] . '</p><p>Address Line 2: ' . $vars['user_address_2'] . '</p><p>State: ' . $vars['user_state'] . '</p><p>City: ' . $vars['user_city'] . '</p><p>Zip: ' . $vars['user_zip'] . '</p><p>Tell Us: ' . $vars['user_tell_us'] . '</p><br><br>';
	
	
	// build the html for the panel contents
	foreach ($_SESSION['asdf'] as $key => $result){
		$clean = unserialize($result);
		$message_panel_contents = $message_panel_contents . '<p>Panel: ' .getPanelNameByID($clean['panel_id']). '</p><p>Thickness: ' .getPanelThicknessFromID($clean['flat_id']). '</p><p>Cradle: ' .getPanelCradleFromID($clean['cradle_id']). '</p><p>Size: ' .$clean['width']. ' X ' .$clean['height']. '</p><p>Quantity: ' .$clean['quantity']. '</p><p>Price: ' .$clean['price']. '</p><br><br>';
		
		$query = "INSERT INTO `tl_quote_request` (`id`, `tstamp`, `sorting`, `alias`, `panel_type`, `thickness`, `cradle`, `width`, `height`, `quantity`, `discount`, `price`, `published`, `tell_us`, `zip`, `state`, `city`, `address_2`, `address_1`, `phone`, `email`, `last_name`, `reviewed`, `first_name`) VALUES (NULL, '0', '0', '', '".getPanelNameByID($clean['panel_id'])."', '".getPanelThicknessFromID($clean['flat_id'])."', '".getPanelCradleFromID($clean['cradle_id'])."', '".$clean['width']."', '".$clean['height']."', '".$clean['quantity']."', '0', '".$clean['price']."', '1', '".$vars['user_tell_us']."', '".$vars['user_zip']."', '".$vars['user_state']."', '".$vars['user_city']."', '".$vars['user_address_2']."', '".$vars['user_address_1']."', '".$vars['user_phone']."', '".$vars['user_email']."', '".$vars['user_last_name']."', 'unreviewed', '".$vars['user_first_name']."')";
		$result = $dbh->prepare($query);
		$result->execute();
	
	}
	
	
	
	// send the mail
	//mail($to,$subject,($message_start . $message_user_contents . '<h2>Quote Items:</h2>' . $message_panel_contents . $message_end),$headers);
	
	
		
	echo "Email Sent!";




// Functions
//////////////////////////

function getPanelNameByID($panel_id){
	switch ($panel_id) {
		case 1:
			return "Claybord";
			break;
		case 2:
			return "Gessobord";
			break;
		case 3:
			return "Aquabord";
			break;
		case 4:
			return "Encausticbord";
			break;
		case 5:
			return "Scratchbord";
			break;
		case 6:
			return "Pastelbord";
			break;
		case 7:
			return "Hardbord";
			break;
	}
}	

function getPanelThicknessFromID($panel_id){
	switch ($panel_id) {
		case 1:
			return '1/8"';
			break;
		case 2:
			return '1/4"';
			break;
	}
}

function getPanelCradleFromID($panel_id){
	switch ($panel_id) {
		case "none":
			return "None";
			break;
		case "3":
			return "3/4\" Cradled";
			break;
		case "4":
			return "1\" Cradled";
			break;
		case "5":
			return "1 1/2\" Cradled";
			break;
		case "6":
			return "2\" Cradled";
			break;
		case "7":
			return "2 1/2\" Cradled";
			break;
		case "8":
			return "3\" Cradled";
			break;
	}
}
