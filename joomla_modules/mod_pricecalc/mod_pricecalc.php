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

$priceCalcInfo = modPriceCalcHelper::getPrice($params);
require( JModuleHelper::getLayoutPath('mod_pricecalc'));

?>