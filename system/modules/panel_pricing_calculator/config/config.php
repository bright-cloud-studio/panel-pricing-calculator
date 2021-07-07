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


/* Back end modules */

// DECLARING LOCATIONS BACK END PLUGIN

if (!is_array($GLOBALS['BE_MOD']['panel_pricing_calculator']))
{
    array_insert($GLOBALS['BE_MOD'], 1, array('panel_pricing_calculator' => array()));
}

$GLOBALS['TL_LANG']['MOD']['panel_pricing_calculator'][0] = "Panel Pricing Calculator";
$GLOBALS['BE_MOD']['panel_pricing_calculator']['panel_pricing_calculator'] = array(
	'tables' => array('tl_panel_pricing_calculator'),
	'icon'   => 'system/modules/panel_pricing_calculator/assets/icons/panel_pricing_calculator.png',
	'exportLocations' => array('Bcs\Backend\PanelPricingCalculatorBackend', 'exportPanelPricingCalculator')
);

$GLOBALS['BE_MOD']['panel_pricing_calculator']['cradle_depths'] = array(
	'tables' => array('tl_cradle_depths'),
	'icon'   => 'system/modules/panel_pricing_calculator/assets/icons/panel_pricing_calculator.png',
	'exportLocations' => array('Bcs\Backend\CradleDepthsBackend', 'exportCradleDepths')
);

/* Models */
$GLOBALS['TL_MODELS']['tl_panel_pricing_calculator'] = 'Bcs\Model\PanelPricingCalculator';
