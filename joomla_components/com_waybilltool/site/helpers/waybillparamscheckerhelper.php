<?php
/**
 * @package Joomla.Administrator
 * @subpackage com_waybilltool
 *
 * @copyright Copyright (C) 2014
 * @license GNU??? BSD???
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
/**
 * @since 1.0.0
 */
class WaybillParamsCheckerHelper {

  public static function checkCreateParams($uid,
      $solution = 'exp-solution', $creation = 'exp-creation') {
    $jinput = JFactory::getApplication()->input;

    $solution = $jinput->get($solution, null);
    $creation = $jinput->get($creation, null);

    $allParams = array();


    $allParams["uid"] = $uid;

    $allParams["solution"] = $solution;

    $allParams["name_send"] = $jinput->get("name_send", null);
    $allParams["addr_send_stre"] = $jinput->get("addr_send_stre", null);
    $allParams["addr_send_post"] = $jinput->get("addr_send_post", null);
    $allParams["addr_send_city"] = $jinput->get("addr_send_city", null);
    $allParams["addr_send_stat"] = $jinput->get("addr_send_stat", null);
    $allParams["addr_send_cnty"] = $jinput->get("addr_send_cnty", null);
    $allParams["phone_send"] = $jinput->get("phone_send", null);


    $allParams["name_recv"] = $jinput->get("name_recv", null);
    $allParams["addr_recv_stre"] = $jinput->get("addr_recv_stre", null);
    $allParams["addr_recv_post"] = $jinput->get("addr_recv_post", null);
    $allParams["addr_recv_city"] = $jinput->get("addr_recv_city", null);
    $allParams["addr_recv_stat"] = $jinput->get("addr_recv_stat", null);
    $allParams["addr_recv_cnty"] = $jinput->get("addr_recv_cnty", null);
    $allParams["phone_recv"] = $jinput->get("phone_recv", null);

    $allParams["insu_amnt"] = $jinput->get("insu_amnt", null);
    $allParams["product"] = $jinput->get("exp-product", null);

    $allParams["comment"] = $jinput->get("comment", null);

    $allParams["weight"] = $jinput->get("weight", null);
    $allParams["length"] = $jinput->get("length", null);
    $allParams["width"] = $jinput->get("width", null);
    $allParams["height"] = $jinput->get("height", null);

    $comment2 = $jinput->get("comment2", null);

    $allParams["id_recto"] = $jinput->get("id_recto", "");
    $allParams["id_verso"] = $jinput->get("id_verso", "");

    $checked = true;

    foreach ($allParams as $key => $value){
      if($value === null) {
        $checked = false;
        break;
      }
    }
    if( ($solution === "EMS")
          && (
            ($allParams["id_recto"] === "")
              || ($allParams["id_verso"] === "")
          )
    ) {
      $checked = false;
    }
    if($checked) {
      $allParams["comment"] = $allParams["comment"]." ||||||备注信息 : ".$comment2;
      //var_dump($allParams);
    }

    return $checked ? $allParams : null;
  }

  public static function checkUsershowParams($cachable = false, $urlparams = false) {
    echo "WaybillParamscheckerhelper : Usershow";
  }


  public static function checkAdminParams($cachable = false, $urlparams = false) {
    echo "WaybillParamscheckerhelper : Admin";
  }


  /**
  * 用来转换数据库id(tech id)跟打印id(id fonctionnel)
  */
  private static function getOrderIdMapping ()
  {
    return array(
      '0' => 'O',
      '1' => 'U',
      '2' => 'T',
      '3' => 'E',
      '4' => 'D',
      '5' => 'S',
      '6' => 'B',
      '7' => 'Z',
      '8' => 'P',
      '9' => 'M'
    );
  }

  private static function getOrderIdUnMapping ()
  {
    return array(
      'O' => '0',
      'U' => '1',
      'T' => '2',
      'E' => '3',
      'D' => '4',
      'S' => '5',
      'B' => '6',
      'Z' => '7',
      'P' => '8',
      'M' => '9',
      '0' => '0' //Avoid Notice undefined offset
    );
  }

  /**
  * length = 12
  *
  * @param unknown $id
  * @return multitype:number
  */
  public static function unmappingId ($id)
  {
    $r = array();
    try {
      if (strlen($id) == 12 && 'FR' == substr($id, 0, 2)) {
        $idTechStr = '';
        $unmapping = self::getOrderIdUnMapping();
        for ($i = 2; $i < strlen($id); $i ++) {
          $idTechStr = $idTechStr . $unmapping[$id[$i]];
        }
        $r['idTech'] = intval($idTechStr);
      } else {
        $r['error'] = 1000;
      }
    } catch (Exception $e) {
      JLog::add(implode('<br />', $e), JLog::WARNING, 'jerror');
      $r['error'] = 1000;
    }
    return $r;
  }

  /**
  * length = 12
  *
  * @param unknown $id
  * @return multitype:number
  */
  public static function mappingId ($order_id)
  {
    $toReturn = 'FR';
    $mapping = self::getOrderIdMapping();
    $stringOrderId = strval($order_id);
    for ($i = 0; $i < strlen($stringOrderId); $i ++) {
      $stringOrderId[$i] = $mapping[$stringOrderId[$i]];
    }
    return $toReturn . str_pad($stringOrderId, 10, '0', STR_PAD_LEFT);
  }

}
