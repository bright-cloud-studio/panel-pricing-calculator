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


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// front end module
	'Bcs\Module\PanelPricingCalculatorModule' 	=> 'system/modules/panel_pricing_calculator/library/Bcs/Module/PanelPricingCalculatorModule.php',
	
	// different dca to hold data
	'Bcs\Model\PanelPricingCalculator' 		=> 'system/modules/panel_pricing_calculator/library/Bcs/Model/PanelPricingCalculator.php',
	'Bcs\Backend\PanelPricingCalculatorBackend' 	=> 'system/modules/panel_pricing_calculator/library/Bcs/Backend/PanelPricingCalculatorBackend.php',
	'Bcs\Backend\CradleDepthsBackend' 		=> 'system/modules/panel_pricing_calculator/library/Bcs/Backend/CradleDepthsBackend.php',
	'Bcs\Backend\PanelThicknessesBackend' 		=> 'system/modules/panel_pricing_calculator/library/Bcs/Backend/PanelThicknessesBackend.php'
));

/* Register the templates */
TemplateLoader::addFiles(array
(
	'mod_panel_pricing_calculator'			=> 'system/modules/panel_pricing_calculator/templates/modules'
));
