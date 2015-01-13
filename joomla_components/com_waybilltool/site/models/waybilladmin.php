<?php
defined('_JEXEC') or die('404');
jimport('joomla.application.component.modelitem');
class WaybillToolModelWaybillAdmin extends JModelItem {
    /**
     * @var string msg
     */
    protected $msg;
    /**
     长宽高重状态付款方式付款状态
     * Here we can load anything from DBO...
     * @return string The message to be displayed to the user
     */
     public function getAllOrders($uid = null, $solution, $oid = null) {
       try {
         $db = JFactory::getDBO();
         $query = $db->getQuery(true);
         $query->select('*,
         send.address_firstname as send_name,
         send.address_street as send_street,
         send.address_post_code as send_post_code,
         send.address_city as send_city,
         send.address_state as send_state,
         send.address_country as send_country,
         send.address_telephone as send_telephone,

         recv.address_firstname as recv_name,
         recv.address_street as recv_street,
         recv.address_post_code as recv_post_code,
         recv.address_city as recv_city,
         recv.address_state as recv_state,
         recv.address_country as recv_country,
         recv.address_telephone as recv_telephone,

         wide as width
         ');
         $query->from('t_packages p');
         $query->join('NATURAL', 't_orders o')
         ->join('NATURAL', 't_id_cards id')
         ->join('INNER', '#__hikashop_address send ON send.address_id=sender_id')
         ->join('INNER', '#__hikashop_address recv ON recv.address_id=recipient_id');
         $whereclause = 'client_id = '.$uid.' AND express_mode = \''.$solution.'\'';
         if ($oid === null) {
           //$whereclause = $whereclause . ' AND order_id = '.$oid;
         }
         $query->where($whereclause);
         $query->order('package_id DESC');
         $db->setQuery((string)$query);
         $list = $db->loadObjectList();
         return $list;
       } catch (Exception $e) {
         return false;
       }
     }
}
