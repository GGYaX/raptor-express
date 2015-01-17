<?php
/**
 * Module's entry point
 *
 * @package    com.express
 * @subpackage Modules
 * @license    ???
 */
class modPDFGenHelper
{
    public static function getData( $waybill )
    {
      $trueWaybill = self::unmappingId($waybill)['idTech'];

      try {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*, o.order_id as oid,
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
        $query->from('t_orders o');
        $query->join('NATURAL', 't_packages p')
        ->join('INNER', '#__hikashop_address send ON send.address_id=sender_id')
        ->join('INNER', '#__hikashop_address recv ON recv.address_id=recipient_id')
        ->join('LEFT', 't_id_cards t ON t.order_id=o.order_id');
        $whereclause = 'o.order_id = '.$trueWaybill;
        $query->where($whereclause);
        $query->order('package_id DESC');
        $db->setQuery((string)$query);
        $list = $db->loadObjectList();
        if($GLOBALS['WAYBILLTOOL_DEBUG']) {
          echo '<pre>';
          var_dump($query);
          var_dump($list);
          echo '</pre>';
        }
        return $list;

      } catch (Exception $e) {
        /* DEBUG */
        if($GLOBALS['WAYBILLTOOL_DEBUG'])
          throw $e;
        return false;
      }
    }
    /**
     * Retrieves the message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */
    public static function getPDF( $waybill )
    {

      $data = (array)self::getData( $waybill )[0];
      //var_dump($data);

      	require_once( dirname(__FILE__) . '/lib/tcpdf/tcpdf.php');
      	require_once( dirname(__FILE__) . '/lib/tcpdf/tcpdf_barcodes_1d.php');

      	$filename = $waybill+'.pdf';

      	// create new PDF document
      	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      	// set document information
      	$pdf->SetCreator(PDF_CREATOR);
      	$pdf->SetAuthor('Raptor Express');
      	$pdf->SetTitle($waybill);
      	$pdf->SetSubject('运单');
      	$pdf->SetKeywords('运单, PDF');

      	// set default monospaced font
      	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      	// set margins
      	$pdf->SetMargins(5, 5, 5, 5);

      	// set auto page breaks
      	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

      	// set image scale factor
      	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

      	// set some language-dependent strings (optional)
      	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
      	    require_once(dirname(__FILE__).'/lang/eng.php');
      	    $pdf->setLanguageArray($l);
      	}

      	// ---------------------------------------------------------

      	// set font
      	TCPDF_FONTS::addTTFfont(dirname(__FILE__) . '/lib/tcpdf/fonts/DroidSansFallback.ttf');

      	// add a page
      	$pdf->AddPage();
      	/*$pdf->Write(0, 'Example of SetLineStyle() method', '', 0, 'L', true, 0, false, false, 0);
      	$pdf->Ln();*/

      	$pdf->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(0, 0, 0)));
      	$pdf->SetFillColor(255,255,255);
      	$pdf->SetTextColor(0,0,0);

      	// define barcode style
      	$style = array(
      		'position' => '',
      		'align' => 'C',
      		'stretch' => false,
      		'fitwidth' => true,
      		'cellfitalign' => '',
      		'border' => false,
      		'hpadding' => 'auto',
      		'vpadding' => 'auto',
      		'fgcolor' => array(0,0,0),
      		'bgcolor' => false, //array(255,255,255),
      		'text' => true,
      		'font' => 'helvetica',
      		'fontsize' => 8,
      		'stretchtext' => 4
      	);
      	// CODE 39 + CHECKSUM
      	//$pdf->Cell(0, 0, 'CODE 39 + CHECKSUM', 0, 1);
      	$pdf->SetFont('DroidSansFallback', '', 36, '', false);
      	$x = $pdf->GetX();
      	$y = $pdf->GetY();
      	$pdf->MultiCell(85, 35, '标准快递', 1, 'C', 1, 0);
      	$now = new DateTime();
      	$now->setTimezone(new DateTimeZone('Europe/Paris'));
      	$datetime = '法国时间：'.$now->format('Y-m-d H:i:s');
      	$pdf->SetXY($x,$y);
      	$pdf->SetFont('DroidSansFallback', '', 12, '', false);
      	$pdf->Cell(85, 35, $datetime, 'LTR', 'L', 1, 0);
      	$x = $pdf->GetX();
      	$y = $pdf->GetY();
      	$pdf->write1DBarcode($data['express_id'], 'C39+', '', '', '', 33, 0.5, $style, 'N');
      	$pdf->SetXY($x,$y);
      	$pdf->SetFont('DroidSansFallback', '', 16, '', false);
      	$pdf->MultiCell(115, 35, '', 'LTR', 'C', 1, 0);

      	$pdf->Ln();
      	$senderAddr = '寄件：'."\n"." ".$data['send_name']." ".$data['send_telephone']."\n".$data['send_street']." ".$data['send_post_code']
          ." ".$data['send_city']." ".$data['send_state']." ".$data['send_country'];
      	$pdf->MultiCell(100, 30, $senderAddr, 'LTR', 'L', 1, 0);
      	$pdf->MultiCell(100, 30, '', 'LTR', 'C', 1, 0);

      	$pdf->Ln();
        $receiverAddr = '收件：'."\n"." ".$data['recv_name']." ".$data['recv_telephone']."\n".$data['recv_street']." ".$data['recv_post_code']
        ." ".$data['recv_city']." ".$data['recv_state']." ".$data['recv_country'];
      	$pdf->SetFont('DroidSansFallback', '', 20, '', false);
      	$pdf->MultiCell(200, 30, $receiverAddr, 'LTR', 'L', 1, 0);

      	$pdf->Ln();
        //FIXME Hard coded arrays
        $payment_statList = array("YFK"=>"已付款", "WFK"=>"未付款", "OTH"=>"其他");
      	$paymentInfo = '付款方式：'.$payment_statList[$data['payment_stat']]."\n".'计费重量（KG）：'.$data['weight']."\n".'保价金额（元）：'.$data['insured_amount'];
      	$pdf->SetFont('DroidSansFallback', '', 16, '', false);
      	$pdf->MultiCell(85, 30, $paymentInfo, 'LT', 'L', 1, 0);
      	$receiveInfo = '收件人\代收人：'."\n".'签收时间：          年       月      日     时'."\n".'快件送达收货人地址,经收件人或收件人允许的代收人签字，视为送达。';
      	$pdf->SetFont('DroidSansFallback', '', 17, '', false);
      	$pdf->MultiCell(115, 30, $receiveInfo, 'LTR', 'L', 1, 0);

      	$pdf->Ln();
      	$x = $pdf->GetX();
      	$y = $pdf->GetY();
      	$pdf->write1DBarcode($waybill, 'C39+', $x+30, '', '', 16, 0.4, $style, 'N');
      	$pdf->SetXY($x,$y);
      	$pdf->SetFont('DroidSansFallback', '', 16, '', false);
      	$packageInfo1 = '订单号：'."\n\n".'配货信息：'.$data['cargo_info'];
      	$pdf->MultiCell(140, 35, $packageInfo1, 'LT', 'L', 1, 0);
      	$packageInfo2 = '件数：'.'1'."\n".'重量（KG）：'.$data['weight'];
      	$pdf->MultiCell(60, 35, $packageInfo2, 'RT', 'L', 1, 0);

      	$pdf->Ln();
      	$pdf->MultiCell(200, 8, '', 'LTR', 'C', 1, 0);

      	$pdf->Ln();
      	$x = $pdf->GetX();
      	$y = $pdf->GetY();
      	$pdf->write1DBarcode($data['express_id'], 'C39+', '', '', '', 23, 0.4, $style, 'N');
      	$pdf->SetXY($x,$y);
      	$pdf->MultiCell(200, 25, '', 'LTR', 'C', 1, 0);

      	$pdf->Ln();
      	$pdf->SetFont('DroidSansFallback', '', 14, '', false);
      	$pdf->MultiCell(85, 30, $senderAddr, 'LTR', 'L', 1, 0);
      	$pdf->MultiCell(115, 30, $receiverAddr, 'LTR', 'L', 1, 0);

      	$pdf->Ln();
      	$x = $pdf->GetX();
      	$y = $pdf->GetY();
      	$pdf->MultiCell(150, 30, '备注：'.$data['comment'], 'LTR', 'L', 1, 0);
      	$pdf->MultiCell(50, 42, '', 1, 'C', 1, 0);
      	$pdf->write2DBarcode('www.ems.com.cn', 'QRCODE,H', $x+150, $y, 42, 42, $style, 'N');
      	$pdf->SetXY($x,$y+30);
      	$pdf->MultiCell(150, 12, '客服电话：+33650997649      网址：www.ems-china.cn', 1, 'L', 1, 0);

      	// ---------------------------------------------------------

      	// ---------------------------------------------------------
      	header_remove();
      	ob_clean();
      	$waybill = JRequest::getVar('waybill', 'waybill');
      	header('Content-type: application/pdf');
      	header('Content-Disposition: attachment; filename="'.$waybill.'.pdf"');
      	$pdf->Output($waybill.'.pdf', 'I');
      	$pdf->Output($waybill.'.pdf', 'I');
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
?>
