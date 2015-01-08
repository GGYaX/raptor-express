<?php
/**
* @package Joomla.Administrator
* @subpackage com_tracking
*
* @copyright Copyright (C) 2014
* @license GNU??? BSD???
*/
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

/**
 *
 * @since 1.0.0
 *
 */
class WalletmanagementController extends JControllerLegacy
{

    function display ()
    {
         JError::raiseError(404, JText::_('COM_WALLETMANAGEMENT_VIEW_FRONT_PAGE_NOT_FOUND'));
    }

    function edit ()
    {
        $user = JFactory::getUser();
        $uid = $user->id;
        $model = $this->getModel('edit');
        if ($uid == 0) {
            // check if user loggin
            $model->setAccessError(
                    array(
                            'error' => JText::_(
                                    'COM_WALLETMANAGEMENT_VIEW_FRONT_EDIT_NO_USER_LOGIN'),
                            'stop' => true
                    ));
        } else {
            $userGroups = JAccess::getGroupsByUser($uid);
            $allowed = false;
            $allowedGroups = array_map('intval',explode('|',
                    JText::_(
                            'COM_WALLETMANAGEMENT_VIEW_FRONT_EDIT_ACCES_GROUPS')));

            foreach ($userGroups as $gid) {
                if (in_array($gid, $allowedGroups)) {
                    $allowed = true;
                    break;
                }
            }
            if ($allowed == true) {
                $jinput = JFactory::getApplication()->input;
                $uidToEdit = $jinput->get('uid');
                if (! isset($uidToEdit)) {
                    $postArray = $jinput->post->getArray();
                    $uidToEdit = $postArray['uid'];
                }

                if (! isset($uidToEdit)) {
                    $model->setAccessError(
                            array(
                                    'error' => JText::_(
                                            'COM_WALLETMANAGEMENT_VIEW_FRONT_NO_USER_TO_EDIT'),
                                    'stop' => true
                            ));
                } else {
                    $model->setUserToEdit($uidToEdit);
                    $model->editWalletAmount($uidToEdit);
                }
                $view = $this->getView('edit', 'html');
                $view->setModel($model, true);
                $view->setLayout('default');
                $view->display();
            } else {
                // not allowed, log this try
                JLog::add(
                        implode('<br />',
                                'user : ' . $uid .
                                         ' tried to connected to wallet edit'),
                        JLog::ALERT, 'jerror');
                $model->setAccessError(
                        array(
                                'error' => JText::_(
                                        'COM_WALLETMANAGEMENT_VIEW_FRONT_EDIT_NO_ACCES_TO_EDIT'),
                                'stop' => true
                        ));
            }
        }
    }
}
