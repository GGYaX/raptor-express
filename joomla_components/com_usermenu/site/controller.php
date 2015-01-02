<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

class UsermenuController extends JControllerLegacy
{

    function display ()
    {
        parent::display();
    }

    function insert ()
    {
        $model = $this->getModel('newaddress');
        $view = $this->getView('newaddress', 'html');
        $view->setModel($model, true);
        $view->setLayout('insert');

        $view->display();
    }
}
