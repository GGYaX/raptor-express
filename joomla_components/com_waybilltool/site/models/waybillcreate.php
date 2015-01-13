<?php
defined('_JEXEC') or die('404');
jimport('joomla.application.component.modelitem');
class WaybillToolModelWaybillCreate extends JModelItem {
    /**
     * @var string msg
     */
    protected $msg;

    /**
     长宽高重状态付款方式付款状态
     * Here we can do anything from DBO...
     * @return string The message to be displayed to the user
     */
    public function insertNewWaybill($data) {
      echo "写入WB";
      try {
        $resAddrSent = $this->insertNewAddress($data["uid"], $data["name_send"],
          $data["addr_send_stre"], $data["addr_send_post"], $data["addr_send_city"],
          $data["addr_send_stat"], $data["addr_send_cnty"], $data["phone_send"]);
        $resAddrRecv = $this->insertNewAddress($data["uid"], $data["name_recv"],
          $data["addr_recv_stre"], $data["addr_recv_post"], $data["addr_recv_city"],
          $data["addr_recv_stat"], $data["addr_recv_cnty"], $data["phone_recv"]);
        echo '<pre>';
        var_dump($resAddrSent);
        var_dump($resAddrRecv);
        echo '</pre>';
        return true;
      } catch (Exception $e) {
        //throw $e;
        return false;
      }
    }

    /**
     * @return last inserted id and inserted values for later verification
     */
    private function insertNewAddress($address_user_id, $address_firstname,
      $address_street, $address_post_code, $address_city,
      $address_state, $address_country, $address_telephone) {
        echo "写入ORDER";

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $col = array('address_user_id', 'address_firstname',
          'address_street', 'address_post_code', 'address_city',
          'address_telephone', 'address_state', 'address_country');

        $values = array($db->quote($address_user_id), $db->quote($address_firstname),
        $db->quote($address_street), $db->quote($address_post_code), $db->quote($address_city),
        $db->quote($address_state), $db->quote($address_country), $db->quote($address_telephone));

        $res = false;
        $query->insert($db->quoteName('#__hikashop_address'))
          ->columns($db->quoteName($col))
          ->values(implode(',', $values));
        $db->setQuery($query);
        $res = $db->execute();
        //var_dump($res);

        // $queryId = $db->getQuery(true);
        // $queryId->select('LAST_INSERT_ID()');
        // $db->setQuery($queryId);
        // $id = $db->execute();
        $id = $db->insertid();
        //var_dump($id);

        $verif = $this->verifyAddress($id, $col);
        //var_dump($verif);

        return array("inserted" => (boolean)$res, "id" => (integer)$id, "verifyInfo" => $verif);
    }

    private function verifyAddress($id, $col) {
      echo "验证插入地址";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);
      $queryVerif = $db->getQuery(true);
      $queryVerif->select($col);
      $queryVerif->from($db->quoteName('#__hikashop_address'));
      $queryVerif->where('address_user_id = '.(integer)$id);
      $db->setQuery($queryVerif);

      return $db->loadObjectList();
    }
}
