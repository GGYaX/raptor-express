<?php

require_once( dirname(__FILE__) . '/lib/tcpdf/tcpdf.php');
require_once( dirname(__FILE__) . '/lib/tcpdf/tcpdf_barcodes_1d.php');


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-16', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Raptor Express');
$pdf->SetTitle('Title');
$pdf->SetSubject('Subject');
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

$text2="一行\n两行";

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
$pdf->write1DBarcode('EMS020202020202', 'C39+', '', '', '', 33, 0.5, $style, 'N');
$pdf->SetXY($x,$y);
$pdf->SetFont('DroidSansFallback', '', 16, '', false);
$pdf->MultiCell(115, 35, '', 'LTR', 'C', 1, 0);

$pdf->Ln();
$senderAddr = '寄件：'."\n".'Nicolas Sarkozy'." ".'+33688888888'."\n".'1 Rue République, 75001 Paris';
$pdf->MultiCell(100, 30, $senderAddr, 'LTR', 'L', 1, 0);
$pdf->MultiCell(100, 30, '', 'LTR', 'C', 1, 0);

$pdf->Ln();
$receiverAddr = '收件：'."\n".'方肘子'." ".'+8613688888888'."\n".'广东省佛山市高明区森林公园老干部活动中心';
$pdf->SetFont('DroidSansFallback', '', 20, '', false);
$pdf->MultiCell(200, 30, $receiverAddr, 'LTR', 'L', 1, 0);

$pdf->Ln();
$paymentInfo = '付款方式：'."\n".'计费重量（KG）：'.'5.2'."\n".'保价金额（元）：'.'?';
$pdf->SetFont('DroidSansFallback', '', 16, '', false);
$pdf->MultiCell(85, 30, $paymentInfo, 'LT', 'L', 1, 0);
$receiveInfo = '收件人\代收人：'."\n".'签收时间：          年       月      日     时'."\n".'快件送达收货人地址,经收件人或收件人允许的代收人签字，视为送达。';
$pdf->SetFont('DroidSansFallback', '', 17, '', false);
$pdf->MultiCell(115, 30, $receiveInfo, 'LTR', 'L', 1, 0);

$pdf->Ln();
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->write1DBarcode('RE1234567890', 'C39+', $x+30, '', '', 16, 0.4, $style, 'N');
$pdf->SetXY($x,$y);
$pdf->SetFont('DroidSansFallback', '', 16, '', false);
$packageInfo1 = '订单号：'."\n\n".'配货信息：'.'物品';
$pdf->MultiCell(140, 35, $packageInfo1, 'LT', 'L', 1, 0);
$packageInfo2 = '件数：'.'1'."\n".'重量（KG）：'.'5.2';
$pdf->MultiCell(60, 35, $packageInfo2, 'RT', 'L', 1, 0);

$pdf->Ln();
$pdf->MultiCell(200, 8, '', 'LTR', 'C', 1, 0);

$pdf->Ln();
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->write1DBarcode('EMS020202020202', 'C39+', '', '', '', 23, 0.4, $style, 'N');
$pdf->SetXY($x,$y);
$pdf->MultiCell(200, 25, '', 'LTR', 'C', 1, 0);

$pdf->Ln();
$pdf->SetFont('DroidSansFallback', '', 14, '', false);
$pdf->MultiCell(85, 30, $senderAddr, 'LTR', 'L', 1, 0);
$pdf->MultiCell(115, 30, $receiverAddr, 'LTR', 'L', 1, 0);

$pdf->Ln();
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->MultiCell(150, 30, '备注：', 'LTR', 'L', 1, 0);
$pdf->MultiCell(50, 42, '', 1, 'C', 1, 0);
$pdf->write2DBarcode('www.ems.com.cn', 'QRCODE,H', $x+150, $y, 42, 42, $style, 'N');
$pdf->SetXY($x,$y+30);
$pdf->MultiCell(150, 12, '客服电话：+33122334455      网址：www.raptor-express.com', 1, 'L', 1, 0);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('ex_LS.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+ 

?>