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
class WaybillToolViewWaybillUsershow extends JViewLegacy
{
	/**
         * @param   string  $tpl  The name of the template file to parse;
         * automatically searches through the template paths.
         * @return  void
         */
	public function display($tpl = null) {
		$user = JFactory::getUser();
		if (($user->guest)
		|| !($user->id > 0)){
			JFactory::getApplication()->redirect(JURI::base()
				.'index.php?option=com_users&view=login', $error, 'error' );
		}
		$solution = SolutionChooserHelper::chosenSolution();

		$waybillid = JFactory::getApplication()->input->get('waybillid', null);
		
		$walletHelper = new UserWalletHelper();

		//1
		if($solution === null){
			$this->userlist = SolutionChooserHelper::getSolutionChooser();
		}
		//3 : details
		else if($waybillid !== null) {
			$this->userlist = '<h1>订单：'.$waybillid.'</h1>';

			$model = $this->getModel();
			$mappedOID = WaybillParamsCheckerHelper::unmappingId($waybillid)['idTech'];
			$items = $model->getUserOrders($user->id, $solution, $mappedOID);

			if($GLOBALS['WAYBILLTOOL_DEBUG']){
				echo '<pre>';
				var_dump($items);
				echo '</pre>';
			}
			if($items !== null) {
				$elem = (array)$items[0];

				$this->userlist = $this->userlist . '（+ViewUserShowDetails+挂载PDF生成模块）<br><br>';
				$this->userlist = $this->userlist
				.'<div class="container">'
				.'<table style="width: 100%;" class="tg">'
				.'<tbody>'
				.'<tr>'
				.'<th class="tg-wvvv">寄件人姓名</th>'
				.'<td class="tg-huh2">'.$elem['send_name'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">寄件人地址</th>'
				.'<td class="tg-huh2">'.$elem['send_street'].' '.$elem['send_post_code'].' '.$elem['send_city'].' '.$elem['send_state'].' '.$elem['send_country'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">寄件人电话</th>'
				.'<td class="tg-huh2">'.$elem['send_telephone'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">收件人姓名</th>'
				.'<td class="tg-huh2">'.$elem['recv_name'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">收件人地址</th>'
				.'<td class="tg-huh2">RUE XXX</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">收件人电话</th>'
				.'<td class="tg-huh2">'.$elem['recv_telephone'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">保价金额</th>'
				.'<td class="tg-huh2">'.$elem['insured_amount'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">快递产品类别</th>'
				.'<td class="tg-huh2">'.$elem['express_type'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">包裹内容及备注</th>'
				.'<td class="tg-huh2">'.$elem['comment'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">长x宽x高（cm）</th>'
				.'<td class="tg-huh2">'.$elem['length'].' * '.$elem['width'].' * '.$elem['height'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">重量（kg）</th>'
				.'<td class="tg-huh2">'.$elem['height'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">价格</th>'
				.'<td class="tg-huh2">'.$elem['payment_amount'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">身份证正面</th>'
				.'<td class="tg-huh2">'.$elem['filename_recto'].'</th>'
				.'</tr>'
				.'<tr>'
				.'<th class="tg-wvvv">身份证反面</th>'
				.'<td class="tg-huh2">'.$elem['filename_verso'].'</th>'
				.'</tr>'
				.'</tbody>'
				.'</table>'
				.'</div>';
			} else {
				$this->userlist = '<h1>订单：'.$waybillid.'载入出错</h1>';
				$this->userlist = $this->userlist . '请联系管理员';
			}
		}
		//2 : list
		else {
			$solutionName;
			switch ($solution) {
				case "LAP":
					$solutionName = "La Poste ";
					break;
				case "EMS":
					$solutionName = "EMS ";
					break;
				default:
					JError::raiseError(500, $solution."未知的快递公司", $solution);
					break;
			}
			$this->userlist = '<h1>'.$solutionName.'订单</h1>';

			$model = $this->getModel();
			$items = $model->getUserOrders($user->id, $solution);

			$this->userlist = $this->userlist
			.'<div class="container">'
			.'<table style="width: 100%;" class="tg">'
			.'<tbody>'
			.'<tr>'
			.'<th class="tg-wvvv">订单号</th>'
			.'<th class="tg-huh2">下单时间</th>'
			.'<th class="tg-huh2">发件人</th>'
			.'<th class="tg-huh2">收件人</th>'
			.'<th class="tg-huh2">付款状态</th>'
			.'<th class="tg-huh2">查看详细信息</th>'
			.'</tr>';

			foreach($items as $elem){
				$value = (array)($elem);

				$order_id = WaybillParamsCheckerHelper::mappingId($value['order_id']);

				$this->userlist = $this->userlist
				.'<tr>'
				.'<td class="tg-xaq9">'.$order_id.'</td>'
				.'<td class="tg-s6z2">'.$value['order_time'].'</td>'
				.'<td class="tg-s6z2">'.$value['send_name'].'</td>'
				.'<td class="tg-s6z2">'.$value['recv_name'].'</td>'
				.'<td class="tg-s6z2">'.$value['payment_stat'].'</td>'
				.'<td class="tg-s6z2"><a href="'.JRoute::_(JURI::current()).'?exp-solution='.$solution.'&waybillid='.$order_id.'">详细信息</a></td>'
				.'</tr>';
			}
			$this->userlist = $this->userlist
			.'</tbody>'
			.'</table>'
			.'</div>';
		}

		if (count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}

		parent::display($tpl);
	}

}
