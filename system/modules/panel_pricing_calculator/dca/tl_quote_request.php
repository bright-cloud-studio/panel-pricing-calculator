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
            'mode'                    => 1,
            'panelLayout'             => 'filter;search,limit',
	    'fields'		      => 'reviewed',
	    'flag'		      => '11'
        ),
        'label' => array
        (
            'fields'                  => array('sorting', 'reviewed', 'first_name', 'last_name', 'city', 'state'),
            'format'                  => '<span style="font-weight: bold;">Quote Request ID: </span>%s <span style="font-weight: bold;">Processed: </span>%s <span style="font-weight: bold;">Name :</span> %s %s <span style="font-weight: bold;">Location:</span> %s, %s'
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
        'default'                     => '{reviewed_legend},reviewed;{user_legend},first_name,last_name,email,phone,address_1,address_2,city,state,zip,tell_us;{size_legend},panel_type,thickness,cradle,width,height,quantity,discount,price;{publish_legend},published;'
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
	'alias' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['alias'],
		'exclude'                 => true,
		'inputType'               => 'text',
		'search'                  => true,
		'eval'                    => array('unique'=>true, 'rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
		'save_callback' => array
		(
			array('Bcs\Backend\QuoteRequestBackend', 'generateAlias')
		),
		'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"

	),
	'reviewed' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['reviewed'],
		'inputType'               => 'select',
		'options'                 => array('unreviewed' => 'Unreviewed', 'reviewed' => 'Reviewed'),
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(32) NOT NULL default ''"
	),
	'first_name' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['first_name'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'last_name' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['last_name'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'email' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['email'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'phone' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['phone'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'address_1' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['addres_1'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'clr'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'address_2' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['address_2'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'clr'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'city' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['city'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'state' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['state'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'zip' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['zip'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'tell_us' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['tell_us'],
		'inputType'               => 'textarea',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'clr'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'panel_type' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['panel_type'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'thickness' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['thickness'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'cradle' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['cradle'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'width' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['width'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'height' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['height'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'quantity' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['quantity'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'discount' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['discount'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'price' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_quote_request']['price'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
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
