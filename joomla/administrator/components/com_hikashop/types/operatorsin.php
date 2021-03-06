<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.3.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2014 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class hikashopOperatorsinType{
	var $js = '';
	function hikashopOperatorsinType(){

		$this->values = array();

		$this->values[] = JHTML::_('select.option', 'IN',JText::_('HIKA_IN'));
		$this->values[] = JHTML::_('select.option', 'NOT IN',JText::_('HIKA_NOT_IN'));

	}

	function display($map, $default='', $additionalClass=''){
		return JHTML::_('select.genericlist', $this->values, $map, 'class="inputbox '.$additionalClass.'" size="1" style="width:120px;" '.$this->js, 'value', 'text',$default);
	}

}
