<?php

/**
 * Panel Pricing Calculator - Adds a panel pricing calculator with a front end module for users and a back end system for managing the data.
 *
 * Copyright (C) 2021 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/panel-pricing-calculator
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/* Front end modules */
$GLOBALS['FE_MOD']['panel_pricing_calculator']['mod_panel_pricing_calculator'] 	= 'Bcs\Module\PanelPricingCalculatorModule';


/* Back end modules */

// DECLARING LOCATIONS BACK END PLUGIN
if (!is_array($GLOBALS['BE_MOD']['panel_pricing_calculator']))
{
    array_insert($GLOBALS['BE_MOD'], 1, array('panel_pricing_calculator' => array()));
}


// This sets up our custom section "PANEL PRICING CALCULATOR"
$GLOBALS['TL_LANG']['MOD']['panel_pricing_calculator'][0] = "Panel Pricing Calculator";
// Adds "Panel Pricing Calculator" to our custom section
$GLOBALS['BE_MOD']['panel_pricing_calculator']['panel_pricing_calculator'] = array(
	'tables' => array('tl_panel_pricing_calculator'),
	'icon'   => 'system/modules/panel_pricing_calculator/assets/icons/panel_pricing_calculator.png',
	'exportLocations' => array('Bcs\Backend\PanelPricingCalculatorBackend', 'exportPanelPricingCalculator')
);
// Adds "Cradle Depths" to our custom section
$GLOBALS['BE_MOD']['panel_pricing_calculator']['cradle_depths'] = array(
	'tables' => array('tl_cradle_depths'),
	'icon'   => 'system/modules/panel_pricing_calculator/assets/icons/panel_pricing_calculator.png',
	'exportLocations' => array('Bcs\Backend\CradleDepthsBackend', 'exportCradleDepths')
);
// Adds "Panel Thickness" to our custom section
$GLOBALS['BE_MOD']['panel_pricing_calculator']['panel_thicknesses'] = array(
	'tables' => array('tl_panel_thicknesses'),
	'icon'   => 'system/modules/panel_pricing_calculator/assets/icons/panel_pricing_calculator.png',
	'exportLocations' => array('Bcs\Backend\PanelThicknessesBackend', 'exportPanelThicknesses')
);


/* Models */
$GLOBALS['TL_MODELS']['tl_panel_pricing_calculator'] = 'Bcs\Model\PanelPricingCalculator';
