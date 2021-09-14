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

 
/* Table tl_price_chart */
$GLOBALS['TL_DCA']['tl_quote_request'] = array
(
 
    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' 	=> 	'primary',
                'alias' =>  'index'
            )
        )
    ),
 
    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 0,
            'panelLayout'             => 'filter;search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('session_id', 'price'),
            'format'                  => '<span style="font-weight: bold;">Session ID:</span> %sx%s <span style="font-weight: bold;">Price:</span> %s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_quote_request']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
			
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_quote_request']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_quote_request']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_quote_request']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('Bcs\Backend\QuoteRequestBackend', 'toggleIcon')
			),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_quote_request']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),
 
    // Palettes
    'palettes' => array
    (
        'default'                     => '{size_legend},session_id,panel_type,thickness,cradle,width,height,quantity,discount,price;{publish_legend},published;'
    ),
 
    // Fields
    'fields' => array
    (
        'id' => array
        (
		'sql'                     	=> "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
		'sql'                     	=> "int(10) unsigned NOT NULL default '0'"
        ),
	'sorting' => array
	(
		'sql'                    	=> "int(10) unsigned NOT NULL default '0'"
	),
	'session_id' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['session_id'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "int(10) NOT NULL default '0'"
	),
	'panel_type' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['panel_type'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "int(10) NOT NULL default '0'"
	),
	'thickness' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['thickness'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "int(10) NOT NULL default '0'"
	),
	'cradle' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['cradle'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "int(10) NOT NULL default '0'"
	),
	'width' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['width'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "int(10) NOT NULL default '0'"
	),
	'height' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['height'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "int(10) NOT NULL default '0'"
	),
	'quantity' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['quantity'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "int(10) NOT NULL default '0'"
	),
	'discount' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['discount'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "int(10) NOT NULL default '0'"
	),
	'price' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['price'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "int(10) NOT NULL default '0'"
	),
	'published' => array
	(
		'exclude'                 => true,
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['published'],
		'inputType'               => 'checkbox',
		'eval'                    => array('submitOnChange'=>true, 'doNotCopy'=>true),
		'sql'                     => "char(1) NOT NULL default ''"
	)		
    )
);
