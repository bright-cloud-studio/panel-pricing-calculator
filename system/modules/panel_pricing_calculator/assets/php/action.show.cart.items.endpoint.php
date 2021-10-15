<?php

	include_once("prepend.cart.endpoint.php");
	
	// Start our session
	session_start();
	
	$test = '';
	foreach ($_SESSION['asdf'] as $key => $result){
		$clean = unserialize($result);
		//$test = $test . "WIDTH: " . $clean['width'];
		
		//$ourQuote = array("panel_id"=>$vars['panel_id'], "flat_id"=>$vars['flat_id'], "cradle_id"=>$vars['cradle_id'], "width"=>$vars['width'], "height"=>$vars['height'], "quantity"=>$vars['quantity']);
		
		$test = $test . '<div id="cart_item_'.$key.'" class="cart_item form-group size100 flex_wrapper">
			<div class="size20 no-pad"><span id="cart_image cart_image_'.$key.'"><img src="system/modules/panel_pricing_calculator/assets/images/'.getPanelNameByID($clean['panel_id']).'.jpg"></span></div>
			<div class="size30">
				<div><strong id="cart_name_label">Panel: </strong><span>'.$clean['width'].'x'.$clean['height'].' '.getPanelNameByID($clean['panel_id']).', '.getPanelThicknessFromID($clean['flat_id']).'</span></div>
				<div><strong id="cart_cradle_wrapper">Cradle: </strong><span>'.getPanelCradleFromID($clean['cradle_id']).'</span></div>
			</div>
			<div class="size30">
				<div id="cart_quantity_wrapper"><strong id="quantity_label">Quantity: </strong><span">'.$clean['quantity'].'</span></div>
				<div id="cart_price_wrapper"><strong id="price_label">Price: </strong><span>'.$clean['price'].'</span></div>
			</div>
			<div class="size20 no-pad">
				<i class="fas fa-trash-alt" onclick="remove_from_cart('.$key.')"></i>
			</div>
	</div>';
		
	}
	
	echo $test;
	
	
	

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

	
?>
