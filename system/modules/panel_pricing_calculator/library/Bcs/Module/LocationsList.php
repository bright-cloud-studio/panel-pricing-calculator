<?php

/**
 * Rep Finder - Rep searching package for Contao
 *
 * Copyright (C) 2021 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/rep-finder
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

  
namespace Bcs\Module;
 
use Bcs\Model\Location;
use Bcs\Locations; 
 
class LocationsList extends \Contao\Module
{
 
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_locations_list';
 
	protected $arrStates = array();
 
	/**
	 * Initialize the object
	 *
	 * @param \ModuleModel $objModule
	 * @param string       $strColumn
	 */
	public function __construct($objModule, $strColumn='main')
	{
		parent::__construct($objModule, $strColumn);
		$this->arrStates = Locations::getStates();
	}
	
    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
 
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['locations_list'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&table=tl_module&act=edit&id=' . $this->id;
 
            return $objTemplate->parse();
        }
 
        return parent::generate();
    }
 
 
    /**
     * Generate the module
     */
    protected function compile()
    {
		$objLocation = Location::findBy('published', '1');
		
		if (!in_array('system/modules/rep_finder/assets/js/rep_finder.js', $GLOBALS['TL_JAVASCRIPT'])) { 
			$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/rep_finder/assets/js/rep_finder.js';
		}
		
		// Return if no pending items were found
		if (!$objLocation)
		{
			$this->Template->empty = 'No Locations Found';
			return;
		}

		$arrStates = array();
		
		// Generate List
		while ($objLocation->next())
		{
			$strStateKey = $objLocation->state;
			$strStateName = ($this->arrStates["United States"][$objLocation->state] != '' ? $this->arrStates["United States"][$objLocation->state] : $this->arrStates["Canada"][$objLocation->state]);
			if (in_array($objLocation->state, array('AB','BC','MB','NB','NL','NS','NT','NU','ON','PE','QC','SK','YT'))) {
				$strStateKey = 'CAN';
				$strStateName = 'Canada - All Provinces';
			}
			
			if (!array_key_exists($strStateKey, $arrStates)) {
				$arrStates[$strStateKey] = array(
					"name" 			=> $strStateName,
					"abbr"			=> $strStateKey,
					"locations"		=> array()
				);
			}
			
			$arrLocation = array(
				'id'		=> $objLocation->id,
				'alias'		=> $objLocation->alias,
				'tstamp'	=> $objLocation->tstamp,
				'timetamp'	=> \Date::parse(\Config::get('datimFormat'), $objLocation->tstamp),
				'published' => $objLocation->published
			);
			
			if ($this->jumpTo) {
				$objTarget = $this->objModel->getRelated('jumpTo');
				$arrLocation['link'] = $this->generateFrontendUrl($objTarget->row()) .'?alias=' .$objLocation->alias;
			}
			
			//$this->Template->categories = \StringUtil::deserialize(YOUR_VARIABLE_HERE);
			
			$arrLocation['name'] 			= $objLocation->name;
			$arrLocation['contact_name']		= $objLocation->contact_name;
			$arrLocation['phone'] 			= $objLocation->phone;
			$arrLocation['url'] 			= $objLocation->url;
			$arrLocation['territory_notes'] 	= $objLocation->territory_notes;
			$arrLocation['zip'] 			= $objLocation->zip;
			

			$strItemTemplate = ($this->locations_customItemTpl != '' ? $this->locations_customItemTpl : 'item_location');
			$objTemplate = new \FrontendTemplate($strItemTemplate);
			$objTemplate->setData($arrLocation);
			$arrStates[$strStateKey]['locations'][] = $objTemplate->parse();
		}

		$arrTemp = $arrStates;
		unset($arrTemp['CAN']);
		uasort($arrTemp, array($this,'sortByState'));
		$arrTemp['CAN'] = $arrStates['CAN'];
		$arrStates = $arrTemp;
		
		$this->Template->stateOptions = $this->generateSelectOptions();
		$this->Template->states = $arrStates;
		
	}

	public function generateSelectOptions($blank = TRUE) {
		$strUnitedStates = '<optgroup label="United States">';
		$strCanada = '<optgroup label="Canada"><option value="CAN">All Provinces</option></optgroup>';
		foreach ($this->arrStates['United States'] as $abbr => $state) {
			if (!in_array($objLocation->state, array('AB','BC','MB','NB','NL','NS','NT','NU','ON','PE','QC','SK','YT'))) {
				$strUnitedStates .= '<option value="' .$abbr .'">' .$state .'</option>';
			}
		}
		$strUnitedStates .= '</optgroup>';
		return ($blank ? '<option value="">Select Location...</option>' : '') .$strUnitedStates .$strCanada;
	}
	
	function sortByState($a, $b) {
		if ($a['Name'] == $b['Name']) {
			return 0;
		}
		return ($a['Name'] < $b['Name']) ? -1 : 1;
	}

} 
