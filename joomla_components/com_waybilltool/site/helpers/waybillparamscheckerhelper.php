<?php
/**
 * @package Joomla.Administrator
 * @subpackage com_waybilltool
 *
 * @copyright Copyright (C) 2014
 * @license GNU??? BSD???
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

/**
 *
 * @since 1.0.0
 */
class WaybillParamsCheckerHelper
{

    public static function checkCreateParams ($uid, $solution = 'exp-solution',
            $creation = 'exp-creation')
    {
        $jinputOrigin = JFactory::getApplication()->input;

        $solution = $jinputOrigin->get($solution, null);
        $creation = $jinputOrigin->get($creation, null);

        $jinput = $jinputOrigin->post->getArray();

        $allParams = array();

        $allParams["uid"] = $uid;

        $allParams["solution"] = $solution;

        $allParams["name_send"] = $jinput["name_send"];
        $allParams["addr_send_stre"] = $jinput["addr_send_stre"];
        $allParams["addr_send_post"] = $jinput["addr_send_post"];
        $allParams["addr_send_city"] = $jinput["addr_send_city"];
        $allParams["addr_send_stat"] = $jinput["addr_send_stat"];
        $allParams["addr_send_cnty"] = $jinput["addr_send_cnty"];
        $allParams["phone_send"] = $jinput["phone_send"];

        $allParams["name_recv"] = $jinput["name_recv"];
        $allParams["addr_recv_stre"] = $jinput["addr_recv_stre"];
        $allParams["addr_recv_post"] = $jinput["addr_recv_post"];
        $allParams["addr_recv_city"] = $jinput["addr_recv_city"];
        $allParams["addr_recv_stat"] = $jinput["addr_recv_stat"];
        $allParams["addr_recv_cnty"] = $jinput["addr_recv_cnty"];
        $allParams["phone_recv"] = $jinput["phone_recv"];

        $allParams["insu_amnt"] = $jinput["insu_amnt"];
        $allParams["product"] = $jinput["exp-product"];

        $allParams["cargo_info"] = $jinput["cargo_info"];
        $allParams["comment"] = $jinput["comment"];

        $allParams["weight"] = $jinput["weight"];
        $allParams["length"] = $jinput["length"];
        $allParams["width"] = $jinput["width"];
        $allParams["height"] = $jinput["height"];

        //$comment2 = $jinput["comment2"];

        $checked = true;

        foreach ($allParams as $key => $value) {
            if ($value === null) {
                $checked = false;
                break;
            }
        }
        if (($solution === "EMS")) {
            $allParams["id_recto"] = $jinput["id_recto"];
            $allParams["id_verso"] = $jinput["id_verso"];
            if (($allParams["id_recto"] === "") ||
                     ($allParams["id_verso"] === "")) {
                $checked = false;
            }
        }
        // if ($checked) {
        //     $allParams["comment"] = $allParams["comment"] . "||||||" .
        //              $comment2;
        //     // var_dump($allParams);
        // }

        return $checked ? $allParams : null;
    }





    public static function checkUsershowParams ($cachable = false, $urlparams = false)
    {
        echo "WaybillParamscheckerhelper : Usershow";
    }





    public static function checkAdminUpdateParams ($uid)
    {

      $jinputOrigin = JFactory::getApplication()->input;

      $jinput = $jinputOrigin->post->getArray();

      $allParams = array();
      $allParams["uid"] = $uid;

      $allParams["express_id"] = $jinput["express_id"];
      $allParams["payment_stat"] = $jinput["paystat"];
      $allParams["package_stat"] = $jinput["packstat"];

      $allParams["id_card_id"] = $jinput["ididc"];
      $allParams["package_id"] = $jinput["idpkg"];
      $oid = $jinputOrigin->get("exp-oid", null);
      $allParams["order_id"] = self::unmappingId($oid)['idTech'];
      $allParams["send_id"] = $jinput["idsend"];
      $allParams["recv_id"] = $jinput["idrecv"];

      $allParams["name_send"] = $jinput["name_send"];
      $allParams["addr_send_stre"] = $jinput["addr_send_stre"];
      $allParams["addr_send_post"] = $jinput["addr_send_post"];
      $allParams["addr_send_city"] = $jinput["addr_send_city"];
      $allParams["addr_send_stat"] = $jinput["addr_send_stat"];
      $allParams["addr_send_cnty"] = $jinput["addr_send_cnty"];
      $allParams["phone_send"] = $jinput["phone_send"];

      $allParams["name_recv"] = $jinput["name_recv"];
      $allParams["addr_recv_stre"] = $jinput["addr_recv_stre"];
      $allParams["addr_recv_post"] = $jinput["addr_recv_post"];
      $allParams["addr_recv_city"] = $jinput["addr_recv_city"];
      $allParams["addr_recv_stat"] = $jinput["addr_recv_stat"];
      $allParams["addr_recv_cnty"] = $jinput["addr_recv_cnty"];
      $allParams["phone_recv"] = $jinput["phone_recv"];

      $allParams["insu_amnt"] = $jinput["insu_amnt"];

      $allParams["solution"] = $jinput["exp-solution"];
      $allParams["product"] = $jinput["exp-product"];

      $allParams["cargo_info"] = $jinput["cargo_info"];
      $allParams["comment"] = $jinput["comment"];

      $allParams["weight"] = $jinput["weight"];
      $allParams["length"] = $jinput["length"];
      $allParams["width"] = $jinput["width"];
      $allParams["height"] = $jinput["height"];

      $allParams["payment_amount"] = $jinput["price"];

      //$comment2 = $jinput["comment2"];

      $checked = true;

      foreach ($allParams as $key => $value) {
        if ($value === null) {
          $checked = false;
          break;
        }
      }
      if (($allParams["solution"] === "EMS")) {
        $allParams["id_recto"] = $jinput["id_recto"];
        $allParams["id_verso"] = $jinput["id_verso"];
        if (($allParams["id_recto"] === "") ||
        ($allParams["id_verso"] === "")) {
          //$checked = false;
          $allParams["id_recto"] = false;
          $allParams["id_verso"] = false;
        }
      } else {
        $allParams["id_recto"] = false;
        $allParams["id_verso"] = false;
      }
      // if ($checked) {
      //   $allParams["comment"] = $allParams["comment"] . "||||||" .
      //     $comment2;
      // }

      return $checked ? $allParams : null;
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
                '0' => '0'
        ) // Avoid Notice undefined offset
;
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
