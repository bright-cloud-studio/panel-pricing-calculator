<?php

/**
 * Panel Pricing Calculator - Customized calculator for determining panel pricing
 *
 * Copyright (C) 2021 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/panel-pricing-calculator
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/* Back end modules */
$GLOBALS['BE_MOD']['content']['locations'] = array(
	'tables' => array('tl_location'),
	'icon'   => 'system/modules/panel_pricing_calculator/assets/icons/location.png',
	'exportLocations' => array('Bcs\Backend\Locations', 'exportLocations')
);

/* Front end modules */
$GLOBALS['FE_MOD']['rep_finder']['locations_list'] 	= 'Bcs\Module\LocationsList';

/* Models */
$GLOBALS['TL_MODELS']['tl_location'] = 'Bcs\Model\Location';
