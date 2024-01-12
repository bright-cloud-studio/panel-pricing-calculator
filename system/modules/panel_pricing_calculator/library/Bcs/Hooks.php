<?php

/*
 * Bright Cloud Studio's Panel Pricing Calculator
 * Copyright (C) 2023-2024 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/panel-pricing-calculato
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
*/

namespace Bcs;

use Contao\Database;

use Isotope\Interfaces\IsotopeProduct;
use Isotope\Isotope;

use Isotope\Model\Product;

class Hooks
{

    // Called when a form is submitted
    public function onProcessForm($submittedData, $formData, $files, $labels, $form)
    {
        
        // If this is our "Add to cart" button
        if($formData['formID'] == 'calc_add_to_cart') {
            
            /** Dev Stuffs
            echo "HOOK: Successful<br><br>";
            
            echo "<pre>";
            print_r($submittedData);
            echo "</pre>";
            
            echo "<pre>";
            print_r($formData);
            echo "</pre>";
            die();
            */
            
            
            // STEP ONE
            // Build our product query by getting the proper IDs for the selected options
            
            $parent_id = 1466;
            $custom_width = 238;
            $custom_height = 240;
            $custom_depth = 230;
            $custom_thickness = 236;
            
            $quantity = $submittedData['quantity'];

            // See if the product exists
            $prod = Product::findOneBy(['tl_iso_product.pid=?', 'tl_iso_product.custom_width=?', 'tl_iso_product.custom_height=?', 'tl_iso_product.custom_depth=?', 'tl_iso_product.custom_thickness=?'],[$parent_id, $custom_width, $custom_height, $custom_depth, $custom_thickness]);
            if($prod == null) {
                
                // Product does not exist, lets create it
                echo "NOPE";
                
            } else {
                
                // Product exists, add it to the cart
                $arrConfig = array();
                if (Isotope::getCart()->addProduct($prod, $quantity, $arrConfig) !== false)
					$blnAdded = true;
                
            }
                
        }
        
    }
  
}
