<?php

// This is the main object and itss functions. Everything starts, besides the constructor, at processForm().
class PanelCalculator
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
	public function insertData($query)
	{
		$result = $this->dbh->prepare($query);
		$result->execute();
		return $this->dbh->lastInsertId();
	}
    
	///////////////////////////////////////////////////////////////
	// GETS - Grabs information from the database for everything //
	///////////////////////////////////////////////////////////////
	
	public function getPanelNameById($id)
	{
		$data = array();
		$query = "select * from six_panel where id=".$id.' LIMIT 1';
		$result = $this->dbh->prepare($query);
		$result->execute();
		$row = $result->fetch();
		return $row['panel_name'];
	}
	public function getPanelTypeNameById($id)
	{
		$data = array();
		$query = "select * from six_panel_type where id=".$id.' LIMIT 1';
		$result = $this->dbh->prepare($query);
		$result->execute();
		$row = $result->fetch();
		return $row['panel_type_name'];
	}
	public function getPanels()
	{
		$data = array();
		$query = "select * from six_panel where id<>8";
		$result = $this->dbh->prepare($query);
		$result->execute();
		while ($row = $result->fetch()) {
			$data[$row['id']] = $row;
		}
		return $data;
	}
	public function getFlats()
	{
		$data = array();
		$query = "select * from six_panel_type WHERE type='flat'";
		$result = $this->dbh->prepare($query);
		$result->execute();
		while ($row = $result->fetch()) {
			$data[$row['id']] = $row;
		}
		return $data;
	}
	public function getCradles()
	{
		$data = array();
		$query = "select * from six_panel_type WHERE type='cradle'";
		$result = $this->dbh->prepare($query);
		$result->execute();
		while ($row = $result->fetch()) {
			$data[$row['id']] = $row;
		}
		return $data;
	}
    
	////////////////////////////////////////////////////////
	// THIS IS THE FIRST FUNCTION CALLED FROM THE SCRIPTS //
	////////////////////////////////////////////////////////
	
	public function processForm()
	{
		// turn the form values into $vars
		$vars = $_POST;

		// validateForm just makes sure numbers are set
		if ($this->validateForm($vars)) {
			$total_price = $this->calculatePrice($vars);
			return $this->formatPrice($total_price);
		}            
		return 99999;
	}
    
	public function calculatePrice($vars)
	{
		// calculate square feet. If 6 or less run SixRate, if more than run MoreSixRate
		$square_feet = $this->getSquareFeet($vars['width'], $vars['height']);
		if ($square_feet <=6) {
			return $this->getSixRate($vars['panel_id'], $vars['flat_id'], $vars['cradle_id'], $square_feet, $vars['quantity'], $vars['width'], $vars['height']);
		} else {
			return $this->getMoreSixRate($vars['panel_id'], $vars['flat_id'], $vars['cradle_id'], $square_feet, $vars['quantity'], $vars['width'], $vars['height']);
		}
	}

	// For Square Feet 6 or under
	public function getSixRate($panel_id, $flat_id, $cradle_id, $square_feet, $quantity, $width, $height)
	{
		
		// Create connection
		$dbh = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		if ($dbh->connect_error) {
			die("Connection failed: " . $dbh->connect_error);
		}

		
		// values to build our query
		$price = '9999';
		$product_type = 'unselected';
		$thicknessPlusPercentage = 0;
		$cradlePlusPercentage = 0;
		
		
		// if uncradled, otherwise use 3/4 sizes
		if($cradle_id == 'none')
		{
			// set which price field to grab based on which panel is selected
			if($panel_id == 1 || $panel_id == 2 || $panel_id == 3 || $panel_id == 4)
				$product_type = '1_8_1';
			else if($panel_id == 5 || $panel_id == 6)
				$product_type = '1_8_2';
			else if($panel_id == 7)
				$product_type = '1_8_3';
		} else {
			if($panel_id == 7)
				$product_type = '3_4_1';
			else
				$product_type = '3_4_2';
		}
		
		// add 15% if thickness is 1/4
		if($flat_id == 2)
			$thicknessPlusPercentage = 15;
		
		// add 20% if cradle is 1" through 2"
		if($cradle_id == 4 || $cradle_id == 5 || $cradle_id == 6)
			$cradlePlusPercentage = 20;
		else if($cradle_id == 7 || $cradle_id == 8)
			$cradlePlusPercentage = 25;
		
		
		// STEPS
		
		// 1. Get price from nearest size
		$query =  "select * from tl_price_chart WHERE square_feet >= ".$square_feet." ORDER BY square_feet ASC LIMIT 1";
		$result = $dbh->query($query);
		if($result) {
			
			while($row = $result->fetch_assoc()) {
				$price = $row[$product_type];
			}
		} else {
			return "Something has gone wrong! ".$sql->errorno;
		}
		
		
		// add our thickness plus percentage
		$price = $price + ($price * ($thicknessPlusPercentage/100));
		
		// add our cradle plus percentage
		$price = $price + ($price * ($cradlePlusPercentage/100));
		
		// add 20% for lengths of 72 or higher
		if($height >= 72)
			$price = $price + ($price * (20/100));

		// multiply the price based on the quantity
		
		$individual_price = $price;
		$price = $price * $quantity;
		
		
		// add a quantity discount
		$quantity_discount = 0;
		if($quantity >= 10 && $quantity <= 24)
			$quantity_discount = 0.15;
		if($quantity >= 25 && $quantity <= 49)
			$quantity_discount = 0.20;
		if($quantity >= 50 && $quantity <= 99)
			$quantity_discount = 0.25;
		if($quantity >= 100 && $quantity <= 249)
			$quantity_discount = 0.30;
		if($quantity >= 250 && $quantity <= 499)
			$quantity_discount = 0.40;
		if($quantity >= 500)
			$quantity_discount = 0.50;
		
		
		$price = $price - ($price * $quantity_discount);
		
		
		// if we see 9999 that means this function finished but we didnt get back what we wanted from it
		return $individual_price;
		return $price;
	}
	
	 // For Squre Feet 6 or more
    public function getMoreSixRate($panel_id, $flat_id, $cradle_id, $square_feet, $quantity, $width, $height)
    {
        // Create connection
		$dbh = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		if ($dbh->connect_error) {
			die("Connection failed: " . $dbh->connect_error);
		}

		// values to build our query
		$price = '1234';
		$price_total = '0';
		$product_type = '1_2';
		$thickness = "1/8";
		$thicknessPlusPercentage = 0;
		$cradlePlusPercentage = 0;
		$db_price = 0;
		$debug_panel_price = 0;
		
		// debug - database price
		$d_db_price = 0;
		// debug - square foot price total
		$d_sf_price = 0;
		// debug - price after qualtity discount
		$d_after_quantity_price = 0;
		// debug - price per square inch
		$d_square_inch_price = 0;
		// debug - cradle price
		$d_cradle_price = 0;
		
		
		if($panel_id == 1 || $panel_id == 2 || $panel_id == 3 || $panel_id == 4)
			$product_type = '1_1';
			
			
		if($panel_id == 5 || $panel_id == 6)
			$product_type = '1_3';
			
		if($panel_id == 7)
			$product_type == '1_2';
		
		
		if($flat_id == 1)
			$thickness = "1/8";
		if($flat_id == 2)
			$thickness = "1/4";
		
			
		// STEPS
		
		// 1. Get price from nearest size
		$query =  "select * from tl_price_chart_large WHERE width = '".$thickness."'";
		$result = $dbh->query($query);
		if($result) {
			
			while($row = $result->fetch_assoc()) {
				$price = $row[$product_type];
				$db_price = $row[$product_type];
				$d_db_price = $row[$product_type];
			}
		} else {
			return "Something has gone wrong! ".$sql->errorno;
		}
		
		// multiply the price based on the quantity
		$price = $price * $square_feet;
		
		$d_sf_price = $price;
		
		// add a quantity discount
		$quantity_discount = 0.10;
		if($quantity == 2)
			$quantity_discount = 0.20;
		if($quantity == 3)
			$quantity_discount = 0.30;
		if($quantity >= 4 && $quantity <= 9)
			$quantity_discount = 0.40;
		if($quantity >= 10)
			$quantity_discount = 0.50;
			
		$price = $price - ($price * $quantity_discount);
		
		$d_after_quantity_price = $price;
	
		
		// DETERMINE THE CRADLE PRICE
		
		// figure out the every X inch size
		$every_x_inches = 12.01;
		if( $cradle_id == 5 || $cradle_id == 6 || $cradle_id == 7 || $cradle_id == 8 )
			$every_x_inches = 24.01;
			
		// figure out the price per linear inch price
		$price_per_inch = 0;
		
		
		/*
		if($cradle_id == 3)
			$price_per_inch = 0.44;
		if($cradle_id == 4)
			$price_per_inch = 0.50;
		if($cradle_id == 5)
			$price_per_inch = 0.52;
		if($cradle_id == 6)
			$price_per_inch = 0.55;
		if($cradle_id == 7)
			$price_per_inch = 0.58;
		if($cradle_id == 8)
			$price_per_inch = 0.60;
		*/
		
		
		$cradle_table = '';
		switch($cradle_id) {
			case 3:
				$cradle_table = "3_4_inch";
				break;
			case 4:
				$cradle_table = "1_inch";
				break;
			case 5:
				$cradle_table = "1_5_inch";
				break;
			case 6:
				$cradle_table = "2_inch";
				break;
			case 7:
				$cradle_table = "2_5_inch";
				break;
			case 8:
				$cradle_table = "3_inch";
				break;
		}
		
		
		$query =  "select * from tl_cradle_prices";
		$result = $dbh->query($query);
		if($result) {
			
			while($row = $result->fetch_assoc()) {
				$price_per_inch = $row[$cradle_table];
			}
		} else {
			return "Something has gone wrong! ".$sql->errorno;
		}
		
		//return $price_per_inch;
		$d_square_inch_price = $price_per_inch;
			
		// step one is divide width by every_x and divide height by every_y;
		$cross_width = ceil($width / $every_x_inches);
		$cross_height = ceil($height / $every_x_inches);
		
		$linear_inch_width = ($cross_width + 1) * $height;
		$linear_inch_height = ($cross_height + 1) * $width;
		
		$cradle_price = ($linear_inch_width + $linear_inch_height) * $price_per_inch;
		
		$d_cradle_price = $cradle_price;
		
		// if we have a cradle selected, take 15% off the panel
		if($cradle_id == 3 || $cradle_id == 4 || $cradle_id == 5 || $cradle_id == 6 || $cradle_id == 7 || $cradle_id == 8)
			$cradle_price = $cradle_price - ($cradle_price * 0.15);
		
		
		
		
		// cradle price total is price multiplied by how many
		//$cradle_price = $cradle_price * $quantity;
		
		// price total is the price multiplied by how many
		//$price_total = $price * $quantity;
		
		
		$price_total = $price + $cradle_price;
		
		//if 48" add ten percent;
		if($width == 48)
			$price_total = $price_total + ($price_total * 0.10);
			
			
		// multiply by quantity
		
		$individual_price = $price_total;
		$price_total = $price_total * $quantity;
		
		// add the cradle price onto the total
		//$price_total = $price_total + $cradle_price;
		
		// if we see 9999 that means this function finished but we didnt get back what we wanted from it
		//return $price_total;
		//return $db_price;
		
		// Debug - send email with values
		//$this->pushDebugMessage($d_db_price, $d_sf_price, $d_after_quantity_price, $d_square_inch_price, $d_cradle_price);
		
		return $individual_price;
		return $price_total;
		//return $price_per_inch;
		
		
		
    }
	
    
     public function getStandardSize($square_feet)
    {
        $query = "select * from six_standard_size WHERE size_val >= ".$square_feet." ORDER BY size_val ASC LIMIT 1";
        $result = $this->dbh->prepare($query);
        $result->execute();
        $row = $result->fetch();
        return $row;
    }
    
    public function getPriceSixJunction($panel_id, $panel_type_id, $standard_size_id)
    {
        $query = "select price from six_junction WHERE panel_id=".$panel_id." AND panel_type_id=".$panel_type_id." AND standard_size_id = ".$standard_size_id.' LIMIT 1';
        $result = $this->dbh->prepare($query);
        $result->execute();
        $row = $result->fetch();
        return $row['price'];
    }
    
    public function getPercentageByFlatId($flat_id){
        $query = "select * from six_panel_type WHERE id=".$flat_id. " and type='flat' LIMIT 1";
        $result = $this->dbh->prepare($query);
        $result->execute();
        $row = $result->fetch();
        return $row['plus_percentage'];
    }
    
     public function sixDiscount($quantity)
    {
        if ($quantity>0) {
            $query = "select * from six_quantity WHERE min <=".$quantity." AND max>=".$quantity."  LIMIT 1";
            $result = $this->dbh->prepare($query);
            $result->execute();
            $row = $result->fetch();
            if ($row['percent']>0) {
                return $row['percent'];
            }
        }
        return false;
    }
    
   
    
	// Large Panel Quantity Discount
	// this applies a percentage off discount for large custom panels based on the quantity of the order
	public function moreDiscount($quantity)
	{
		if ($quantity == 1) {
			return 10;
        	}
		if ($quantity == 2) {
			return 20;
		}
		if ($quantity == 3) {
			return 30;
		}
		if ($quantity >= 4 && $quantity < 10) {
			return 40;
		}
		if ($quantity >= 10) {
			return 50;
		}
		return false;
	}
    
	public function formatPrice($total_price)
	{
		return round($total_price, 2);
	}
    
	public function getSquareFeet($width, $height)
	{
		$square_inches = $this->getSquareInches($width, $height);
		return round(($square_inches / 144), 4);
	}

	public function getSquareInches($width, $height)
	{
		return ($width * $height);
	}
    
	///////////////////////////////////////
	// SESSIONS, COOKIES AND CART STUFFS //
	///////////////////////////////////////
    
	public function pushDebugMessage($d_db_price, $d_sf_price, $d_after_quantity_price, $d_square_inch_price, $d_cradle_price)
    {
    	$to = 'mark@brightcloudstudio.com';
		$subject = "Debug Email";

		$message_start = "
			<html>
			<head>
			<title>Debug Email</title>
			</head>
			<body>
			";
	
		$message_debug_contents = "Database price: " . $d_db_price . "<br>Square Foot Price: " . $d_sf_price . "<br>After Quantity Price: " . $d_after_quantity_price . "<br>Square Inch Price: " . $d_square_inch_price . "<br>Cradle Price: " . $d_cradle_price;
		
		$message_end = "
			</body>
			</html>
			";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <mark@brightcloudstudio.com>' . "\r\n";
		$headers .= 'Cc: <mark@brightcloudstudio.com>' . "\r\n";
	

		// Add our custom intro text
		$message_user_contents = $message_user_contents . '<p>Thank you for your Ampersand Art Supply custom quote request.</p>';
   		mail($to,$subject,($message_start . $message_debug_contents . $message_end),$headers);
   		
   		return true;
    }
    
	public function generateFormToken($form)
	{
		// generate a token from an unique value
		$token = md5(uniqid(microtime(), true));
        
		// Write the generated token to the session variable to check it against the hidden field when the form is sent
		$_SESSION[$form.'_token'] = $token;
        
		return $token;
	}

	public function verifyFormToken($form)
	{
		// check if a session is started and a token is transmitted, if not return an error
		if (!isset($_SESSION[$form.'_token'])) {
			return false;
		}
    
		// check if the form is sent with token in it
		if (!isset($_POST['token'])) {
			return false;
		}
    
		// compare the tokens against each other if they are still the same
		if ($_SESSION[$form.'_token'] !== $_POST['token']) {
			return false;
		}
    
		return true;
	}

    public function processOrderForm()
    {
        if ($this->verifyFormToken('form3')) {
            return $this->saveOrder();
        } else {
            echo "Session expired. Please refresh the page.";
            exit;
        }
        return false;
    }

    public function getCartItems() {
        $data = array();
        if(isset($_COOKIE[self::$cart_cookie_name])) {
            $uuid = $_COOKIE[self::$cart_cookie_name];
            $sth = $this->dbh->prepare('SELECT e.*,c.id as cart_id, c.uuid, p.panel_name, pt.panel_type_name as panel_type, cr.panel_type_name as cradle, e.quoted_price    
                    FROM cart as c 
                    INNER JOIN enquiry as e on c.enquiry_id = e.id 
                    LEFT JOIN six_panel as p on e.panel_id = p.id 
                    LEFT JOIN six_panel_type as pt on e.flat_id = pt.id 
                    LEFT JOIN six_panel_type as cr on e.cradle_id = cr.id 
                    WHERE uuid = ? ');
            $sth->bindParam(1, $uuid, PDO::PARAM_STR);
            $sth->execute();
            while ($row = $sth->fetch()) {
                $data[$row['id']] = $row;
            }
        }
        return $data;
    }

    public function getOrderItems($order_id) {
        $data = array();
        if(is_numeric($order_id)) {
            $sth = $this->dbh->prepare('SELECT * FROM order_items WHERE order_id = ? ');
            $sth->bindParam(1, $order_id, PDO::PARAM_INT);
            $sth->execute();
            while ($row = $sth->fetch()) {
                $data[$row['id']] = $row;
            }
        }
        return $data;
    }

    public function addToCart()
    {
        if ($this->verifyFormToken('form2')) {
            if (is_numeric($_POST['enquiry_id'])) {
                $vars = $this->getEnquiryById($_POST['enquiry_id']);
                return $this->saveAddToCart($vars);
            }
        } else {
            echo "Session expired. Please refresh the page.";
            exit;
        }
        return false;
    }
    
    public function isLoggedIn()
    {
        return false;
    }

	//////////////////////
	// NOT USED ANYMORE //
	//////////////////////
	
    public function calculateOrderTotal($items)
    {
        $total = 0;
        foreach ($items as $key => $item) {
            $total += $item['quoted_price'];
        }
        return $total;
    }

    public function getAdminEmail()
    {
        $data = array();
        $query = "select * from admin_email";
        $result = $this->dbh->prepare($query);
        $result->execute();
        while ($row = $result->fetch()) {
            if (filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                $data[] = $row['email'];
            }
        }
        if (count($data)) {
            return $data;
        } else {
            return false;
        }
    }
    
    ////////////////////////
    // VALIDATE FUNCTIONS //
    ////////////////////////
    
    public function validateForm($vars)
    {
	if ($vars['panel_id'] == '' or !is_numeric($vars['panel_id'])) {
            $this->errors[] = "Choose panel";
        }
        if ($vars['cradle_id'] != 'none') {
            if ($vars['cradle_id'] == 0) {
            } else {
                //$this->errors[] = "Invalid cradle";
            }
        }
        if (!is_numeric($vars['width'])) {
            $this->errors[] = "Invalid width";
        }
        if (!is_numeric($vars['height'])) {
            $this->errors[] = "Invalid height";
        }
        if (!is_numeric($vars['quantity'])) {
            $this->errors[] = "Invalid quantity";
        }
        if (count($this->errors)) {
            return false;
        }
        return true;
    }

}
