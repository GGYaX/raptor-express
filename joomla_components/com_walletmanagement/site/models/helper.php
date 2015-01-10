<?php

class comModelWalletmanagementHelper
{

    public function getWalletAmount ()
    {
        $user = JFactory::getUser();
        $uid = $user->id;
        if (isset($uid) && $uid != 0) {
            return $this->getWalletAmountByUserId($uid);
        } else {
            // return error
            return array(
                    'error' => JText::_(
                            'COM_WALLETMANAGEMENT_VIEW_FRONT_ERROR_LABEL')
            );
        }
    }

    public function getWalletAmountByUserId($uid) {
        try {
            $result = $this->getWalletByUserId($uid);
            $emsid = $result['ems_id'];
            $laposteid = $result['laposte_id'];
            return array(
                    'emsAmount' => $this->getEmsAmount($uid, $emsid),
                    'laposteAmount' => $this->getLaposteAmount($uid,
                            $laposteid),
                    'emsId' => $emsid,
                    'laposteId' => $laposteid
            );
        } catch (Exception $e) {
            JLog::add ( implode ( '<br />', $errors ), JLog::WARNING, 'jerror' );
            // return error
            return array(
                    'error' => JText::_(
                            'COM_WALLETMANAGEMENT_VIEW_FRONT_ERROR_LABEL')
            );
        }
    }

    public function getAllUserWithTheirWallet() {
        $allUser = $this->getAllUser();
        $toReturn = array();
        foreach ($allUser as $key => $value) {
            $v = (array) $value;
            $toReturn[$key] = $this->getWalletAmountByUserId($v['id']);
            $toReturn[$key]['uid'] = $v['id'];
        }
        return $toReturn;
    }

    public function log($var, $varname) {
        echo '<br/>'. $varname .'<br/>';
        echo '<pre>';
        var_dump($var);
        echo '</pre><br/>';
    }

    private  function getAllUser() {
        $db = JFactory::getDBO();
        $query = 'SELECT id,username FROM gzqxc_users;';
        $db->setQuery($query);
        $db->query();
        return $db->loadObjectList('username');
    }

    public function getWalletByUserId ($uid)
    {
        $db = JFactory::getDBO();
        $query = 'SELECT ems_id, laposte_id FROM t_wallets WHERE USER_ID = ' .
                 $db->quote($uid) . ';';

        $db->setQuery($query);
        $db->query();

        return (array) $db->loadObject();
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

    private static function getAmount ($uid, $id, $types)
    {
        $db = JFactory::getDBO();

        $queryBalance = 'SELECT SUM(AMOUNT) as sum FROM t_balance_modifications WHERE WALLET_ID = ' .
                 $db->quote($id) . ';';
        $queryOrder = 'SELECT SUM(payment_amount) as sum FROM t_orders WHERE CLIENT_ID = ' .
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
?>