<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class WalletmanagementModelDisplay extends JModelItem
{

    public function getWalletAmount ()
    {
        $user = JFactory::getUser();
        $uid = $user->id;
        if (isset($uid) && $uid != 0) {
            $db = JFactory::getDBO();
            $query = 'SELECT ems_id, laposte_id FROM T_WALLETS WHERE USER_ID = ' .
                     $db->quote($uid) . ';';

            $db->setQuery($query);
            $db->query();

            $result = (array) $db->loadObject();
            $emsid = $result['ems_id'];
            $laposteid = $result['laposte_id'];

            return array(
                    'emsAmount' => $this->getEmsAmount($uid, $emsid),
                    'laposteAmount' => $this->getLaposteAmount($uid, $laposteid),
                    'emsId' => $emsid,
                    'laposteId' => $laposteid
            );
        } else {
            // return error
            return array(
                    'error' => JText::_('COM_WALLETMANAGEMENT_VIEW_FRONT_ERROR_LABEL')
            );
        }
    }

    private function getEmsAmount ($uid, $emsid)
    {
        return $this->getAmount($uid, $emsid,
                JText::_('COM_WALLETMANAGEMENT_VIEW_FRONT_EMS_TYPE'));
    }

    private function getLaposteAmount ($uid, $laposteid)
    {
        return $this->getAmount($uid, $laposteid,
                JText::_('COM_WALLETMANAGEMENT_VIEW_FRONT_LAPOSTE_TYPE'));
    }

    private function getAmount ($uid, $id, $types)
    {
        $db = JFactory::getDBO();

        $queryBalance = 'SELECT SUM(AMOUNT) as sum FROM T_BALANCE_MODIFICATIONS WHERE WALLET_ID = ' .
                 $db->quote($id) . ';';
        $queryOrder = 'SELECT SUM(payment_amount) as sum FROM T_ORDERS WHERE CLIENT_ID = ' .
                 $db->quote($uid) . ' AND express_type IN (' . $types . ')' . ';';

        $db->setQuery($queryBalance);
        $db->query();
        $r = (array) $db->loadObject();
        $amountBalance = $r['sum'];

        $db->setQuery($queryOrder);
        $db->query();
        $r = (array) $db->loadObject();
        $amountOrder = $r['sum'];

        return floatval($amountBalance) - floatval($amountOrder);
    }
}