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

  public static function getSolutionChooser($btnStr, $varname = 'exp-solution') {
    //FIXME hard coded solution types
    $output = '<form method="post" action="'.JRoute::_(JURI::current()).'">'
    .'<div class="acm-features style-light style-1">'
    .'<div class="container">'
    .'<div class="row">'
    .'<div class="features-item col-sm-4 center">'
    .'<h3>La Poste通道</h3>'
    .'<p>La Poste通道请点击：</p>'
    .'<button class="btn btn-lg btn-success" name="'.$varname.'" value="LAP">'.$btnStr.'</button>'
    .'</div>'
    .'<div class="features-item col-sm-4 center">'
    .'<h3>奶粉专用通道</h3>'
    .'<p>奶粉专用通道请点击：</p>'
    .'<button class="btn btn-lg btn-success" name="'.$varname.'" value="EMS">'.$btnStr.'</button>'
    .'</div>'
    .'<div class="features-item col-sm-4 center">'
    .'<h3>EMS通道</h3>'
    .'<p>EMS通道请点击：</p>'
    .'<button class="btn btn-lg btn-success" name="'.$varname.'" value="EMS">'.$btnStr.'</button>'
    .'</div>'
    .'</div>'
    .'</div>'
    .'</div>'
    .'</form>';
    return $output;
  }

}
