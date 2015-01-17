<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
jimport('joomla.log.log');

class UsermenuModelListhistoric extends JModelItem
{

    protected $errorsMsg;

    public function getHistoricByCurrentUser ()
    {
        $user = JFactory::getUser();
        $uid = $user->id;
        if (! isset($uid) || $uid == 0) {
            JLog::add(
                    JText::_(
                            'COM_USERMENU_VIEW_FRONT_LISTHISTORIC_ERROR_NO_LOGIN'),
                    JLog::WARNING, 'jerror');
            return array(
                    'error' => JText::_(
                            'COM_USERMENU_VIEW_FRONT_LISTHISTORIC_ERROR_NO_LOGIN')
            );
        } else {
            return $this->getHistoric($uid);
        }
    }

    public function getHistoric ($uid)
    {
        $db = JFactory::getDBO();

        $query = 'select id, operation_date, operation_type, reference_id from t_operation_historic where user_id = ' .
                 $db->quote($uid) . ' and operation_type in ('.$db->quote('ORD').', '.$db->quote('BMO').');';
        $db->setQuery($query);

        $r = $db->loadObjectList('id');

//         $this->log($r, 't_operation_historic');

        if (empty($r)) {
            return array(
                    'error' => JText::_(
                            'COM_USERMENU_VIEW_FRONT_LISTHISTORIC_ERROR')
            );
        } else {
            $toReturn = array();
            foreach ($r as $k => $value) {
                $v = (array) $value;
                $referenceId = $v['reference_id'];
                $toReturn[$k] = $v;
                if ($v['operation_type'] == 'ORD') {
                    // order
                    $orderInfo = $this->getOrder($referenceId);
                    $toReturn[$k]['orderId'] = $this->mapOrderId($referenceId);
                    $toReturn[$k]['oAmount'] = $orderInfo['payment_amount'];

                    // $v['oAmount'] = $orderInfo['payment_amount'];
                } else {
                    // balance_modifications
                    $balanceModifications = $this->getBalanceModifications(
                            $referenceId);
//                     $this->log($balanceModifications, 'balance is');
                    $toReturn[$k]['bAmount'] = $balanceModifications['amount'];
                    $toReturn[$k]['bComment'] = $balanceModifications['comment'];
                    $toReturn[$k]['bWalletId'] = $balanceModifications['wallet_id'];
                    $toReturn[$k]['bWalletType'] = $balanceModifications['wallet_type'];
                }
            }
            // $this->log($toReturn, 'final result');
            return $toReturn;
        }
    }

    private function getBalanceModifications ($id)
    {
        $db = JFactory::getDBO();

        $query = 'select id, amount, comment, wallet_id, wallet_type from t_balance_modifications where id = ' .
                 $db->quote($id) . ';';

        $db->setQuery($query);

        return (array) $db->loadObject();
    }

    // TODO
    private function getOrderAndPackage ($order_id)
    {
        $order = $this->getOrder($order_id);
    }

    private function getOrder ($order_id)
    {
        $db = JFactory::getDBO();
        $query = 'select payment_amount, package_id, express_type from t_orders where order_id = ' .
                 $db->quote($order_id) . ';';

        $db->setQuery($query);

        return (array) $db->loadObject();
    }

    // TODO
    private function getPackage ($package_id)
    {
        $db = JFactory::getDBO();
        $query = 'select sender_id, recipient_id, weight, height, length, wide, stock_in_time, stock_out_time from t_packages where package_id = ' .
                 $db->quote($package_id) . ';';

        $db->setQuery($query);

        return (array) $db->loadObject();
    }

    // TODO
    private function getAddress ($id)
    {
        $db = JFactory::getDBO();
        $query = 'select address_firstname as name, address_street as street,  from gzqxc_hikashop_address where order_id = ' .
                 $db->quote($id) . ';';

        $db->setQuery($query);

        return (array) $db->loadObject();
    }

    /**
     * 用来转换数据库id(tech id)跟打印id(id fonctionnel)
     */
    private function getOrderIdMapping ()
    {
        return array(
                '0' => 'O',
                '1' => 'U',
                '2' => 'T',
                '3' => 'E',
                '4' => 'D',
                '5' => 'S',
                '6' => 'B',
                '7' => 'T',
                '8' => 'P',
                '9' => 'M'
        );
    }

    private function mapOrderId ($order_id)
    {
        $toReturn = 'FR';
        $mapping = $this->getOrderIdMapping();
        $stringOrderId = strval($order_id);
        for ($i = 0; $i < strlen($stringOrderId); $i ++) {
            $stringOrderId[$i] = $mapping[$stringOrderId[$i]];
        }
        return $toReturn . str_pad($stringOrderId, 10, '0', STR_PAD_LEFT);
    }

    public function log ($var, $varname)
    {
        echo '<br/>' . $varname . '<br/>';
        echo '<pre>';
        var_dump($var);
        echo '</pre><br/>';
    }
}