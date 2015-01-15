<?php
defined('_JEXEC') or die('404');
jimport('joomla.application.component.modelitem');
class WaybillToolModelWaybillCreate extends JModelItem {

    // la poste报价
    protected $laposteArray = array(
            '1KG'=>21,
            '2KG'=>28,
            '3KG'=>31,
            '4KG'=>32,
            '5KG'=>35,
            '6KG'=>38,
            '7KG'=>41,
            '8KG'=>51,
            '9KG'=>60,
            '10KG'=>68,
            '11KG'=>96,
            '12KG'=>96,
            '13KG'=>96,
            '14KG'=>96,
            '15KG'=>96
    );

    // ems 奶粉
    protected $enaArray = array(
            '2|900|2KG'=>19,
            '2|800|2KG'=>19,
            '2|12004KG'=>26,
            '3|800|3KG'=>21,
            '3|900|4KG'=>30,
            '3|1200|6KG'=>40,
            '4|800|4KG'=>31,
            '4|900|5KG'=>32,
            '4|1200|7KG'=>43,
            '5|800|5.5KG'=>38,
            '5|900|6KG'=>42,
            '6|800|7KG'=>43,
            '6|900|7KG'=>45
    );

    protected $emsArray = array(
            '1KG'=>11,
            '2KG'=>15,
            '3KG'=>19,
            '4KG'=>23,
            '5KG'=>27,
            '6KG'=>31,
            '7KG'=>35,
            '8KG'=>39,
            '9KG'=>43,
            '10KG'=>47,
            '11KG'=>51,
            '12KG'=>55,
            '13KG'=>59,
            '14KG'=>63,
            '15KG'=>67,
            '16KG'=>71,
            '17KG'=>75,
            '18KG'=>79,
            '19KG'=>83,
            '20KG'=>87
    );

    /**
     长宽高重状态付款方式付款状态
     * Here we can do anything from DBO...
     * @return string The message to be displayed to the user
     */
    public function insertNewWaybill($data) {
      if($GLOBALS['WAYBILLTOOL_DEBUG']) echo "写入WayBill<br>";
      try {

        $wallethelper = new UserWalletHelper();
        $priceCalculed = $this->calculPrice($data['product'],$data['height'],$data['length'],$data['width'],$data['weight'],$data['enaDemand']);
        $canInsert = true;
        if(isset($priceCalculed['error'])) {
            // 计算价格错误
            return array('ok'=>false,'msg'=>$priceCalculed['error']);
        } else {
            $walletAmount = $wallethelper->getWalletAmount();
            if($GLOBALS['WAYBILLTOOL_DEBUG']){
                echo '<pre> result';
                var_dump($priceCalculed);
                echo '</pre>';
                echo '<pre> $canInsert';
                var_dump($canInsert);
                echo '</pre>';
                echo '<pre> $walletAmount';
                var_dump($walletAmount);
                echo '</pre>';
            }
            if($data['product'] == 'ENA' || $data['product'] == 'ENO') {
                // ems
                if(floatval($walletAmount['emsAmount']) < floatval($priceCalculed['result'])) {
                    $canInsert = false;
                }
            } else {
                // la poste
                if(floatval($walletAmount['laposteAmount']) < floatval($priceCalculed['result'])) {
                    $canInsert = false;
                }
            }
        }
        if($GLOBALS['WAYBILLTOOL_DEBUG']){
            echo '<pre> result';
            var_dump($priceCalculed['result']);
            echo '</pre>';
            echo '<pre> $canInsert';
            var_dump($canInsert);
            echo '</pre>';
        }
        if($canInsert == true) {
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
            $resOrder = $this->insertNewOrder(floatval($priceCalculed['result']), $resPackage["id"],
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
        }
        // 没钱错误
        return array('ok'=>false,'msg'=>JText::_('COM_WAYBILLTOOL_VIEW_FONT_OUT_OF_AMOUNT'));
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
          $db->quote($address_telephone), $db->quote($address_state), $db->quote($address_country));

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

    /**
     * 计算价格
     */
    private function calculPrice($express_type, $high = 0, $length = 0, $width = 0, $weight = 0, $enaDemand) {
        $toReturn = array();
        if($high == 0 || $length == 0 || $width == 0 || $weight == 0) {
            $toReturn = array('error'=>JText::_('COM_WAYBILLTOOL_VIEW_FONT_NO_WAY_TO_CREATE_ORDER'));
        } else {
            if($express_type = 'EMS') {
                $weightCalculed = $this->calculeWeight($high, $length, $width, $weight);
                if($weightCalculed > 20) {
                    $toReturn = array('error'=>JText::_('COM_WAYBILLTOOL_VIEW_FONT_OUT_OF_BOUND'));
                } else {
                    $toReturn = array('result' => $this->emsArray[$weightCalculed . 'KG']);
                }
            } else if($express_type = 'ENA') {
                if(isset($enaDemand) && isset($this->enaArray[$enaDemand])) {
                    $toReturn = array('result'=>$this->enaArray[$enaDemand]);
                } else {
                    $toReturn = array('error'=>JText::_('COM_WAYBILLTOOL_VIEW_FONT_NO_WAY_TO_CREATE_ORDER'));
                }
            } else if($express_type = 'LAP') {
                if($weightCalculed > 15) {
                    $toReturn = array('error'=>JText::_('COM_WAYBILLTOOL_VIEW_FONT_OUT_OF_BOUND'));
                } else {
                    $toReturn = array('result' => $this->laposteArray[intval($weightCalculed) . 'KG']);
                }
            } else {
                $toReturn = array('error'=>JText::_('COM_WAYBILLTOOL_VIEW_FONT_NO_WAY_TO_CREATE_ORDER'));
            }
        }
        return $toReturn;
    }

    // 计算体积重
    private function calculeWeight($high = 1, $length = 1, $width = 1, $weight = 1) {
        $volumeWeight = ceil(($high *$length * $width) / 5000);
        $weightCeiled = ceil($weight);
        return intval(max($volumeWeight, $weightCeiled));
    }
}
