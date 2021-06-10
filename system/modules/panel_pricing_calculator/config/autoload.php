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

/* Register the classes */
ClassLoader::addClasses(array
(
    	'Bcs\Module\LocationsList' 		=> 'system/modules/panel_pricing_calculator/library/Bcs/Module/LocationsList.php',
	'Bcs\Backend\Locations' 		=> 'system/modules/panel_pricing_calculator/library/Bcs/Backend/Locations.php',
	'Bcs\Model\Location' 			=> 'system/modules/panel_pricing_calculator/library/Bcs/Model/Location.php',
	'Bcs\Locations'		 		=> 'system/modules/panel_pricing_calculator/library/Bcs/Locations.php'
));

/* Register the templates */
TemplateLoader::addFiles(array
(
   	'mod_locations_list' 		=> 'system/modules/panel_pricing_calculator/templates/modules',
	'item_location' 		=> 'system/modules/panel_pricing_calculator/templates/items',
));
