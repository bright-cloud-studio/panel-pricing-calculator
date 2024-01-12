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

class Hooks
{

    // Called when a form is submitted
    public function onProcessForm($submittedData, $formData, $files, $labels, $form)
    {
        
        // If this is our "Add to cart" button
        if($formData['formID'] == 'calc_add_to_cart') {
            
            /** Dev Stuffs */
            echo "HOOK: Successful<br><br>";
            echo "<pre>";
            print_r($submittedData);
            echo "</pre>";
            echo "<pre>";
            print_r($formData);
            echo "</pre>";
            die();

            
            
            // First, see if this product exists
            
                // If exists, add to cart
                
                // If doesnt exist, create the variant
                    // Add to cart
                    
            // Return back to the page
            
            
            
        }
        
    }
  
}
