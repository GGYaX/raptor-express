<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
require_once(dirname(__FILE__) . '/helper.php');

class WalletmanagementModelDisplay extends JModelItem
{

    public function getWalletAmount ()
    {
        $helper = new comModelWalletmanagementHelper();
        return $helper->getWalletAmount();
    }

}