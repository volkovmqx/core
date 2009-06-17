<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Winans Creative / Fred Bliss 2009
 * @author     Fred Bliss <fred@winanscreative.com>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Table tl_payment_modules 
 */
$GLOBALS['TL_DCA']['tl_payment_modules'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_payment_options'),
		'switchToEdit'                => true,
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('name'),
			'flag'                    => 1,
			'panelLayout'             => 'sort,filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name', 'type'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_payment_modules']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_payment_modules']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_payment_modules']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_payment_modules']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'buttons' => array
			(
				'button_callback'     => array('tl_payment_modules', 'moduleOperations'),
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type'),
		'default'                     => 'name,type',
		'cash'						  => 'name,type,label,note;countries,shipping_modules,minimum_total,maximum_total,new_order_status;enabled',
		'paypal'                      => 'name,type,label,note;countries,shipping_modules,minimum_total,maximum_total,new_order_status;paypal_account,paypal_business;debug,enabled',
		'postfinance'                 => 'name,type,label,note;countries,shipping_modules,minimum_total,maximum_total,new_order_status,postsale_mail;postfinance_pspid,postfinance_secret,postfinance_method;debug,enabled',
		'authorizedotnet'			  => 'authorize_login,authorize_is_test,authorize_delimiter,authorize_delimit_data,authorize_trans_type,authorize_relay_response,authorize_email_customer'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['type'],
			'default'                 => 'cc',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'default'				  => 'cash',
			'options_callback'        => array('tl_payment_modules', 'getModules'),
			'reference'               => &$GLOBALS['TL_LANG']['PAY'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true)
		),
		'label' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['label'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'mandatory'=>true),
		),
		'note' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['note'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE'),
		),
		'countries' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['countries'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'                 => array_keys($this->getCountries()),
			'options'                 => $this->getCountries(),
			'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'size'=>8),
		),
		'shipping_modules' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['shipping_modules'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_payment_modules', 'getShippingModules'),
			'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'size'=>8),
		),
		'minimum_total' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['minimum_total'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'default'                 => 0,
			'eval'                    => array('maxlength'=>255, 'rgxp'=>'digit', 'tl_class'=>'w50'),
		),
		'maximum_total' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['maximum_total'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'default'                 => 0,
			'eval'                    => array('maxlength'=>255, 'rgxp'=>'digit', 'tl_class'=>'w50'),
		),
		'new_order_status' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['new_order_status'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'                 => 'pending',
			'options_callback'        => array('tl_payment_modules', 'getOrderStatus'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC']['order_status_labels'],
			'eval'                    => array('includeBlankOption'=>true, 'mandatory'=>true, 'tl_class'=>'w50'),
		),
		'postsale_mail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['postsale_mail'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_iso_mail.name',
			'eval'					  => array('includeBlankOption'=>true),
		),
		'paypal_account' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['paypal_account'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'email'),
		),
		'paypal_business' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['paypal_business'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
		),
		'postfinance_pspid' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['postfinance_pspid'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
		),
		'postfinance_secret' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['postfinance_secret'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255),
		),
		'postfinance_method' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['postfinance_method'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'                 => 'POST',
			'options'                 => array('POST', 'GET'),
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		),
		'debug' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['debug'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		),		
		'enabled' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_payment_modules']['enabled'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		),
	)
);

/**
 * tl_payment_modules class.
 * 
 * @extends Backend
 */
class tl_payment_modules extends Backend
{

	/**
	 * Return a string of more buttons for the current payment module.
	 * 
	 * @todo Collect additional buttons from payment modules.
	 * @access public
	 * @param array $arrRow
	 * @return string
	 */
	public function moduleOperations($arrRow)
	{
		$strClass = $GLOBALS['ISO_PAY'][$arrRow['type']];

		if (!strlen($strClass) || !$this->classFileExists($strClass))
			return '';
			
		try 
		{
			$objModule = new $strClass($arrRow);
			return $objModule->moduleOperations();
		}
		catch (Exception $e) {}
		
		return '';
	}
	
	
	public function getOrderStatus($dc)
	{
		$objModule = $this->Database->prepare("SELECT * FROM tl_payment_modules WHERE id=?")->limit(1)->execute($dc->id);
		
		$strClass = $GLOBALS['ISO_PAY'][$objModule->type];

		if (!strlen($strClass) || !$this->classFileExists($strClass))
			return array();
			
		try 
		{
			$objModule = new $strClass($arrRow);
			return $objModule->statusOptions();
		}
		catch (Exception $e) {}
		
		return array();
	}
	
	
	/**
	 * Get a list of all payment modules available.
	 * 
	 * @access public
	 * @return array
	 */
	public function getModules()
	{
		$arrModules = array();
		
		if (is_array($GLOBALS['ISO_PAY']) && count($GLOBALS['ISO_PAY']))
		{
			foreach( $GLOBALS['ISO_PAY'] as $module => $class )
			{
				$arrModules[$module] = (strlen($GLOBALS['TL_LANG']['PAY'][$module][0]) ? $GLOBALS['TL_LANG']['PAY'][$module][0] : $module);
			}
		}
		
		return $arrModules;
	}
	
	
	/**
	 * Get all available shipping modules.
	 * 
	 * @access public
	 * @param object $dc
	 * @return array
	 */
	public function getShippingModules($dc)
	{
		$arrModules = array();
		
		$objShippings = $this->Database->execute("SELECT * FROM tl_shipping_modules ORDER BY name");
		
		while( $objShippings->next() )
		{
			$objOptions = $this->Database->prepare("SELECT * FROM tl_shipping_options WHERE pid=?")->execute($objShippings->id);
			
			if ($objOptions->numRows)
			{
				while( $objOptions->next() )
				{
					$arrModules[$objShippings->name][$objShippings->id.'_'.$objOptions->id] = $objOptions->name;
				}
			}
			else
			{
				$arrModules[$objShippings->id] = $objShippings->name;
			}
		}
		
		return $arrModules;
	}
}

