<?php
/**
* @package Joomla.Administrator
* @subpackage com_tracking
*
* @copyright Copyright (C) 2014
* @license GNU??? BSD???
*/
defined ( '_JEXEC' ) or die ();
jimport ( 'joomla.application.component.view' );

/**
 * HTML View class for the HelloWorld Component
 *
 * @since 0.0.1
 *
 */
class WalletmanagementViewEdit extends JViewLegacy {

    protected $msg;

	/**
	 *
	 * @param string $tpl
	 *        	The name of the template file to parse;
	 *        	automatically searches through the template paths.
	 * @return void
	 */
	public function display($tpl = null) {
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			JLog::add ( implode ( '<br />', $errors ), JLog::WARNING, 'jerror' );
			return false;
		}

		$this->msg = $this->get('Msg');

		parent::display ( $tpl );
	}
}