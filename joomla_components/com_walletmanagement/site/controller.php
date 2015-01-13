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
        $jinput = JFactory::getApplication()->input;
        $viewRequest = $jinput->get('view');
        if ($viewRequest == 'display') {
            parent::display();
        } else {
            $user = JFactory::getUser();
            $uid = $user->id;
            $model = $this->getModel('edit');
            if ($uid == 0) {
                // check if user loggin
                JFactory::getApplication()->redirect(
                        JURI::base() . 'index.php/login', $error, 'error');
            } else {
                // if user loggin, check if he has right
                $allowed = $this->checkUserAllowed($uid);
                if ($allowed == false) {
                    // raise a 404
                    JError::raiseError(404,
                            JText::_(
                                    'COM_WALLETMANAGEMENT_VIEW_FRONT_PAGE_NOT_FOUND'));
                } else {
                    parent::display();
                }
            }
        }
    }

    function edit ()
    {
        $user = JFactory::getUser();
        $uid = $user->id;
        $model = $this->getModel('edit');
        if ($user->guest || $uid <= 0) {
            // check if user no loggin
            JFactory::getApplication()->redirect(
                    JURI::base() . 'index.php/login', $error, 'error');
        } else {
            $allowed = $this->checkUserGroupsInAllowedGroups($user->groups);
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
                $view->setLayout('edit');
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

    function editwalletid ()
    {
        $user = JFactory::getUser();
        $uid = $user->id;
        $user = JFactory::getUser();
        $uid = $user->id;
        $model = $this->getModel('edit');
        if ($user->guest || $uid <= 0) {
            // check if user no loggin
            JFactory::getApplication()->redirect(
                    JURI::base() . 'index.php/login', $error, 'error');
        } else {
            $allowed = $this->checkUserGroupsInAllowedGroups($user->groups);
            if ($allowed == true) {
                $jinput = JFactory::getApplication()->input;
                $walletIdToEdit = $jinput->get('walletid');
                $walletType = $jinput->get('wallettype');

                $post = $jinput->post->getArray();
                $newWalletId = $post['newwalletid'];

                if (! isset($walletIdToEdit) || ! isset($walletType)) {
                    $model->setAccessError(
                            array(
                                    'error' => JText::_(
                                            'COM_WALLETMANAGEMENT_VIEW_FRONT_NO_WALLET_ID_TO_EDIT')
                            ));
                } else {
                    if(isset($newWalletId)) {

                        $model->editWalletId($walletIdToEdit, $walletType,
                                $newWalletId);
                        $model->setMsgArray(
                                array(
                                        array(
                                                'level' => 'success',
                                                'msgBody' => JText::_(
                                                        'COM_WALLETMANAGEMENT_VIEW_FRONT_WALLET_ID_CHANGED_SUCCES')
                                        )
                                ));
                    }
                }
                $view = $this->getView('edit', 'html');
                $view->setModel($model, true);
                $view->setLayout('edit_wallet_id');
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

    private function checkUserAllowed ($uid)
    {
        $userGroups = JAccess::getGroupsByUser($uid);
        return $this->checkUserGroupsInAllowedGroups($userGroups);
    }

    private function checkUserGroupsInAllowedGroups ($userGroups)
    {
        $allowed = false;
        $allowedGroups = array_map('intval',
                explode('|',
                        JText::_(
                                'COM_WALLETMANAGEMENT_VIEW_FRONT_EDIT_ACCES_GROUPS')));

        foreach ($userGroups as $gid) {
            if (in_array($gid, $allowedGroups)) {
                $allowed = true;
                break;
            }
        }
        return $allowed;
    }
}
