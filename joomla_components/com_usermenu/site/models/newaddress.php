<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class UsermenuModelNewaddress extends JModelItem
{

    protected $msg;

    protected $errorsMsg;

    public function getMsg ()
    {
        return "newaddress";
    }

    public function getInsert ()
    {
        $jinput = JFactory::getApplication()->input;
        $jformArray = $jinput->post->getArray();
        $jform = $jformArray['jform'];
        $params = array(
                'name' => $jform['name'],
                'street' => $jform['street'],
                'zip_code' => $jform['zip_code'],
                'country' => $jform['country'],
                'city' => $jform['city'],
                'phone' => $jform['phone']
        );
        $labels = $this->getLabels();
        $errors = array();
        foreach ($params as $k => $v) {
            if (! isset($v)) {
                array_push($errors,
                        $labels[$k] . ' ' .
                                 JText::_(
                                        'COM_USERMENU_VIEW_FRONT_NEWADDRESS_INSERT_VALIDATION_FAILURE_SUFFIX'));
            }
        }
        // 如果没有错误，插入到数据库中
        if (empty($errors)) {
            try{
                // get current user
                $user = JFactory::getUser();

                $db = JFactory::getDBO();
                $query = 'INSERT INTO `#__hikashop_address` (`address_user_id`, `address_firstname`, `address_street`, `address_post_code`, `address_city`, `address_telephone`, `address_state`, `address_country`) VALUES (' .
                         $user->id . ',' . $db->quote($params['name']) . ',' .
                         $db->quote($params['street']) . ',' .
                         $db->quote($params['zip_code']) . ',' .
                         $db->quote($params['city']) . ',' .
                         $db->quote($params['phone']) . ',' .
                         (isset($params['state']) ? $db->quote($params['state']) : 'NULL') .
                         ',' . $db->quote($params['country']) . ');';
//                 $query = "INSERT INTO `#__hikashop_address` (`address_user_id`, `address_firstname`, `address_street`, `address_post_code`, `address_city`, `address_telephone`, `address_state`, `address_country`) VALUES (287, 'TEST', 'TEST', 'TEST','TEST','TEST','TEST','TEST');";
//                 var_dump($query->__toString());
                $db->setQuery($query);
                $db->query();
            } catch (Exception $e) {
                array_push($this->errorsMsg, $e);
                array_push($errors, JText::_('COM_USERMENU_VIEW_FRONT_NEWADDRESS_INSERT_FAILURE_SUFFIX'));
            }
        }

        // return
        if (! empty($errors)) {
            echo "<br />returing errors<br />";
            var_dump($errors);
            return array(
                    'errors' => $errors
            );
        }
        return array(
                'succes' => JText::_(
                        'COM_USERMENU_VIEW_FRONT_NEWADDRESS_INSERT_SUCCES')
        );
    }

    public function getLabels ()
    {
        return array(
                'name' => JText::_(
                        'COM_USERMENU_VIEW_FRONT_NEWADDRESS_NAME_INPUT_LABEL'),
                'street' => JText::_(
                        'COM_USERMENU_VIEW_FRONT_NEWADDRESS_STREET_INPUT_LABEL'),
                'zip_code' => JText::_(
                        'COM_USERMENU_VIEW_FRONT_NEWADDRESS_ZIP_CODE_INPUT_LABEL'),
                'country' => JText::_(
                        'COM_USERMENU_VIEW_FRONT_NEWADDRESS_COUNTRY_INPUT_LABEL'),
                'city' => JText::_(
                        'COM_USERMENU_VIEW_FRONT_NEWADDRESS_CITY_INPUT_LABEL'),
                'phone' => JText::_(
                        'COM_USERMENU_VIEW_FRONT_NEWADDRESS_PHONE_INPUT_LABEL')
        );
    }

    public function getErrors() {
        return $this->errorsMsg;
    }
}