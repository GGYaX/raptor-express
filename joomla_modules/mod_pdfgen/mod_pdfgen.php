<?php

/**
 * Module's entry point
 * 
 * @package    com.express
 * @subpackage Modules
 * @license    ???
 */

defined('_JEXEC') or die;

require_once( dirname(__FILE__) . '/jHelper.php' );
 
$pdfGenInfo = modPDFGenHelper::getPDF($params);
require( JModuleHelper::getLayoutPath('mod_pdfgen'));

?>