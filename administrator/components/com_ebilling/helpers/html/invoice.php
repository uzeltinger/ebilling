<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_ebilling
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

abstract class JHtmlInvoice
{	        
	public static function recurrented($value, $i, $enabled = true, $checkbox = 'cb')
	{
		$states = array(
			1 => array(
				'recurrent_unpublish',
				'recurrent_publish',
				'Recurrente',
				'recurrent_publish',
				true,
				'publish',
				'publish'
			),
			0 => array(
				'recurrent_publish',
				'recurrent_unpublish',
				'No Recurrente',
				'recurrent_unpublish',
				true,
				'unpublish',
				'unpublish'
			),
		);

		return JHtml::_('jgrid.state', $states, $value, $i, 'invoices.', $enabled, true, $checkbox);
	}
}
