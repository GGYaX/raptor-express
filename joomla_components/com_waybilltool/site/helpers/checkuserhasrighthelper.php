<?php
defined('_JEXEC') or die();

class CheckUserHasRightHelper {

    public function checkUserHasRight() {
        $user = JFactory::getUser();
        $uid = $user->id;
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