<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla modelitem library
jimport ( 'joomla.application.component.modelitem' );

class UsermenuModelListorders extends JModelItem {
    protected $msg;

    public function getMsg() {
        return "listorders";
    }
}