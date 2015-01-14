<?php
/**
* @package Joomla.Administrator
* @subpackage com_waybilltool
*
* @copyright Copyright (C) 2014
* @license GNU??? BSD???
*/

defined('_JEXEC') or die;
jimport('joomla.application.component.view');

/**
* HTML View class for the Waybill Tool Component
*
* @since 0.0.1
*/
class WaybillToolViewWaybillAdmin extends JViewLegacy
{
	/**
         * @param   string  $tpl  The name of the template file to parse;
         * automatically searches through the template paths.
         * @return  void
         */
	public function display($tpl = null) {
    echo "Modifying table view...";
		$this->listForm = $this->get('AdminListForm');

		if (count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}

		parent::display($tpl);
	}
}
