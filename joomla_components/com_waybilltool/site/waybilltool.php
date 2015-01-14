<?php

  defined('_JEXEC') or die;

  $GLOBALS['WAYBILLTOOL_DEBUG'] = true;

  require_once JPATH_COMPONENT.DS.'helpers'.DS.'waybillparamscheckerhelper.php';
  require_once JPATH_COMPONENT.DS.'helpers'.DS.'solutionchooserhelper.php';
  require_once JPATH_COMPONENT.DS.'helpers'.DS.'userwallethelper.php';

  $controller = JControllerLegacy::getInstance('WaybillTool');

  $input = JFactory::getApplication()->input;

  $controller->execute('');
  $controller->redirect();


  // send.address_firstname as send_name,
  // send.address_street as send_street,
  // send.address_post_code as send_post_code,
  // send.address_city as send_city,
  // send.address_state as send_state,
  // send.address_country as send_country,
  // send.address_telephone as send_telephone,
  //
  // recv.address_firstname as recv_name,
  // recv.address_street as recv_street,
  // recv.address_post_code as recv_post_code,
  // recv.address_city as recv_city,
  // recv.address_state as recv_state,
  // recv.address_country as recv_country,
  // recv.address_telephone as recv_telephone,
  //
  // wide as width
  // ');
  // $query->from('t_packages p');
  // $query->join('NATURAL', 't_orders o')
  // ->join('NATURAL', 't_id_cards id')
  // ->join('INNER', '#__hikashop_address send ON send.address_id=sender_id')
  // ->join('INNER', '#__hikashop_address recv ON recv.address_id=recipient_id');


  // $allParams["name_send"] = $jinput->get("name_send", null);
  // $allParams["addr_send"] = $jinput->get("addr_send", null);
  // $allParams["phone_send"] = $jinput->get("phone_send", null);
  //
  //
  // $allParams["name_recv"] = $jinput->get("name_recv", null);
  // $allParams["addr_recv"] = $jinput->get("addr_recv", null);
  // $allParams["phone_recv"] = $jinput->get("phone_recv", null);
  //
  // $allParams["insu_amnt"] = $jinput->get("insu_amnt", null);
  // $allParams["product"] = $jinput->get("exp-product", null);
  //
  // $allParams["comment"] = $jinput->get("comment", null);
  //
  // $allParams["weight"] = $jinput->get("weight", null);
  // $allParams["length"] = $jinput->get("length", null);
  // $allParams["width"] = $jinput->get("width", null);
  // $allParams["height"] = $jinput->get("height", null);
  //
  // $comment2 = $jinput->get("comment2", null);
  //
  // $allParams["id_recto"] = $jinput->get("id_recto", null);
  // $allParams["id_verso"] = $jinput->get("id_verso", null);
