<?php
defined('_JEXEC') or die('Restricted access');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
require_once (dirname(__FILE__) . '/helper.php');

class WalletmanagementModelEdit extends JModelItem
{

    protected $accessError;

    protected $userToEdit;

    public function setAccessError ($e)
    {
        $this->accessError = $e;
    }

    public function getAccessError ()
    {
        return $this->accessError;
    }

    public function setUserToEdit ($uid)
    {
        $this->userToEdit = $uid;
    }

    public function getUserToEdit ()
    {
        return $this->userToEdit;
    }

    public function getWalletAmountByUserId ($uid)
    {
        $helper = new comModelWalletmanagementHelper();
        return $helper->getWalletAmountByUserId($uid);
    }

    public function editWalletAmount ($uid)
    {
        $helper = new comModelWalletmanagementHelper();
        $wallet = $helper->getWalletAmountByUserId($uid);

        $toReturn;
        try {
            if (isset($wallet['error'])) {
                JLog::add(implode('<br />', $wallet['error']), JLog::WARNING,
                        'jerror');
                $toReturn = array(
                        'error' => JText::_(
                                'COM_WALLETMANAGEMENT_VIEW_FRONT_EDIT_ERROR_LABEL')
                );
            } else {
                $jinput = JFactory::getApplication()->input;
                $jformArray = $jinput->post->getArray();
                $jform = $jformArray['jform'];
                $params = array(
                        'emsM' => $jform['emsM'],
                        'laposteM' => $jform['laposteM'],
                        'comment' => $jform['comment']
                );
                $helper->log($params, 'params');
                if (isset($params['emsM']) || isset($params['laposteM'])) {
                    $floatEmsM = floatval($params['emsM']);
                    $floatLaposteM = floatval($params['laposteM']);
                    if ($floatEmsM == 0 && $floatLaposteM == 0) {
                        $toReturn = array(
                                'warning' => JText::_(
                                        'COM_WALLETMANAGEMENT_VIEW_FRONT_EDIT_AMOUNT_NO_CHANGE')
                        );
                    } else {
                        if ($floatEmsM != 0) {
                            // change ems wallet
                            $this->changeWalletAmount($wallet['emsId'],
                                    $floatEmsM, 'EMS', $params['comment']);
                        }
                        if ($floatLaposteM != 0) {
                            // change laposte wallet
                            $this->changeWalletAmount($wallet['laposteId'],
                                    $floatLaposteM, 'LAP', $params['comment']);
                        }
                        $toReturn = array(
                                'succes' => JText::_(
                                        'COM_WALLETMANAGEMENT_VIEW_FRONT_EDIT_AMOUNT_CHANGE_SUCCES')
                        );
                    }
                } else {
                    $toReturn = array(
                            'warning' => JText::_(
                                    'COM_WALLETMANAGEMENT_VIEW_FRONT_EDIT_AMOUNT_NO_CHANGE')
                    );
                }
            }
        } catch (Exception $e) {
            JLog::add(implode('<br />', $e), JLog::WARNING, 'jerror');
            $toReturn = array(
                    'error' => JText::_(
                            'COM_WALLETMANAGEMENT_VIEW_FRONT_EDIT_ERROR_LABEL')
            );
        } finally {
            return $toReturn;
        }
    }

    private function changeWalletAmount ($walletId, $amount, $walletType,
            $comment)
    {
        try {

            $db = JFactory::getDBO();
            $query = 'INSERT INTO t_balance_modifications (`amount`, `wallet_id`, `wallet_type`, `date`, `comment`) VALUES (' .
                     $db->quote($amount) . ',' . $db->quote($walletId) . ',' .
                     $db->quote($walletType) . ',' . 'NOW()' . ',' .
                     $db->quote($comment) . ');';
            $db->setQuery($query);
            $db->query();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllUserWithTheirWallet() {
        $helper = new comModelWalletmanagementHelper();
        return $helper->getAllUserWithTheirWallet();
    }
}