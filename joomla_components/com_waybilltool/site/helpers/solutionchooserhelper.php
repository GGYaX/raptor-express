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
 * @since 1.0.0
 */
class SolutionChooserHelper {

  public static function chosenSolution($varname = 'exp-solution') {
    $jinput = JFactory::getApplication()->input;
    $solution = $jinput->get($varname, null);
    return ($solution === null) ? null : $solution;
  }

  public static function getSolutionChooser($varname = 'exp-solution') {
    //FIXME hard coded solution types
    $output = '<form method="post" action="'.JRoute::_(JURI::current()).'">'
    .'<div class="container" style="margin:0 auto;text-align:center">'
    .'<div class="col col-sm-3 col-featured no-padding">'
    .'<div class="col-header text-center">'
    .'<h2>La Poste</h2>'
    .'<p></p>'
    .'</div>'
    .'<button class="btn btn-lg btn-success" name="'.$varname.'" value="LAP">选择</button>'
    .'</div>'
    .'<div class="col col-sm-3 col-featured no-padding">'
    .'<div class="col-header text-center">'
    .'<h2>EMS</h2>'
    .'<p></p>'
    .'</div>'
    .'<button class="btn btn-lg btn-success" name="'.$varname.'" value="EMS">选择</button>'
    .'</div>'
    .'</div>'
    .'</form>';
    return $output;
  }

}
