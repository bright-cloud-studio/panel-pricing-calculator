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
	
try {
    $dbh = new PDO("mysql:host=localhost;dbname=bcdev_contao_4_9_20", 'bcdev_user', 'T{-hrwAC.N;Y%IY,)s', array(
    PDO::ATTR_PERSISTENT => true
));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
	
	
	foreach ($_SESSION['asdf'] as $key => $result){
		$clean = unserialize($result);
		$message_contents = $message_contents . '<p>Panel: ' .$clean['panel_id']. '</p><p>Thickness: ' .$clean['flat_id']. '</p><p>Cradle: ' .$clean['cradle_id']. '</p><p>Size: ' .$clean['width']. ' X ' .$clean['height']. '</p><p>Quantity: ' .$clean['quantity']. '</p><br><br>';
		
		$query = "INSERT INTO `tl_quote_request` (`id`, `tstamp`, `sorting`, `alias`, `panel_type`, `thickness`, `cradle`, `width`, `height`, `quantity`, `discount`, `price`, `published`, `tell_us`, `zip`, `state`, `city`, `address_2`, `address_1`, `phone`, `email`, `last_name`, `reviewed`, `first_name`) VALUES (NULL, '0', '0', '', 'Hardbord', '1', '2', '3', '4', '5', '6', '7', '1', 'asdf', '11111', 'ma', 'westfield', '923 General Knox rd', 'asdf', '4135644795', 'stjeanmark@gmail.com', 'St. Jean', 'reviewed', 'Mark')";
		$result = $dbh->prepare($query);
		$result->execute();
	
	}
	
	
	

	//mail($to,$subject,($message_start . $message_contents . $message_end),$headers);
	
	
		
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
