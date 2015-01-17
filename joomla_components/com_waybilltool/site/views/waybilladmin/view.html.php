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
* HTML View class for the Waybill Tool Component
*
* @since 0.0.1
*/
class WaybillToolViewWaybillAdmin extends JViewLegacy
{
	/**
         * @param   string  $tpl  The name of the template file to parse;
         * automatically searches through the template paths.
         * @return  void
         */
	public function display($tpl = null) {
// 		$user = JFactory::getUser();
// 		if (($user->guest) || !($user->id > 0)){
// 			JFactory::getApplication()->redirect(JURI::base()
// 			.'index.php?option=com_users&view=login', $error, 'error' );
// 		}
        $checkUserHasRightHelper = new CheckUserHasRightHelper();
        $checkUserHasRightHelper->checkUserHasRight();

		$express_uid = JFactory::getApplication()->input->get('exp-uid', null);
		$express_oid = JFactory::getApplication()->input->get('exp-oid', null);
		$displayNoExpressId = JFactory::getApplication()->input->get('exp-no-express-id', false);
		$express_oid_edt = JFactory::getApplication()->input->get('exp-oid-edit', null);

		$walletHelper = new UserWalletHelper();

		$model = $this->getModel();
        // if $displayNoExpressId == true，则显示所有没有express_id的订单
        if($displayNoExpressId == true) {
            $items = $model->getOrders($express_uid,null,true);
            $this->displayOrders($items,$express_uid);
        } else
		//1 : choose user
		if($express_uid === null){
			$items = $model->getUserInfo();
			if($items !== null) {
				$this->userlist = '<div class="container">'
				.'<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.css" /><script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>'
				.'<table style="width: 100%;" class="tg" id="dataTable">'
				.'<thead>'
				.'<tr>'
				.'<th class="tg-wvvv">用户ID</th>'
				.'<th class="tg-huh2">用户名</th>'
				.'<th class="tg-huh2">姓名</th>'
				.'<th class="tg-huh2">邮箱</th>'
				.'<th class="tg-huh2">注册日期</th>'
				.'<th class="tg-huh2">最后访问日期</th>'
				.'<th class="tg-huh2">用户订单列表</th>'
				.'</tr>'
				.'</thead>'
				.'<tbody>';

				foreach($items as $elem){
					$value = (array)($elem);
					$this->userlist = $this->userlist
					.'<tr>'
					.'<td class="tg-xaq9">'.$value['id'].'</td>'
					.'<td class="tg-s6z2">'.$value['username'].'</td>'
					.'<td class="tg-s6z2">'.$value['name'].'</td>'
					.'<td class="tg-s6z2">'.$value['email'].'</td>'
					.'<td class="tg-s6z2">'.$value['registerDate'].'</td>'
					.'<td class="tg-s6z2">'.$value['lastvisitDate'].'</td>'
					.'<td class="tg-s6z2"><a href="'.JURI::base()
					.'index.php?option=com_waybilltool&view=waybilladmin&exp-uid='.$value['id'].'">点击查看</a></td>'
					.'</tr>';//JRoute::_(JURI::current()).'
				}
				$this->userlist = $this->userlist
				.'</tbody>'
				.'</table>'
				.'<script>jQuery(document).ready(function() {jQuery("#dataTable").DataTable();} );</script>'
				.'</div>';
			} else {
				$this->userlist = '<h1>用户列表载入出错:(</h1>';
				$this->userlist = $this->userlist . '请联系管理员';
			}
		}
		//2 : choose order
		else if($express_oid === null) {
			$items = $model->getOrders($express_uid);
			if($items !== null) {
				$this->displayOrders($items,$express_uid);

			} else {
				$this->userlist = '<h1>用户订单载入出错:(</h1>';
				$this->userlist = $this->userlist . '请联系管理员';
			}

				$this->userlist = $this->userlist .
				'<button class="btn btn-lg btn-success" onclick="location.href= \''.
				JURI::base().
				'index.php?option=com_waybilltool&view=waybilladmin\'">返回用户列表</button>';

		}
		//3 :  edit order
		else if($express_oid_edt === null) {

				$mappedOID = WaybillParamsCheckerHelper::unmappingId($express_oid)['idTech'];
				$items = $model->getOrders($express_uid, $mappedOID);

				if($GLOBALS['WAYBILLTOOL_DEBUG']){
					echo '<pre>';
					var_dump($items);
					echo '</pre>';
				}
				if($items !== null) {
					$elem = (array)$items[0];

					//$comments = explode("||||||", $elem['comment']);

					// FIXME Hard coded arrays
					$productList = array("La Poste普通包裹"=>"LNO", "EMS普通包裹"=>"ENO", "EMS奶粉"=>"ENA");
					$solutionList = array("La Poste"=>"LAP", "EMS"=>"EMS");
					$payment_statList = array("已付款"=>"YFK", "未付款"=>"WFK", "其他"=>"OTH");
					$package_statList = array("已缴费"=>"DDJ", "入库"=>"RKK", "出库"=>"CKK", "运送中"=>"YSZ", "到达国内等待清关"=>"DQG", "清关成功"=>"QGC", "国内派送中"=>"GNP");

					$output =
					'<div class="registration">'
					.'<form method="POST" class="form-validate form-horizontal" id="exp-wb-create" action="'.JURI::base()
					.'index.php?option=com_waybilltool&view=waybilladmin&exp-oid='.$express_oid.'&exp-uid='.$express_uid.'">'
					.'<input type="hidden" value="1" name="exp-oid-edit" />'
					.'<input type="hidden" value="'.$elem['id_card_id'].'" name="ididc" />'
					.'<input type="hidden" value="'.$elem['package_id'].'" name="idpkg" />'
					.'<input type="hidden" value="'.$elem['send_id'].'" name="idsend" />'
					.'<input type="hidden" value="'.$elem['recv_id'].'" name="idrecv" />';

					$output = $output.'<fieldset><div class="form-box"><legend>订单包裹状态</legend><div class="row">';
					if(!($payment_statList === null)
					&& !empty($payment_statList)){
						$output = $output
						.'<div class="col-sm-6"><div class="control-label"><label> 付款状态: <span class="star">&nbsp;*</span></label></div><div class=""><select name="paystat">';
						foreach ($payment_statList as $key => $value) {
							$output = $output.'<option ';

							if($value === $elem['payment_stat'])
								$output = $output.'selected="selected" ';

							$output = $output.'value="'.$value.'">'.$key.'</option>';
						}
						$output = $output.'</select></div></div>';
					}

					if(!($package_statList === null)
					&& !empty($package_statList)){
						$output = $output
						.'<div class="col-sm-6"><div class="control-label"><label> 包裹状态: <span class="star">&nbsp;*</span></label></div><div class=""><select name="packstat">';
						foreach ($package_statList as $key => $value) {
							$output = $output.'<option ';

							if($value === $elem['package_stat'])
								$output = $output.'selected="selected" ';

							$output = $output.'value="'.$value.'">'.$key.'</option>';
						}
						$output = $output.'</select></div></div>';
					}

					$output = $output
					.'<div class="col-sm-6"><div class="control-label"><label> 价格（请核对钱包修改）: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-numeric" value="'.$elem['payment_amount'].'" type="text" name="price"></div></div>
					<div class="col-sm-6"><div class="control-label"><label> 国内物流单号: </label></div><div class="">
					<input class="validate-username" value="'.$elem['express_id'].'" type="text" name="express_id"></div></div>
					</fieldset>';

					$output = $output.'<fieldset><div class="form-box"><legend>寄件人信息</legend><div class="row"><div class="col-sm-6"><div class="control-label"><span class="spacer">
					<span class="before"></span><span class="text"><label id="jform_spacer-lbl" class=""><strongclass="red">*</strong> 必填字段</label></span>
					<span class="after"></span></span></div><div class=""></div></div><div class="col-sm-6"><div class="control-label">
					<label> 寄件人姓名: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-username" value='.$elem['send_name'].' type="text" name="name_send"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 寄件人街道: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-username" value='.$elem['send_street'].' type="text" name="addr_send_stre"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 寄件人邮编: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-numeric" value='.$elem['send_post_code'].' type="text" name="addr_send_post"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 寄件人城市: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-username" value='.$elem['send_city'].' type="text" name="addr_send_city"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 寄件人省份: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-username" value='.$elem['send_state'].' type="text" name="addr_send_stat"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 寄件人国家: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-username" value='.$elem['send_country'].' type="text" name="addr_send_cnty"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 寄件人电话: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-numeric" value='.$elem['send_telephone'].' type="text" name="phone_send"></div></div></div></div></fieldset><fieldset>
					<div class="form-box"><legend>收件人信息</legend><div class="row"><div class="col-sm-6"><div class="control-label"><span class="spacer">
					<span class="before"></span><span class="text"><label id="jform_spacer-lbl" class=""><strongclass="red">*</strong> 必填字段</label></span>
					<span class="after"></span></span></div><div class=""></div></div><div class="col-sm-6"><div class="control-label">
					<label> 收件人姓名: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-username" value='.$elem['recv_name'].' type="text" name="name_recv"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 收件人街道: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-username" value='.$elem['recv_street'].' type="text" name="addr_recv_stre"></div></div><div class="col-sm-6"><div class="control-label">
					<label> 收件人邮编: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-numeric" value='.$elem['recv_post_code'].' type="text" name="addr_recv_post"></div></div><div class="col-sm-6"><div class="control-label">
					<label> 收件人城市: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-username" value='.$elem['recv_city'].' type="text" name="addr_recv_city"></div></div><div class="col-sm-6"><div class="control-label">
					<label> 收件人省份: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-username" value='.$elem['recv_state'].' type="text" name="addr_recv_stat"></div></div><div class="col-sm-6"><div class="control-label">
					<label> 收件人国家: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-username" value='.$elem['recv_country'].' type="text" name="addr_recv_cnty"></div></div><div class="col-sm-6"><div class="control-label">
					<label> 收件人电话: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-numeric" value='.$elem['recv_telephone'].' type="text" name="phone_recv"></div></div></div></div></fieldset>'
					.'<fieldset><div class="form-box"><legend>货物信息</legend><div class="row"><div class="col-sm-6">
					<div class="control-label"><span class="spacer"><span class="before"></span><span class="text">
					<label id="jform_spacer-lbl" class=""><strongclass="red">*</strong> 必填字段</label></span><span class="after"></span></span>
					<div class=""></div></div><div class="col-sm-6"><div class="control-label">
					<label> 保价金额: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-numeric" value='.$elem['insured_amount'].' type="text" name="insu_amnt"></div>'
					.'</div>';

					if(!($solutionList === null)
					&& !empty($solutionList)){
						$output = $output
						.'<div class="col-sm-6"><div class="control-label"><label> 快递公司: <span class="star">&nbsp;*</span></label></div><div class=""><select name="exp-solution">';
						foreach ($solutionList as $key => $value) {
							$output = $output.'<option ';

							if($value === $elem['express_mode'])
							$output = $output.'selected="selected" ';

							$output = $output.'value="'.$value.'">'.$key.'</option>';
						}
						$output = $output.'</select></div></div>';
					}

					if(!($productList === null)
					&& !empty($productList)){
						$output = $output
						.'<div class="col-sm-6"><div class="control-label"><label> 快递产品类别: <span class="star">&nbsp;*</span></label></div><div class=""><select name="exp-product">';
						foreach ($productList as $key => $value) {
							$output = $output.'<option ';

							if($value === $elem['express_type'])
								$output = $output.'selected="selected" ';

							$output = $output.'value="'.$value.'">'.$key.'</option>';
						}
						$output = $output.'</select></div></div>';
					}

					$output = $output
					.'<div class="col-sm-6"><div class="control-label"><label> 包裹内容（请如实填报）: <span class="star">&nbsp;*</span></label></div><div class="">
					<textarea class="required" rows="10" cols="50" name="cargo_info" form="exp-wb-create">'.$elem['cargo_info'].'</textarea></div></div>
					<div class="col-sm-6"><div class="control-label"><label> 重量（kg）: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-numeric" value='.$elem['weight'].' type="text" name="weight"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 长（cm）: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-numeric" value='.$elem['length'].' type="text" name="length"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 宽（cm）: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-numeric" value='.$elem['width'].' type="text" name="width"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 高（cm）: <span class="star">&nbsp;*</span></label></div><div class="">
					<input class="required validate-numeric" value='.$elem['height'].' type="text" name="height"></div></div><div class="col-sm-6">
					<div class="control-label"><label> 备注 : <span class="star">&nbsp;*</span></label></div><div class="">
					<textarea rows="10" cols="50" name="comment" form="exp-wb-create">'.$elem['comment'].'</textarea></div></div>'
					.'</div></div></fieldset>';

					$output = $output.'<fieldset><div class="form-box"><legend>身份证信息</legend><div class="row"><div class="col-sm-6"><div class="control-label">
					<span class="spacer"><span class="before"></span><span class="text"><label id="jform_spacer-lbl" class=""><strongclass="red">(*)</strong> 若无修改请留空</label></span>
					<span class="after"></span></span></div><div class=""></div></div><div class="col-sm-6"><div class="control-label">
					<label> 身份证正面 : <span class="star">&nbsp;(*)</span></label></div><div class="">
					<input class="required" type="file" name="id_recto" id="id_recto"></div></div><div class="col-sm-6"><div class="control-label">
					<label> 身份证反面 : <span class="star">&nbsp;(*)</span></label></div><div class="">
					<input class="required" type="file" name="id_verso" id="id_verso"></div></div></div></div></fieldset>';


					$output = $output
					.'<br><br>'
					.'<input class="btn btn-lg btn-success" type="submit" value="确认修改订单">'
					.'</form>'
					.'</div>';

					$this->userlist = $output;

				} else {
					$this->userlist = '<h1>订单：'.$waybillid.'载入出错</h1>';
					$this->userlist = $this->userlist . '请联系管理员';
				}

				$this->userlist = $this->userlist .
				'<button class="btn btn-lg btn-success" onclick="location.href= \''.
				JURI::base().
				'index.php?option=com_waybilltool&view=waybilladmin'.
				'&exp-uid='.$express_uid.'\'">返回用户订单列表</button>';
		}
		//4 : update execution
		else {
			$toPersistData = WaybillParamsCheckerHelper::checkAdminUpdateParams($express_uid);
			if($GLOBALS['WAYBILLTOOL_DEBUG']){
				echo '<pre>';
				var_dump($toPersistData);
				echo '</pre>';
			}
			if($toPersistData !== null){
				$this->userlist = '';
				$model = $this->getModel();
				$res = $model->updateWaybill($toPersistData);
				if($res["ok"]===true){
					$this->userlist = '<h1>修改已保存:)</h1>';
					$this->userlist = $this->userlist . '<br>';
				} else {
					$this->userlist = '<h1>修改未保存:(</h1>';
					$this->userlist = $this->userlist . '请联系管理员';
				}

			} else {
				$this->userlist = '<h1>修改未保存:(</h1>';
				$this->userlist = $this->userlist . '请检查修改内容';
			}

			$this->userlist = $this->userlist .
			'<button class="btn btn-lg btn-success" onclick="location.href= \''.
			JURI::base().
			'index.php?option=com_waybilltool&view=waybilladmin'.
			'&exp-oid='.$express_oid.
			'&exp-uid='.$express_uid.'\'">返回查看此订单</button>';
		}

		if (count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}

		parent::display($tpl);
	}

	private function displayOrders($items,$express_uid) {
	    $productList = array("LNO"=>"La Poste普通包裹", "ENO"=>"EMS普通包裹", "ENA"=>"EMS奶粉");
	    $this->userlist = '<div class="container">'
	            .'<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.css" /><script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>'
	                    .'<table style="width: 100%;" class="tg" id="dataTable">'
	                            .'<thead>'
	                                    .'<tr>'
	                                            .'<th class="tg-wvvv">订单号</th>'
	                                                    .'<th class="tg-huh2">国内运单号</th>'
	                                                            .'<th class="tg-huh2">包裹类别</th>'
	                                                            .'<th class="tg-huh2">下单时间</th>'
	                                                                    .'<th class="tg-huh2">发件人</th>'
	                                                                            .'<th class="tg-huh2">收件人</th>'
	                                                                                    .'<th class="tg-huh2">付款状态</th>'
	                                                                                            .'<th class="tg-huh2">查看修改详细信息</th>'
	                                                                                                    .'</tr>'
	                                                                                                            .'</thead>'
	                                                                                                                    .'<tbody>';

	    foreach($items as $elem){
	        $value = (array)($elem);

	        $order_id = WaybillParamsCheckerHelper::mappingId($value['oid']);

	        $this->userlist = $this->userlist
	        .'<tr>'
	                .'<td class="tg-xaq9">'.$order_id.'</td>'
	                        .'<td class="tg-s6z2">'.((!isset ($value['express_id'])) ? '暂无' : $value['express_id']).'</td>'
	                                .'<td class="tg-s6z2">'.($productList[$value['express_type']]).'</td>'
	                                .'<td class="tg-s6z2">'.$value['order_time'].'</td>'
	                                        .'<td class="tg-s6z2">'.$value['send_name'].'</td>'
	                                                .'<td class="tg-s6z2">'.$value['recv_name'].'</td>'
	                                                        .'<td class="tg-s6z2">'.$value['payment_stat'].'</td>'
	                                                                .'<td class="tg-s6z2"><a href="'.JURI::base()
	                                                                .'index.php?option=com_waybilltool&view=waybilladmin&exp-oid='.$order_id.'&exp-uid='.$express_uid.'">点击查看修改</a></td>'
	                                                                        .'</tr>';//JRoute::_(JURI::current()).'
	    }
	    $this->userlist = $this->userlist
	    .'</tbody>'
	            .'</table>'
	                    .'</div>'
	                            .'<script>jQuery(document).ready(function() {jQuery("#dataTable").DataTable();} );</script>';
	}

}
