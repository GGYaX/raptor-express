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
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "写入WayBill<br>";
      try {
        $resAddrSent = $this->insertNewAddress($data["uid"], $data["name_send"],
          $data["addr_send_stre"], $data["addr_send_post"], $data["addr_send_city"],
          $data["addr_send_stat"], $data["addr_send_cnty"], $data["phone_send"]);
        $resAddrRecv = $this->insertNewAddress($data["uid"], $data["name_recv"],
          $data["addr_recv_stre"], $data["addr_recv_post"], $data["addr_recv_city"],
          $data["addr_recv_stat"], $data["addr_recv_cnty"], $data["phone_recv"]);
        $resPackage = $this->insertNewPackage($resAddrSent["id"], $resAddrRecv["id"],
          $data["insu_amnt"], $data["solution"],
          $data["weight"], $data["height"], $data["length"],
          $data["width"], $data["comment"]);
          //FIXME Calculate price
        $resOrder = $this->insertNewOrder(9999, $resPackage["id"],
          $data["uid"], $data["product"]);
        $resIDCards = $this->insertNewIDCards($data["uid"], $data["id_recto"],
          $data["id_verso"], $resOrder["id"]);
        if($GLOBALS['WAYBILLTOOL_DEBUG']){
          echo '<pre>';
          var_dump($resAddrSent);
          echo '</pre>';
          echo '<pre>';
          var_dump($resAddrRecv);
          echo '</pre>';
          echo '<pre>';
          var_dump($resPackage);
          echo '</pre>';
          echo '<pre>';
          var_dump($resOrder);
          echo '</pre>';
          echo '<pre>';
          var_dump($resOrder);
          echo '</pre>';
        }
        return array("ok"=>true, "oid"=>$resOrder["id"]);
      } catch (Exception $e) {
        /* DEBUG */
        if($GLOBALS['WAYBILLTOOL_DEBUG']) throw $e;
        return array("ok"=>false);
      }
    }



    /**
     * @return last inserted id and inserted values for later verification
     */
    private function insertNewAddress($address_user_id, $address_firstname,
      $address_street, $address_post_code, $address_city,
      $address_state, $address_country, $address_telephone) {
        if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "写入Address<br>";

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $col = array('address_user_id', 'address_firstname',
          'address_street', 'address_post_code', 'address_city',
          'address_telephone', 'address_state', 'address_country');

        $values = array($db->quote($address_user_id), $db->quote($address_firstname),
          $db->quote($address_street), $db->quote($address_post_code), $db->quote($address_city),
          $db->quote($address_state), $db->quote($address_country), $db->quote($address_telephone));

        $query->insert($db->quoteName('#__hikashop_address'))
          ->columns($db->quoteName($col))
          ->values(implode(',', $values));
        $db->setQuery($query);
        $res = $db->execute();
        // var_dump($res);

        // $queryId = $db->getQuery(true);
        // $queryId->select('LAST_INSERT_ID()');
        // $db->setQuery($queryId);
        // $id = $db->execute();
        $id = $db->insertid();
        // var_dump($id);

        $verif = $this->verifyAddress($id, $col);
        // var_dump($verif);

        return array("inserted" => (boolean)$res, "id" => (integer)$id, "verifyInfo" => $verif);
    }

    private function verifyAddress($id, $col) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "验证插入地址<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);
      $queryVerif = $db->getQuery(true);
      $queryVerif->select($col);
      $queryVerif->from($db->quoteName('#__hikashop_address'));
      $queryVerif->where('address_id = '.(integer)$id);
      $db->setQuery($queryVerif);

      return $db->loadObjectList();
    }

    /**
    * @return last inserted id and inserted values for later verification
    */
    private function insertNewPackage($sender_id, $recipient_id,
    $insured_amount, $express_mode,
    $weight, $height, $length,
    $wide, $comment,
    $package_stat="DDJ") {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "写入PACKAGE<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);

      $col = array('sender_id', 'recipient_id',
        'package_stat', 'insured_amount', 'express_mode',
        'weight', 'height', 'length',
        'wide', 'comment');

      $values = array($db->quote($sender_id), $db->quote($recipient_id),
        $db->quote($package_stat), $db->quote($insured_amount), $db->quote($express_mode),
        $db->quote($weight), $db->quote($height), $db->quote($length),
        $db->quote($wide), $db->quote($comment));

      $query->insert($db->quoteName('t_packages'))
        ->columns($db->quoteName($col))
        ->values(implode(',', $values));
      $db->setQuery($query);
      $res = $db->execute();
      // var_dump($res);

      $id = $db->insertid();
      // var_dump($id);

      $verif = $this->verifyPackage($id, $col);
      // var_dump($verif);

      return array("inserted" => (boolean)$res, "id" => (integer)$id, "verifyInfo" => $verif);
    }

    private function verifyPackage($id, $col) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "验证插入包裹<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);
      $queryVerif = $db->getQuery(true);
      $queryVerif->select($col);
      $queryVerif->from($db->quoteName('t_packages'));
      $queryVerif->where('package_id = '.(integer)$id);
      $db->setQuery($queryVerif);

      return $db->loadObjectList();
    }

    /**
    * @return last inserted id and inserted values for later verification
    */
    private function insertNewOrder($payment_amount,
      $package_id, $client_id, $express_type,
      $payment_stat="WFK", $media_code="ONL") {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "写入ORDER<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);

      $col = array('payment_stat', 'order_time',
      'payment_amount', 'media_code', 'package_id',
      'client_id', 'express_type');

      $values = array($db->quote($payment_stat), "NOW()",
      $db->quote($payment_amount), $db->quote($media_code), $db->quote($package_id),
      $db->quote($client_id), $db->quote($express_type));

      $query->insert($db->quoteName('t_orders'))
      ->columns($db->quoteName($col))
      ->values(implode(',', $values));
      $db->setQuery($query);
      $res = $db->execute();
      // var_dump($res);

      $id = $db->insertid();
      // var_dump($id);

      $verif = $this->verifyOrder($id, $col);
      // var_dump($verif);

      return array("inserted" => (boolean)$res, "id" => (integer)$id, "verifyInfo" => $verif);
    }

    private function verifyOrder($id, $col) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "验证插入订单<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);
      $queryVerif = $db->getQuery(true);
      $queryVerif->select($col);
      $queryVerif->from($db->quoteName('t_orders'));
      $queryVerif->where('order_id = '.(integer)$id);
      $db->setQuery($queryVerif);

      return $db->loadObjectList();
    }

    /**
    * @return last inserted id and inserted values for later verification
    */
    private function insertNewIDCards($user_id, $filename_recto,
    $filename_verso, $order_id) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "写入IDCARDS<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);

      $col = array('user_id', 'filename_recto',
      'filename_verso', 'order_id');

      $values = array($db->quote($user_id), $db->quote($filename_recto),
      $db->quote($filename_verso), $db->quote($order_id));

      $query->insert($db->quoteName('t_id_cards'))
      ->columns($db->quoteName($col))
      ->values(implode(',', $values));
      $db->setQuery($query);
      $res = $db->execute();
      // var_dump($res);

      $id = $db->insertid();
      // var_dump($id);

      $verif = $this->verifyIdCards($id, $col);
      // var_dump($verif);

      return array("inserted" => (boolean)$res, "id" => (integer)$id, "verifyInfo" => $verif);
    }

    private function verifyIDCards($id, $col) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "验证插入身份证<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);
      $queryVerif = $db->getQuery(true);
      $queryVerif->select($col);
      $queryVerif->from($db->quoteName('t_id_cards'));
      $queryVerif->where('order_id = '.(integer)$id);
      $db->setQuery($queryVerif);

      return $db->loadObjectList();
    }
}
