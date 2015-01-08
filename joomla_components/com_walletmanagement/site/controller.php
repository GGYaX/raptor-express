<?php
/**
* @package Joomla.Administrator
* @subpackage com_tracking
*
* @copyright Copyright (C) 2014
* @license GNU??? BSD???
*/

defined('_JEXEC') or die;
jimport('joomla.application.component.view');

/**
* @since 1.0.0
*/
class WalletmanagementController extends JControllerLegacy
{
    function display ()
    {
        parent::display();
    }

    function edit() {
    // JAccess::getGroupsByUser($userId) TODO define usergroup_id (8)
    // TODO control access
    }
}
