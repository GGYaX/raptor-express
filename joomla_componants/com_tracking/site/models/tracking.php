<?php
defined('_JEXEC') or die('404');

jimport('joomla.application.component.modelitem');

class TrackingModelTracking extends JModelItem
{

    /**
     *
     * @var string msg
     */
    protected $msg;

    protected $trackingInfo;

    private $localInfo;

    private $emsInfo;


    /**
     */
    public function getTrackingInfo ()
    {
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->post->get('trackingnumber');

        /*
         * tracking info如果有错误，则返回一个错误的objet
         * 错误描述：
         * local_error(1***) : 需要返回“单号不存在”
         * remote_error(2***) : 需要返回remote错误信息
         */

        if (isset($id) && ! is_array($this->trackingInfo)) {

            $this->trackingInfo = array();
            // fetch local info
            $localinfo = $this->getLocalInfo($id);

            if (!isset($localinfo['package_id'])) {
                $this->trackingInfo = array(
                        "error" => '1000'
                );
            } else {
                // fetch ems info
                $emsInfo = $this->getEmsInfo($localinfo['package_id']);
                /*
                 * kuaidi100 接口：
                 * status:
                 * 0：物流单暂无结果，
                 * 1：查询成功，
                 * 2：接口出现异常，
                 */
                if (! isset($emsInfo['status']) ||
                         ($emsInfo['status'] != '1') && $emsInfo['status'] == '0') {
                    $this->trackingInfo['error'] = '2000';
                }

                $this->trackingInfo = array_merge($localinfo, $emsInfo);
                $this->trackingInfo['order_id'] = $id;
            }
        }
        // var_dump($this->trackingInfo);
        return $this->trackingInfo;
    }

    private function getLocalInfo ($id)
    {
        $db = JFactory::getDBO();
        $queryOrder = $db->getQuery(true);
        $query = $db->getQuery(true);

        $queryOrder->select('package_id')
            ->from('t_orders')
            ->where('order_id = ' . $db->quote($id));

        $query->select(
                'package_id,DDJ_DATE,RKK_DATE,CKK_DATE,YSZ_DATE,DQG_DATE,QGC_DATE,GNP_DATE')
            ->from('t_shipping_historic')
            ->where('package_id = ' . '(' . $queryOrder->__toString() . ')');

        $db->setQuery((string) $query);
        $messages = $db->loadObject();

        return (array) $messages;
    }

    private function getEmsInfo ($id)
    {
        $uri = 'http://kuaidi100.com/query?type=ems&postid=' . $id;
        /* Initialisation de la ressource curl */
        $c = curl_init();
        /* On indique à curl quelle url on souhaite télécharger */
        curl_setopt($c, CURLOPT_URL, $uri);
        /*
         * On indique à curl de nous retourner le contenu de la requête plutôt
         * que de l'afficher
         */
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        /*
         * On indique à curl de ne pas retourner les headers http de la réponse
         * dans la chaine de retour
         */
        curl_setopt($c, CURLOPT_HEADER, false);
        /*
         * On indique à curl de suivre les redirections par le header http
         * location
         */
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        /* On execute la requete */
        $res = curl_exec($c);
        $resJSON = new JResponseJson($res);
        /* On ferme la ressource */
        curl_close($c);

        return json_decode($resJSON, true);
    }

    public function getErrorCodeLibelle ()
    {
        return '{"1000":"法国中邮单号不存在","2000":"快递接口返回错误"}';
    }
}
