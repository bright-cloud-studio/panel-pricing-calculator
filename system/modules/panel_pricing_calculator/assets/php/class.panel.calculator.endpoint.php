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
        return false;
    }
    
    public function calculatePrice($vars)
    {
    	// calculate square feet. If 6 or less run SixRate, if more than run MoreSixRate
        $square_feet = $this->getSquareFeet($vars['width'], $vars['height']);
        if ($square_feet <=6) {
            return $this->getSixRate($vars['panel_id'], $vars['flat_id'], $vars['cradle_id'], $square_feet, $vars['quantity'], $vars['width'], $vars['height']);
        } else {
            $price = $this->getMoreSixRate($vars, $square_feet);
            return $price;
        }
    }

    public function getSixRate($panel_id, $flat_id, $cradle_id, $square_feet, $quantity, $width, $height)
    {
    	// create a new array to store data
        $data = array();
        
        // if a cradle as been picked then set the panel type id to that selection, else set to the flat id
        if ($cradle_id > 0) {
            $panel_type_id = $cradle_id;
        } else {
            $panel_type_id = $flat_id;
        }
        
        // "id" field from "six_standard_size" based on "size_val"
        $standard_size = $this->getStandardSize($square_feet);
        $standard_size_id = $standard_size['id'];
        
        // if we have a valid id
        if (isset($standard_size_id)) {
            if (is_numeric($panel_id) && is_numeric($panel_type_id) && is_numeric($standard_size_id)) {
                
                // "price" from "six_junction" based on every other id
                $rate = $this->getPriceSixJunction($panel_id, $panel_type_id, $standard_size_id);
                // "plus_percentage" from "six_panel_type"
                $plusPercentage = $this->getPercentageByFlatId($flat_id);
                
                if($plusPercentage > 0){
                    $rate = $rate + ($rate * ($plusPercentage/100));
                }
                
                if ($width > 72 || $height > 72) {
                    $rate = $rate + ((20/100) * $rate);
                }
                
                // set rate in our message array
                $this->message['rate'] = $rate;

				// "percent" from "six_quantity"
                $discount_percent = $this->sixDiscount($quantity);
                
                // if we have a discount
                if ($discount_percent) {
                	
                    $discount_amount = (($discount_percent/100)*$rate);
                    $price = $rate - $discount_amount;
                    $total_discount_amount = $discount_amount*$quantity;
                    $total_price = ($quantity*$price);

                    $this->message['discount_percent'] = $discount_percent;
                    $this->message['discount_amount'] = $discount_amount;
                    $this->message['total_discount_amount'] = $total_discount_amount;
                    return $total_price;
                }
                $total_price = ($quantity*$rate);
                return $total_price;
            }
        }
        return false;
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
    
    public function getMoreSixRate($vars, $square_feet)
    {
        $data = array();
        $query = "select price from more_junction WHERE panel_id=".$vars['panel_id']." AND panel_type_id=".$vars['flat_id']. " LIMIT 1";
        $result = $this->dbh->prepare($query);
        $result->execute();
        $row = $result->fetch();
        $panel_price = $row['price'];
        $flat_price = ($panel_price * $square_feet);

        if ($vars['cradle_id'] > 0) {
            //perimeter
            $perimeter = ($vars['height'] * 2) + ($vars['width'] * 2);
            $query = "select * from six_panel_type WHERE id=".$vars['cradle_id']. " LIMIT 1";
            $result = $this->dbh->prepare($query);
            $result->execute();
            $row = $result->fetch();

            //price per inch
            $price_per_inch = $row['more_price_per_inch'];

            //cross bracing
            $length_cross_piece = 0;
            $width_cross_piece = 0;

            if ($vars['width'] > $row['more_cross_bracing']) {
                $c = $vars['width']/$row['more_cross_bracing'];
                
                $w = floor($c);
                //echo '<br>';
                $length_cross_piece = $w * $vars['height'];
                //echo '<br>';
                $this->message['length_cross_piece'] = $w.', '.$vars['height'].'" length cross brace';
            }

            if ($vars['height'] > $row['more_cross_bracing']) {
                $c = $vars['height']/$row['more_cross_bracing'];
                $w = floor($c);
                //echo '<br>';
                $width_cross_piece = $w * $vars['width'];
                //echo '<br>';
                $this->message['width_cross_piece'] = $w.', '.$vars['width'].'" width cross piece';
            }

            $cross_bracing_price = ($length_cross_piece + $width_cross_piece)*$price_per_inch;

            $cradle_price = $perimeter*$price_per_inch;

            $cradle_cross_bracing_discount = (15/100) * ($cross_bracing_price + $cradle_price);

            $cradle_cross_bracing_price = ($cradle_price + $cross_bracing_price) - $cradle_cross_bracing_discount;

            $more_price = $flat_price + $cradle_cross_bracing_price;
            $this->message['price_per_inch'] = $price_per_inch;
            $this->message['flat_price'] = $flat_price;
            $this->message['cradle_price'] = $cradle_price;
            $this->message['cross_bracing_price'] = $cross_bracing_price;
            $discount_percent = $this->moreDiscount($vars['quantity']);
            $this->message['total_discount_amount'] = 0;
            if ($discount_percent) {
                $this->message['discount_percent'] = $discount_percent;
                $discount_amount = (($discount_percent/100)*$flat_price);
                $this->message['discount_amount'] = $discount_amount;
                $price = $more_price - $discount_amount;
                $this->message['total_discount_amount'] = $vars['quantity']*$discount_amount;
                if ($vars['width'] == 48) {
                    $price = $price + ($price / 10);
                }
                return ($vars['quantity']*$price);
            } else {
                if ($vars['width'] == 48) {
                    $more_price = $more_price + ($more_price / 10);
                }
                return ($vars['quantity']*$more_price);
            }
        } else {
            $discount_percent = $this->moreDiscount($vars['quantity']);
            if ($discount_percent) {
                $discount_amount = (($discount_percent/100)*$flat_price);
                $this->message['discount_amount'] = $discount_amount;
                $price = $flat_price - $discount_amount;
                $this->message['total_discount_amount'] = $vars['quantity']*$discount_amount;
                if ($vars['width'] == 48) {
                    $price = $price + ($price / 10);
                }
                return ($vars['quantity']*$price);
            } else {
                if ($vars['width'] == 48) {
                    $flat_price = $flat_price + ($flat_price / 10);
                }
            }
            return ($vars['quantity']*$flat_price);
        }
        return false;
    }
    
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
	if ($vars['panel_id'] =='' or !is_numeric($vars['panel_id'])) {
            $this->errors[] = "Choose panel";
        }
        if (!is_numeric($vars['cradle_id'])) {
            if ($vars['cradle_id'] == 0) {
            } else {
                $this->errors[] = "Invalid cradle";
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
