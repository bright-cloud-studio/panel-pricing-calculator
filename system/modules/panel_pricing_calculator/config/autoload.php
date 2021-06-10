<?php

/**
 * Rep Finder - Location Plugin for Contao
 *
 * Copyright (C) 2021 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/rep-finder
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/* Register the classes */
ClassLoader::addClasses(array
(
    'Bcs\Module\LocationsList' 		=> 'system/modules/rep_finder/library/Bcs/Module/LocationsList.php',
	'Bcs\Backend\Locations' 		=> 'system/modules/rep_finder/library/Bcs/Backend/Locations.php',
	'Bcs\Model\Location' 			=> 'system/modules/rep_finder/library/Bcs/Model/Location.php',
	'Bcs\Locations'		 		=> 'system/modules/rep_finder/library/Bcs/Locations.php'
));

/* Register the templates */
TemplateLoader::addFiles(array
(
   	'mod_locations_list' 		=> 'system/modules/rep_finder/templates/modules',
	'item_location' 		=> 'system/modules/rep_finder/templates/items',
));
