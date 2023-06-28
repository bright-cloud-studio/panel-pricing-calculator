<?php

// This is the main object and itss functions. Everything starts, besides the constructor, at processForm().
class QuoteCart
{
	public static $cart_cookie_name = 'ampersandart_cart';
	
	public function __construct($dbh)
	{
		$this->dbh = $dbh;
		$this->message = array();
		$this->errors = array();
		
		$this->servername = "localhost";
		$this->username = "ampers_cms_db_admin";
		$this->password = "JKDlS*3klsd9sk";
		$this->dbname = "ampers_cms413";

	}

	////////////////////////////////////////////////////////
	// THIS IS THE FIRST FUNCTION CALLED FROM THE SCRIPTS //
	////////////////////////////////////////////////////////
	
	public function addToCart()
	{
		// turn the form values into $vars
		$vars = $_POST;
		
		// create an reference array filled with our passed in values
		$ourQuote = array("panel_id"=>$vars['panel_id'], "flat_id"=>$vars['flat_id'], "cradle_id"=>$vars['cradle_id'], "width"=>$vars['width'], "height"=>$vars['height'], "quantity"=>$vars['quantity'], "price"=>$vars['price']);
		// add a serialized copy of our array into our session array
		array_push($_SESSION['asdf'],serialize($ourQuote));
		// return the total count of entries in our session array
		return count($_SESSION['asdf']);
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
