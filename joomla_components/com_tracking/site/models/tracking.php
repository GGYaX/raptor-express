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

            $idTechR = $this->unmappingId(strtoupper($id));
            if (isset($idTechR['error'])) {
                // 直接尝试快递100接入
                $emsInfo = $this->getEmsInfo($id);
                $emsInfoEncoded = json_decode($emsInfo['data'], true);
                if(!isset($emsInfoEncoded['nu'])) {
                    $this->trackingInfo = array(
                            "error" => '1000',
                            "order_id" => $id
                    );
                } else {
                    $this->trackingInfo = $emsInfo;
                    $this->trackingInfo['order_id'] = $id;
                }
            } else {
                $idTech = $idTechR['idTech'];
                $package = $this->getPackageInfo($idTech);
                // fetch local info
                $localinfo = $this->getLocalInfo($package['package_id']);

                if (! isset($localinfo['package_id'])) {
                    $this->trackingInfo = array(
                            "error" => '1000',
                            "order_id" => $id
                    );
                } else {
                    // fetch ems info
                    $emsInfo = $this->getEmsInfo($package['express_id']);
                    /*
                     * kuaidi100 接口：
                     * status:
                     * 0：物流单暂无结果，
                     * 1：查询成功，
                     * 2：接口出现异常，
                     */
                    if (! isset($emsInfo['status']) || ($emsInfo['status'] != '1') &&
                             $emsInfo['status'] == '0') {
                        $this->trackingInfo['error'] = '2000';
                    }

                    $this->trackingInfo = array_merge($localinfo, $emsInfo);
                    $this->trackingInfo['order_id'] = $id;
                }
            }
        }
        // var_dump($this->trackingInfo);
        return $this->trackingInfo;
    }

    private function getPackageInfo ($id)
    {
        $db = JFactory::getDBO();
        $queryOrder = $db->getQuery(true);
        $query = $db->getQuery(true);

        $queryOrder->select('package_id')
            ->from('t_orders')
            ->where('order_id = ' . $db->quote($id));

        $query->select('package_id,express_id')
        ->from('t_packages')
        ->where('package_id = ' . '(' . $queryOrder->__toString() . ')');

        $db->setQuery((string) $query);
        $messages = $db->loadObject();

        return (array) $messages;
    }

    private function getLocalInfo ($packageId)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select(
                'package_id,DDJ_DATE,RKK_DATE,CKK_DATE,YSZ_DATE,DQG_DATE,QGC_DATE,GNP_DATE')
            ->from('t_shipping_historic')
            ->where('package_id = ' . $db->quote($packageId));

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
        return '{"1000":"快递单号不存在","2000":"快递接口返回错误"}';
    }

    /**
     * 用来转换数据库id(tech id)跟打印id(id fonctionnel)
     */
    private function getOrderIdUnmapping ()
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
                'M' => '9'
        );
    }

    /**
     * 用来转换数据库id(tech id)跟打印id(id fonctionnel)
     */
    private function getOrderIdMapping ()
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

    /**
     * length = 12
     *
     * @param unknown $id
     * @return multitype:number
     */
    private function unmappingId ($id)
    {
        $r = array();
        try {
            if (strlen($id) == 12 && 'FR' == substr($id, 0, 2)) {
                $idTechStr = '';
                $unmapping = $this->getOrderIdUnmapping();
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
}
