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
use Isotope\Model\Attribute;
use Isotope\Model\AttributeOption;

use Isotope\Model\Product;

class Hooks
{

    // Called when a form is submitted
    public function onProcessForm($submittedData, $formData, $files, $labels, $form)
    {
        
        // If this is our "Add to cart" button
        if($formData['formID'] == 'calc_add_to_cart') {
            
            // STEP ONE
            // First we will gather all of our IDs based on the submitted form so that we can query Isotope and see if this product exists or not
            
            $parent_id = $this->getProductTypeID($submittedData['panel_id']);
            $attribute_id_custom_width = Attribute::findOneBy(['tl_iso_attribute.field_name=?'],["custom_width"])->id;
            $attribute_id_custom_height = Attribute::findOneBy(['tl_iso_attribute.field_name=?'],["custom_height"])->id;
            $attribute_id_custom_depth = Attribute::findOneBy(['tl_iso_attribute.field_name=?'],["custom_depth"])->id;
            $attribute_id_custom_thickness = Attribute::findOneBy(['tl_iso_attribute.field_name=?'],["custom_thickness"])->id;
            $custom_width = AttributeOption::findOneBy(['tl_iso_attribute_option.pid=?', 'tl_iso_attribute_option.label=?'],[$attribute_id_custom_width, $submittedData['width'] . "&#34;"])->id;
            $custom_height = AttributeOption::findOneBy(['tl_iso_attribute_option.pid=?', 'tl_iso_attribute_option.label=?'],[$attribute_id_custom_height, $submittedData['height'] . "&#34;"])->id;
            $custom_depth = AttributeOption::findOneBy(['tl_iso_attribute_option.pid=?', 'tl_iso_attribute_option.label=?'],[$attribute_id_custom_depth, $this->getCradleDepthByID($submittedData['cradle_id'])])->id;
            $custom_thickness = AttributeOption::findOneBy(['tl_iso_attribute_option.pid=?', 'tl_iso_attribute_option.label=?'],[$attribute_id_custom_thickness, $this->getPanelThicknessByID($submittedData['flat_id'])])->id;
            $quantity = $submittedData['quantity'];


            // STEP TWO
            // Using our collected IDs we will see if this product exists or not
            $prod = Product::findOneBy(['tl_iso_product.pid=?', 'tl_iso_product.custom_width=?', 'tl_iso_product.custom_height=?', 'tl_iso_product.custom_depth=?', 'tl_iso_product.custom_thickness=?'],[$parent_id, $custom_width, $custom_height, $custom_depth, $custom_thickness]);
            if($prod == null) {
                
                // This product does not exist! Let's create it.
                
                // If the custom width doesn't exist yet, make it and save the id
                if(!$custom_width) {
                    $attr = new AttributeOption();
                    $attr->pid = $attribute_id_custom_width;
                    $attr->sorting = 1000 * $submittedData['width'];
                    $attr->tstamp = time();
                    $attr->ptable = 'tl_iso_attribute';
                    $attr->type = 'option';
                    $attr->label = $submittedData['width'] . "&#34;";
                    $attr->published = 1;
                    $attr->save();
                    
                    $custom_width = $attr->id;
                }
                // If the custom height doesn't exist yet, make it and save the id
                if(!$custom_height) {
                    $attr = new AttributeOption();
                    $attr->pid = $attribute_id_custom_height;
                    $attr->sorting = 1000 * $submittedData['height'];
                    $attr->tstamp = time();
                    $attr->ptable = 'tl_iso_attribute';
                    $attr->type = 'option';
                    $attr->label = $submittedData['height'] . "&#34;";
                    $attr->published = 1;
                    $attr->save();
                    
                    $custom_height = $attr->id;
                }
                
                // Insert these IDs into 'tl_iso_product'
                //$prod = new Product();
                //$prod->pid = $parent_id;
                
                //$prod->custom_width = $custom_width;
                //$prod->custom_height = $custom_height;
                //$prod->custom_depth = $custom_depth;
                //$prod->custom_thickness = $custom_thickness;
                
                // SKU = panel_id _ panel_thickness _ panel_depth _ custom_width _ custom_height
                //$prod->sku = "1" + "_" + "1" + "_" + "2";
                
                //$prod->tstamp = time();
                //$prod->published = 1;
                //$prod->save();
                
                $newProd = array();
                $newProd['pid'] = $parent_id;
                $newProd['sku'] = $submittedData['panel_id'] . "_" . $submittedData['flat_id'] . "_" . $submittedData['cradle_id'] . "_" . $submittedData['width'] . "_" . $submittedData['height'];
                $newProd['tstamp'] = time();
                $newProd['custom_width'] = $custom_width;
                $newProd['custom_height'] = $custom_height;
                $newProd['custom_depth'] = $custom_depth;
                $newProd['custom_thickness'] = $custom_thickness;
                $newProd['published'] = 1;
                // Add our newly created product to the cart
                $result = \Database::getInstance()->prepare("INSERT INTO tl_iso_product %s")
                                                 ->set($newProd)
                                                 ->execute();
                                                         
                // Second, create entry in the 'tl_product_price' table                    
                $price = array();
                $price['pid'] = $result->insertId;
                $price['tstamp'] = time();
                $price['tax_class'] = 1;
                $price['config_id'] = 0;
                $price['member_group'] = 0;
                $priceResult = \Database::getInstance()->prepare("INSERT INTO tl_iso_product_price %s")
                                 ->set($price)
                                 ->execute();                                           

                
                // First, create entry in the 'tl_product_pricetier" table
                $priceTier = array();
                $priceTier['pid'] = $priceResult->insertId;
                $priceTier['tstamp'] = time();
                $priceTier['min'] = 1;
                $priceTier['price'] = $submittedData['price'];
                $priceTierResult = \Database::getInstance()->prepare("INSERT INTO tl_iso_product_pricetier %s")
                                 ->set($priceTier)
                                 ->execute();


                $prod = Product::findOneBy(['tl_iso_product.pid=?', 'tl_iso_product.custom_width=?', 'tl_iso_product.custom_height=?', 'tl_iso_product.custom_depth=?', 'tl_iso_product.custom_thickness=?'],[$parent_id, $custom_width, $custom_height, $custom_depth, $custom_thickness]);
                $arrConfig = array();
                if (Isotope::getCart()->addProduct($prod, $quantity, $arrConfig) !== false)
					$blnAdded = true;
                
                
                //echo "custom_width: " . $custom_width . "<br>";
                //echo "custom_height: " . $custom_height . "<br>";
                //echo "custom_depth: " . $custom_depth . "<br>";
                //echo "custom_thickness: " . $custom_thickness . "<br>";
                //die();
                
            } else {
                
                // This product exists! Add it to the cart with the quantity we need
                $arrConfig = array();
                if (Isotope::getCart()->addProduct($prod, $quantity, $arrConfig) !== false)
					$blnAdded = true;
            }

        }
        
    }
    
    
    // Pass in the ID from the form's "Panel name" select and get back the ID of that product type
    private function getProductTypeID($select_id) {
        switch ($select_id) {
            // Claybord
            case 1:
                return 1466;
                break;
            // Gessobord
            case 2:
                return -1;
                break;
            // Aquabord
            case 3:
                return -1;
                break;
            // Encausticbord
            case 4:
                return -1;
                break;
            // Scratchbord
            case 5:
                return -1;
                break;
            // Pastelbord
            case 6:
                return -1;
                break;
            // Hardbord
            case 7:
                return -1;
                break;
            default:
                return -1;
        }
    }
    
    // Take the ID and get back the isotope ID we need here
    private function getCradleDepthByID($select_id) {
        switch ($select_id) {
            // Claybord
            case "none":
                return "None";
                break;
            // Gessobord
            case 3:
                return "3/4&#34; Cradled";
                break;
            // Aquabord
            case 4:
                return "1&#34; Cradled";
                break;
            // Encausticbord
            case 5:
                return "1 1/2&#34; Cradled";
                break;
            // Scratchbord
            case 6:
                return "2&#34; Cradled";
                break;
            // Pastelbord
            case 7:
                return "2 1/2&#34; Cradled";
                break;
            // Hardbord
            case 8:
                return "3&#34; Cradled";
                break;
            default:
                return -1;
        }
    }
    
    // Take the ID and get back the isotope ID we need here
    private function getPanelThicknessByID($select_id) {
        switch ($select_id) {
            // 1/8"
            case 1:
                return "1/8&#34;";
                break;
            // 1/4"
            case 2:
                return "1/4&#34;";
                break;
            default:
                return -1;
        }
    }
    
}
