<?php
// Starts the session and connects to the database
include_once("prepend.cart.endpoint.php");

    $debug_mode = true;
    if($debug_mode)
        $log = fopen($_SERVER['DOCUMENT_ROOT'] . '/../calc_logs/send_email_'.date('m_d_y').'.txt', "a+") or die("Unable to open file!");
        
	// turn the form values into $vars
	$vars = $_POST;
	
	debug($debug_mode, $log, "[Send Mail] Triggering script for email - " . $vars['user_email']);
	
	// SEND EMAIL
    //$to = "mark@brightcloudstudio.com";
	$to = $vars['user_email'];
	$subject = "Panel Calculator Quote Request - " . $vars['user_email'];

	$message_start = "
		<html>
		<head>
		<title>Panel Calculator Quote Request</title>
		</head>
		<body>
		";
	
	$message_user_contents = "";
	$message_panel_contents = "";
	$message_panel_contents_internal = "";
		
	$message_end = "
		</body>
		</html>
		";

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "Content-Transfer-Encoding: base64" . "\r\n";

	// More headers
	$headers .= 'From: <orders@ampersandart.com>' . "\r\n";

	// The in house copy is sent on its own rather than as a Cc, so it can carry the production
	// values without showing them to the customer. Same headers, different recipients.
	$internal_to = 'web@brightcloudstudio.com, bords@ampersandart.com, mark@brightcloudstudio.com';
	$internal_headers = $headers;


	// Add our custom intro text
	$message_user_contents = $message_user_contents . '<p>Thank you for your Ampersand Art Supply custom quote request.</p>';
	$message_user_contents = $message_user_contents . '<p>Our team is reviewing your request and will respond with a confirmed quote, estimated shipping and timing. Please allow 2-4 days for a response.</p>';
	$message_user_contents = $message_user_contents . '<p>All custom orders are handmade in our factory in Buda, TX just minutes outside of Austin. Due to the special nature of a custom-made panel, our current lead time is 8 weeks.</p><br>';

	$message_user_contents = $message_user_contents . '<h2>Details:</h2>';

	// build the html for the users contents
	$message_user_contents = $message_user_contents . '<p>Name: ' . $vars['user_first_name'] . " " . $vars['user_last_name'] . '</p><p>Email: ' . $vars['user_email'] . '</p><p>Phone: ' . $vars['user_phone'] . '</p><p>Address Line 1: ' . $vars['user_address_1'] . '</p><p>Address Line 2: ' . $vars['user_address_2'] . '</p><p>State: ' . $vars['user_state'] . '</p><p>City: ' . $vars['user_city'] . '</p><p>Zip: ' . $vars['user_zip'] . '</p><p>Tell Us About Your Project: ' . $vars['user_tell_us'] . '</p><br>';

	// we need to know what the previous sorting number is, if there is one
	$sorting_number = getPreviousID();
	
	$has_product = false;
	
	// build the html for the panel contents
	foreach ($_SESSION['asdf'] as $key => $result){
		$clean = unserialize($result);

		$message_panel_contents = $message_panel_contents . '<p>Panel: ' .getPanelNameByID($clean['panel_id']). '</p><p>Panel Thickness: ' .getPanelThicknessFromID($clean['flat_id']). '</p><p>Cradle: ' .getPanelCradleFromID($clean['cradle_id']). '</p><p>Size: ' .$clean['width']. ' X ' .$clean['height']. '</p><p>Quantity: ' .$clean['quantity']. '</p><p>Price: ' .$clean['price']. '</p><br>';

		// Work out the square footage and bracing for production. Cradles 1 1/2" and deeper brace
		// every 24", everything shallower every 12" - same math the calculator uses elsewhere.
		$square_feet = getSquareFeet($clean['width'], $clean['height']);
		$every_x_inches = in_array((int) $clean['cradle_id'], array(5, 6, 7, 8), true) ? 24.01 : 12.01;
		$cross_width = ceil($clean['width'] / $every_x_inches);
		$cross_height = ceil($clean['height'] / $every_x_inches);
		$linear_inch_width = ($cross_width + 1) * $clean['height'];
		$linear_inch_height = ($cross_height + 1) * $clean['width'];

		// The in house copy repeats the panel block and adds what production needs
		$message_panel_contents_internal = $message_panel_contents_internal . '<p>Panel: ' .getPanelNameByID($clean['panel_id']). '</p><p>Panel Thickness: ' .getPanelThicknessFromID($clean['flat_id']). '</p><p>Cradle: ' .getPanelCradleFromID($clean['cradle_id']). '</p><p>Size: ' .$clean['width']. ' X ' .$clean['height']. '</p><p>Quantity: ' .$clean['quantity']. '</p><p>Price: ' .$clean['price']. '</p><p>Custom Sq. Ft.: ' .$square_feet. '</p><p>Every X Inches: ' .$every_x_inches. '</p><p>Cross Width: ' .$cross_width. '</p><p>Cross Height: ' .$cross_height. '</p><p>Linear Inch Width: ' .$linear_inch_width. '</p><p>Linear Inch Height: ' .$linear_inch_height. '</p><br>';

		$query = "INSERT INTO `tl_quote_request` (`id`, `tstamp`, `sorting`, `alias`, `panel_type`, `thickness`, `cradle`, `width`, `height`, `quantity`, `discount`, `price`, `published`, `tell_us`, `zip`, `state`, `city`, `address_2`, `address_1`, `phone`, `email`, `last_name`, `reviewed`, `first_name`, `created`) VALUES (NULL, '".time()."', '".$sorting_number."', '', '".getPanelNameByID($clean['panel_id'])."', '".getPanelThicknessFromID($clean['flat_id'])."', '".getPanelCradleFromID($clean['cradle_id'])."', '".$clean['width']."', '".$clean['height']."', '".$clean['quantity']."', '0', '".$clean['price']."', '1', '".addslashes($vars['user_tell_us'])."', '".$vars['user_zip']."', '".$vars['user_state']."', '".$vars['user_city']."', '".$vars['user_address_2']."', '".$vars['user_address_1']."', '".$vars['user_phone']."', '".$vars['user_email']."', '".$vars['user_last_name']."', 'unreviewed', '".$vars['user_first_name']."', '".date('F j, Y, g:i a')."')";
		$result = $dbh->prepare($query);
		$result->execute();
	    $has_product = true;
	}
	
	
	$final_message = $message_start . $message_user_contents . '<h2>Quote Items:</h2>' . $message_panel_contents . $message_end;
	$final_message = chunk_split(base64_encode($final_message));

	// Same message for the in house copy, built from the panel block that carries the production values
	$internal_message = $message_start . $message_user_contents . '<h2>Quote Items:</h2>' . $message_panel_contents_internal . $message_end;
	$internal_message = chunk_split(base64_encode($internal_message));

	if($has_product) {
    	// send the mail
    	mail($to,$subject,$final_message,$headers);
    	mail($internal_to,$subject,$internal_message,$internal_headers);
    	unset($_SESSION['asdf']);
    	echo "Email Sent!";
	} else {
	    debug($debug_mode, $log, "[Send Mail] No Products found, email skipped for: " . $vars['user_email']);
	}

    if($debug_mode)
        fclose($log);


/** HELPER FUNCTIONS **/
function debug($debug_mode, $log, $message) {
    if($debug_mode)
        fwrite($log, $message . "\n");

    echo $message . "<br>";
}

function getPreviousID(){
    $dbh = new mysqli("localhost", "ampers_cms_db_admin", "JKDlS*3klsd9sk", "ampers_cms413");
	if ($dbh->connect_error) {
		die("Connection failed: " . $dbh->connect_error);
	}
    
    $sorting_number = 0;
    $query =  "select * from tl_quote_request ORDER BY sorting desc LIMIT 1";
    $result = $dbh->query($query);
    if($result) {
        while($row = $result->fetch_assoc()) {
            $sorting_number = $row['sorting'];
        }
    }
    
    $sorting_number = $sorting_number + 1;
    
    return $sorting_number;
}

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

function getSquareFeet($width, $height){
	return getSquareInches($width, $height) / 144;
}

function getSquareInches($width, $height){
	return ($width * $height);
}
