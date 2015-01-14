<?php
jimport('joomla.log.log');
/**
* @since 1.0.0
*/
class UserWalletHelper {

    public function getWalletAmount() {
        $user = JFactory::getUser();
        $uid = $user->id;
        if (isset($uid) && $uid != 0) {
            return $this->getWalletAmountByUserId($uid);
        } else {
          if($GLOBALS['WAYBILLTOOL_DEBUG']){
            throw $e;
          }
          return null;
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
          if($GLOBALS['WAYBILLTOOL_DEBUG']){
            throw $e;
          }
          return null;
        }
    }

    public function getAllUserWithTheirWallet() {
        $allUser = $this->getAllUser();
        $toReturn = array();
        foreach ($allUser as $key => $value) {
            $v = (array) $value;
            $toReturn[$key] = $this->getWalletAmountByUserId($v['id']);
            $toReturn[$key]['uid'] = $v['id'];
            $toReturn[$key]['name'] = $v['name'];
        }
        return $toReturn;
    }

    public function getWalletByUserId ($uid)
    {
      $db = JFactory::getDBO();
      $query = 'SELECT ems_id, laposte_id FROM t_wallets WHERE USER_ID = ' .
      $db->quote($uid) . ';';

      $db->setQuery($query);

      $result = (array) $db->loadObject();

      if($GLOBALS['WAYBILLTOOL_DEBUG']){
        echo '<pre>';
        var_dump($result);
        echo '</pre>';
      }

      return $result;
    }

    public function log($var, $varname) {
        echo '<br/>'. $varname .'<br/>';
        echo '<pre>';
        var_dump($var);
        echo '</pre><br/>';
    }


    public function getUserWalletHtml(){
      $result = $this->getWalletAmount();
      if($result === null) {
        return '
        <div id="system-message-container">
        <div id="system-message">
        <div class="alert alert-warning">
        <div>
        <p>余额载入出错:( 请联系管理员</p>
        </div>
        </div>
        </div>
        </div>';
      }

      $emsAmount = $result["emsAmount"];
      $laposteAmount = $result["laposteAmount"];
      $emsId = $result["emsId"];
      $laposteId = $result["laposteId"];
      $displayAlertEms = $emsAmount < 50;
      $displayAlertLaposte = $laposteAmount < 50;
      $warning = '';
      if ($displayAlertEms == true || $displayAlertLaposte == true){
        $warning = '
        <div id="system-message-container">
        <div id="system-message">
        <div class="alert alert-warning">
        <div>
        <p>余额即将不足，请及时充值</p>
        </div>
        </div>
        </div>
        </div>';
      }
      $alertEmsStyle = $displayAlertEms ? 'color:#c09853' : '';
      $alertLaposteStyle = $displayAlertLaposte ? 'color:#c09853' : '';
      $output =
            '<div class="row">
            <div class="col-xs-12 col-md-12">'.
            $warning.
            '<table class="tg">
            <tr>
            <th class="tg-dm0n"></th>
            <th class="tg-s6z2">EMS钱包</th>
            <th class="tg-s6z2">La Poste钱包</th>
            </tr>
            <tr>
            <td class="tg-5klj">余额</td>
            <td class="tg-031e tg-s6z2" style="'.$alertEmsStyle.'">'.$result['emsAmount'].
              '</td>
              <td class="tg-031e tg-s6z2" style="'.$alertLaposteStyle.'">'.$result['laposteAmount'].
                '</td>
              </tr>
            </table>
          </div>
        </div>';
      return $output;
    }

    private function getAllUser() {
        $db = JFactory::getDBO();
        $query = 'SELECT id,username,name FROM gzqxc_users;';
        $db->setQuery($query);
        return $db->loadObjectList('username');
    }

    private function getEmsAmount($uid, $emsid)
    {
        $result = $this->getAmount($uid, $emsid,
              JText::_('COM_WAYBILLTOOL_VIEW_FRONT_EMS_TYPE'));
        if($GLOBALS['WAYBILLTOOL_DEBUG']){
          echo '<pre>';
          var_dump($result);
          echo '</pre>';
        }
        return $result;
    }

    private function getLaposteAmount($uid, $laposteid)
    {
        $result = $this->getAmount($uid, $laposteid,
              JText::_('COM_WAYBILLTOOL_VIEW_FRONT_LAPOSTE_TYPE') );
        if($GLOBALS['WAYBILLTOOL_DEBUG']){
          echo '<pre>';
          var_dump($result);
          echo '</pre>';
        }
        return $result;
    }

    private static function getAmount ($uid, $id, $types)
    {
        $db = JFactory::getDBO();

        $queryBalance = 'SELECT SUM(AMOUNT) as sum FROM t_balance_modifications WHERE WALLET_ID = ' .
                 $db->quote($id) . ';';
        $queryOrder = 'SELECT SUM(payment_amount) as sum FROM t_orders WHERE CLIENT_ID = ' .
                 $db->quote($uid) . ' AND express_type IN (' . $types . ')' . ';';

        $db->setQuery($queryBalance);
        $r = (array) $db->loadObject();
        $amountBalance = $r['sum'];

        $db->setQuery($queryOrder);
        $r = (array) $db->loadObject();
        $amountOrder = $r['sum'];

        return floatval($amountBalance) - floatval($amountOrder);
    }

}
?>
