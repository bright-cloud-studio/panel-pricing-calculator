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
	'Bcs\Model\PanelPricingCalculator' 		=> 'system/modules/panel_pricing_calculator/library/Bcs/Model/PanelPricingCalculator.php',
	'Bcs\Backend\PanelPricingCalculatorBackend' 	=> 'system/modules/panel_pricing_calculator/library/Bcs/Backend/PanelPricingCalculatorBackend.php',
	'Bcs\Backend\CradleDepthsBackend' 		=> 'system/modules/panel_pricing_calculator/library/Bcs/Backend/CradleDepthsBackend.php',
	'Bcs\Backend\PanelThicknessBackend' 		=> 'system/modules/panel_pricing_calculator/library/Bcs/Backend/PanelThicknessBackend.php'
));
