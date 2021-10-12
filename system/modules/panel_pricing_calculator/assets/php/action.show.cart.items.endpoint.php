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
			<div class="size20 no-pad"><img src="system/modules/panel_pricing_calculator/assets/images/claybord.jpg"></div>
			<div class="size30">
				<div><strong id="cart_name_label">Panel: </strong><span>'.$clean['panel_id'].'</span></div>
				<div><strong id="cart_thickness_label">Thickness: </strong><span>'.$clean['flat_id'].'</span></div>
				<div><strong id="cart_cradle_wrapper">Cradle: </strong><span>'.$clean['cradle_id'].'</span></div>
				<div><strong id="cart_width_label">Width: </strong><span>'.$clean['width'].'</span></div>
				<div><strong id="cart_height_label">Height: </strong><span>'.$clean['height'].'</span></div>
			</div>
			<div class="size30">
				<div id="cart_quantity_wrapper"><strong id="quantity_label">Quantity: </strong><span">'.$clean['quantity'].'</span></div>
				<div id="cart_price_wrapper"><strong id="price_label">Price: </strong><span>1234</span></div>
			</div>
			<div class="size20 no-pad">
				<i class="fas fa-trash-alt" onclick="remove_from_cart('.$key.')"></i>
			</div>
	</div>';
		
	}
	
	echo $test;
?>
