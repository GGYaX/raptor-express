<?php
defined('_JEXEC') or die('404');
jimport('joomla.application.component.modelitem');
class WaybillToolModelWaybillAdmin extends JModelItem {


  public function getUserInfo() {
    try {
      $db = JFactory::getDBO();
      $query = $db->getQuery(true);
      $query->select('*');
      $query->from('#__users');
      // $query->join('t_orders o ON id=o.client_id')
      // ->
      $query->where('block=0');
      $query->order('id ASC');
      $db->setQuery((string)$query);
      $list = $db->loadObjectList();

      if($GLOBALS['WAYBILLTOOL_DEBUG']){
        echo '<pre>';
        var_dump($list);
        echo '</pre>';
      }
      return $list;
    } catch (Exception $e) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) throw $e;
      return false;
    }
  }

    /**
     * Here we can load anything from DBO...
     * @return string The message to be displayed to the user
     */
    public function getOrders($uid = null, $oid = null) {
       try {
         $db = JFactory::getDBO();
         $query = $db->getQuery(true);
         $query->select('*, o.order_id as oid,
         send.address_id as send_id,
         send.address_firstname as send_name,
         send.address_street as send_street,
         send.address_post_code as send_post_code,
         send.address_city as send_city,
         send.address_state as send_state,
         send.address_country as send_country,
         send.address_telephone as send_telephone,

         recv.address_id as recv_id,
         recv.address_firstname as recv_name,
         recv.address_street as recv_street,
         recv.address_post_code as recv_post_code,
         recv.address_city as recv_city,
         recv.address_state as recv_state,
         recv.address_country as recv_country,
         recv.address_telephone as recv_telephone,

         wide as width
         ');
         $query->from('t_orders o');
         $query->join('NATURAL', 't_packages p')
         ->join('INNER', '#__hikashop_address send ON send.address_id=sender_id')
         ->join('INNER', '#__hikashop_address recv ON recv.address_id=recipient_id')
         ->join('LEFT', 't_id_cards t ON t.order_id=o.order_id');
         $whereclause = '1';
         if ($uid === null) {
           $whereclause = 'client_id = '.$uid;
           //$whereclause = $whereclause . ' AND order_id = '.$oid;
         }
         $query->where($whereclause);
         $query->order('package_id DESC');
         $db->setQuery((string)$query);
         $list = $db->loadObjectList();

         if($GLOBALS['WAYBILLTOOL_DEBUG']){
           echo '<pre>';
           var_dump($list);
           echo '</pre>';
         }
         return $list;
       } catch (Exception $e) {
         if($GLOBALS['WAYBILLTOOL_DEBUG']) throw $e;
         return false;
       }
    }


    /**
    * UPDATE
    * @return
    */
    public function updateWaybill($data) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "写入WayBill<br>";
      try {
        $resAddrSent = $this->updateAddress($data["send_id"],
          $data["name_send"],
          $data["addr_send_stre"], $data["addr_send_post"], $data["addr_send_city"],
          $data["addr_send_stat"], $data["addr_send_cnty"], $data["phone_send"]);

        $resAddrRecv = $this->updateAddress($data["recv_id"],
          $data["name_recv"],
          $data["addr_recv_stre"], $data["addr_recv_post"], $data["addr_recv_city"],
          $data["addr_recv_stat"], $data["addr_recv_cnty"], $data["phone_recv"]);

        $resPackage = $this->updatePackage($data["package_id"],
          $data["send_id"], $data["recv_id"],
          $data["insu_amnt"], $data["solution"],
          $data["weight"], $data["height"], $data["length"],
          $data["width"], $data["cargo_info"], $data["comment"],
          $data["package_stat"], $data["express_id"]);

        $resOrder = $this->updateOrder($data["order_id"],
          $data["payment_amount"], $data["product"], $data["payment_stat"]);

        if((!$data["id_recto"]) && (!$data["id_verso"])){
          $resIDCards = $this->updateIDCards($data["id_card_id"],
            $data["id_recto"], $data["id_verso"]);
        }
        if($GLOBALS['WAYBILLTOOL_DEBUG']){
          echo '<pre>';
          echo 'ADDRESS_SEND<br>';
          var_dump($resAddrSent);
          echo '</pre>';
          echo '<pre>';
          echo 'ADDRESS_RECV<br>';
          var_dump($resAddrRecv);
          echo '</pre>';
          echo '<pre>';
          echo 'PKG<br>';
          var_dump($resPackage);
          echo '</pre>';
          echo '<pre>';
          echo 'ORDER<br>';
          var_dump($resOrder);
          echo '</pre>';
          echo '<pre>';
          echo 'IDCARD<br>';
          var_dump($resIDCards);
          echo '</pre>';
        }
        return array("ok"=>true, "info"=>$resOrder);
      } catch (Exception $e) {
        /* DEBUG */
        if($GLOBALS['WAYBILLTOOL_DEBUG']) throw $e;
        return array("ok"=>false);
      }
    }



    /**
    * @return
    */
    private function updateAddress($address_id,
    $address_firstname,
    $address_street, $address_post_code, $address_city,
    $address_state, $address_country, $address_telephone) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "修改Address<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);

      $fields = array(
        $db->quoteName('address_firstname').'='.$db->quote($address_firstname),
        $db->quoteName('address_street').'='.$db->quote($address_street),
        $db->quoteName('address_post_code').'='.$db->quote($address_post_code),
        $db->quoteName('address_city').'='.$db->quote($address_city),
        $db->quoteName('address_telephone').'='.$db->quote($address_telephone),
        $db->quoteName('address_state').'='.$db->quote($address_state),
        $db->quoteName('address_country').'='.$db->quote($address_country)
        );

      $conditions = array(
          $db->quoteName('address_id') . ' = ' . $address_id
        );

      $query->update($db->quoteName('#__hikashop_address'))
        ->set($fields)
        ->where($conditions);

      $db->setQuery($query);
      $res = $db->execute();
      // var_dump($res);

      return array("updated" => $res);
    }

    /**
    * @return
    */
    private function updatePackage($package_id,
    $sender_id, $recipient_id,
    $insured_amount, $express_mode,
    $weight, $height, $length,
    $wide, $cargo_info, $comment, $package_stat, $express_id) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "修改PACKAGE<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);

      $fields = array(
        $db->quoteName('sender_id').'='.$db->quote($sender_id),
        $db->quoteName('recipient_id').'='.$db->quote($recipient_id),
        $db->quoteName('insured_amount').'='.$db->quote($insured_amount),
        $db->quoteName('express_mode').'='.$db->quote($express_mode),
        $db->quoteName('weight').'='.$db->quote($weight),
        $db->quoteName('height').'='.$db->quote($height),
        $db->quoteName('length').'='.$db->quote($length),
        $db->quoteName('wide').'='.$db->quote($wide),
        $db->quoteName('cargo_info').'='.$db->quote($cargo_info),
        $db->quoteName('comment').'='.$db->quote($comment),
        $db->quoteName('package_stat').'='.$db->quote($package_stat),
        $db->quoteName('express_id').'='.$db->quote($express_id)
      );

      $conditions = array(
        $db->quoteName('package_id') . ' = ' . $package_id
      );

      $query->update($db->quoteName('t_packages'))
        ->set($fields)
        ->where($conditions);

      $db->setQuery($query);
      $res = $db->execute();
      // var_dump($res);

      return array("updated" => $res);
    }

    /**
    * @return
    */
    private function updateOrder($order_id,
    $payment_amount, $express_type,
    $payment_stat) {//, $media_code="ONL"
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "修改ORDER<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);

      $fields = array(
        $db->quoteName('payment_stat').'='.$db->quote($payment_stat),
        $db->quoteName('payment_amount').'='.$db->quote($payment_amount),
        $db->quoteName('express_type').'='.$db->quote($express_type),
        $db->quoteName('payment_stat').'='.$db->quote($payment_stat)
      );

      $conditions = array(
        $db->quoteName('order_id') . ' = ' . $order_id
      );

      $query->update($db->quoteName('t_orders'))
        ->set($fields)
        ->where($conditions);

      $db->setQuery($query);
      $res = $db->execute();
      // var_dump($res);

      return array("updated" => $res);
    }

    /**
    * @return
    */
    private function updateIDCards($id_card_id,
    $filename_recto, $filename_verso) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "修改IDCARDS<br>";

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);

      $fields = array(
        $db->quoteName('filename_recto').'='.$db->quote($filename_recto),
        $db->quoteName('filename_verso').'='.$db->quote($filename_verso)
      );

      $conditions = array(
        $db->quoteName('id_card_id') . ' = ' . $id_card_id
      );

      $query->update($db->quoteName('t_id_cards'))
      ->set($fields)
      ->where($conditions);

      $db->setQuery($query);
      $res = $db->execute();
      // var_dump($res);

      return array("updated" => $res);
    }
}
