<?php
  
  defined('_JEXEC') or die;

  $controller = JControllerLegacy::getInstance('Tracking');
  
  $input = JFactory::getApplication()->input;
  $controller->execute($input->getCmd('task'));

  $controller->redirect();