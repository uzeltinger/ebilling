<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_ebilling
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

abstract class JHtmlPayment
{	        
	public static function setTotal($value, $i, $enabled = true, $checkbox = 'cb')
	{
		$states = array(
			1 => array(
				'total_unpublish',
				'total_publish',
				'Pago Total',
				'total_publish',
				true,
				'publish',
				'publish'
			),
			0 => array(
				'total_publish',
				'total_unpublish',
				'No Pago Total',
				'total_unpublish',
				true,
				'unpublish',
				'unpublish'
			),
		);

		return JHtml::_('jgrid.state', $states, $value, $i, 'payments.', $enabled, true, $checkbox);
	}
}
