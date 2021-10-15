<?php

// This is the main object and itss functions. Everything starts, besides the constructor, at processForm().
class QuoteCart
{
	public static $cart_cookie_name = 'ampersandart_cart';
	
	public function __construct()
	{
		$this->dbh = $dbh;
		$this->message = array();
		$this->errors = array();
		
		$this->servername = "localhost";
		$this->username = "ampersan_dbadmin";
		$this->password = "Y06ZCg9BiAh2Uv#@";
		$this->dbname = "ampersan_cms49";

	}

	////////////////////////////////////////////////////////
	// THIS IS THE FIRST FUNCTION CALLED FROM THE SCRIPTS //
	////////////////////////////////////////////////////////
	
	public function addToCart()
	{
		// turn the form values into $vars
		$vars = $_POST;

		//// validateForm just makes sure numbers are set
		//if ($this->validateForm($vars)) {
		//	$total_price = $this->calculatePrice($vars);
		//	return $this->formatPrice($total_price);
		//}            
		//return false;
		
		
		//$ourQuote = new Quote();
		//$ourQuote->set_panel_id($vars['panel_id']);
		//$ourQuote->set_panel_thickness_id($vars['flat_id']);
		//$ourQuote->set_panel_cradle_id($vars['cradle_id']);
		//$ourQuote->set_panel_width($vars['width']);
		//$ourQuote->set_panel_height($vars['height']);
		//$ourQuote->set_order_quantity($vars['quantity']);
		
		//array_push($_SESSION['asdf'],serialize($ourQuote));
		
		
		$ourQuote = array("panel_id"=>$vars['panel_id'], "flat_id"=>$vars['flat_id'], "cradle_id"=>$vars['cradle_id'], "width"=>$vars['width'], "height"=>$vars['height'], "quantity"=>$vars['quantity'], "price"=>$vars['price']);
		array_push($_SESSION['asdf'],serialize($ourQuote));
		
		return count($_SESSION['asdf']);
		//return $ourQuote->get_panel_id();
	}

}



class Quote {
	// Properties
	public $panel_id;
	public $panel_thickness_id;
	public $panel_cradle_id;
	public $panel_width;
	public $panel_height;
	public $order_quantity;
	public $order_price;

	// Methods
	function set_panel_id($panel_id) {
		$this->panel_id = $panel_id;
	}
	function get_panel_id() {
		return $this->panel_id;
	}
	
	function set_panel_thickness_id($panel_thickness_id) {
		$this->panel_thickness_id = $panel_thickness_id;
	}
	function get_panel_thickness_id() {
		return $this->panel_thickness_id;
	}
	
	function set_panel_cradle_id($panel_cradle_id) {
		$this->panel_cradle_id = $panel_cradle_id;
	}
	function get_panel_cradle_id() {
		return $this->panel_cradle_id;
	}
	
	function set_panel_width($panel_width) {
		$this->panel_width = $panel_width;
	}
	function get_panel_width() {
		return $this->panel_width;
	}
	
	function set_panel_height($panel_height) {
		$this->panel_height = $panel_height;
	}
	function get_panel_height() {
		return $this->panel_height;
	}
	
	function set_order_quantity($order_quantity) {
		$this->order_quantity = $order_quantity;
	}
	function get_order_quantity() {
		return $this->order_quantity;
	}
	function set_order_price($order_price) {
		$this->order_quantity = $order_price;
	}
	function get_order_price() {
		return $this->order_price;
	}
}

