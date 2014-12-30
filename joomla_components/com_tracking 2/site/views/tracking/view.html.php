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
class TrackingViewTracking extends JViewLegacy {
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
		$doc = JFactory::getDocument();
		// Load scripts
		$doc->addScript ( JURI::root ( true ) . '/media/com_tracking/js/com_tracking.js' );

		// load styles
		$doc->addStyleSheet ( JURI::root ( true ) . '/media/com_tracking/css/com_tracking.css' );
		parent::display ( $tpl );
	}
}