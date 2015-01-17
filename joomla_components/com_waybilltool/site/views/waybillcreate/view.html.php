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
class WaybillToolViewWaybillCreate extends JViewLegacy
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

		$creation = JFactory::getApplication()->input->get('exp-creation', null);

		$walletHelper = new UserWalletHelper();

    //1
    if($solution === null){
			$this->form = $walletHelper->getUserWalletHtml();
      $this->form = $this->form . SolutionChooserHelper::getSolutionChooser('点击下单');
    }
    //3
    else if($creation !== null) {
			$toPersistData = WaybillParamsCheckerHelper::checkCreateParams($user->id);
			if($GLOBALS['WAYBILLTOOL_DEBUG']){
				echo '<pre>';
				var_dump($toPersistData);
				echo '</pre>';
			}
			if($toPersistData !== null){
				$model = $this->getModel();
				$res = $model->insertNewWaybill($toPersistData);
				if($res["ok"]===true){
					$this->form = '<h1>订单已保存:)</h1>';
					$this->form = $this->form . '<br>';
					$this->form = $this->form .
						'<button class="btn btn-lg btn-success" onclick="location.href= \''.
							JURI::base().
							'index.php?option=com_waybilltool&view=waybillusershow'.
							'&exp-solution='.$solution.'&waybillid='.
							WaybillParamsCheckerHelper::mappingId($res["oid"]).
							'\'">查看此订单</button>';
				} else {
					$this->form = '<h1>订单未保存:(</h1>';
					$this->form = $this->form . '请联系管理员<br/>';
					$this->form = $this->form . $res["msg"];
				}
			} else {
				$this->form = '<h1>订单未保存:(</h1>';
				$this->form = $this->form . '请检查输入内容<br/>';
				$this->form = $this->form . $res["msg"];
			}
    }
    //2
    else {
			$this->form = $walletHelper->getUserWalletHtml();
      //FIXME hard coded solution types
      switch ($solution) {
        case "LAP":
          //FIXME hard coded product types
          $products = array("La Poste普通包裹"=>"LNO");
          $this->form = $this->form . self::getHtmlCreateForm($solution, $products, false);
          break;
        case "EMS":
          //FIXME hard coded product types
          $products = array("EMS普通包裹"=>"ENO", "EMS奶粉"=>"ENA");
          $this->form = $this->form . self::getHtmlCreateForm($solution, $products, true);
          break;
        default:
          JError::raiseError(500, "未知的快递公司", $solution);
          break;
      }

    }
		//$this->form = $this->get('CreateForm');

		if (count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}
		parent::display($tpl);
	}



  // private static function getHtmlCreateFormOld($solution = null, $productList = null, $needIdCard = false) {
	//
  //   $output ='（显示地址本列表？）<br>'.
  //     '<form method="POST" class="form-validate" id="exp-wb-create" action="'.JRoute::_(JURI::current()).'" enctype="multipart/form-data">'
  //     .'<input type="hidden" value="1" name="exp-creation" />'
  //     .'<input type="hidden" value="'.$solution.'" name="exp-solution" />'
  //     .'<div style="display: inline-block; padding: 0 20px 0 0;">'
  //     .'寄件人姓名 : <input class="required validate-username" type="text" name="name_send">'
  //     .'<br>'
  //     .'寄件人街道 : <input class="required validate-username" type="text" name="addr_send_stre">'
  //     .'<br>'
	// 		.'寄件人邮编 : <input class="required validate-numeric" type="text" name="addr_send_post">'
	// 		.'<br>'
	// 		.'寄件人城市 : <input class="required validate-username" type="text" name="addr_send_city">'
	// 		.'<br>'
	// 		.'寄件人省份 : <input class="required validate-username" type="text" name="addr_send_stat">'
	// 		.'<br>'
	// 		.'寄件人国家 : <input class="required validate-username" type="text" name="addr_send_cnty">'
	// 		.'<br>'
  //     .'寄件人电话 : <input class="required validate-numeric" type="text" name="phone_send">'
  //     .'</div>'
  //     .'<div style="display: inline-block">'
  //     .'收件人姓名 : <input class="required validate-username" type="text" name="name_recv">'
  //     .'<br>'
	// 		.'收件人街道 : <input class="required validate-username" type="text" name="addr_recv_stre">'
	// 		.'<br>'
	// 		.'收件人邮编 : <input class="required validate-numeric" type="text" name="addr_recv_post">'
	// 		.'<br>'
	// 		.'收件人城市 : <input class="required validate-username" type="text" name="addr_recv_city">'
	// 		.'<br>'
	// 		.'收件人省份 : <input class="required validate-username" type="text" name="addr_recv_stat">'
	// 		.'<br>'
	// 		.'收件人国家 : <input class="required validate-username" type="text" name="addr_recv_cnty">'
	// 		.'<br>'
  //     .'收件人电话 : <input class="required validate-numeric" type="text" name="phone_recv">'
  //     .'</div>'
  //     .'<br><br>'
  //     .'<div style="display: inline-block; padding: 0 20px 0 0;">'
  //     .'保价金额 : <input class="required validate-numeric" type="text" name="insu_amnt">'
  //     .'</div>';
	//
  //   if(!($productList === null)
  //       && !empty($productList)){
  //     $output = $output
  //       .'<div style="display: inline-block">'
  //       .'快递产品类别 : '
  //       .'<select name="exp-product">';
  //     foreach ($productList as $key => $value) {
  //       $output = $output
  //         .'<option value="'.$value.'">'.$key.'</option>';
  //     }
  //     $output = $output.'</select></div>';
  //   }
	//
  //   $output = $output . '<br><br>'
  //     .'包裹内容（请如实填报） : '
  //     .'<textarea class="required" rows="10" cols="50" name="cargo_info" form="exp-wb-create"></textarea>'
  //     .'<br>'
  //     .'重量（kg） : <input class="required validate-numeric" type="text" name="weight">'
  //     .'<br>'
  //     .'<div style="display: inline-block; padding: 0 20px 0 0;">'
  //     .'长（cm） : <input class="required validate-numeric" type="text" name="length">'
  //     .'</div>'
  //     .'<div style="display: inline-block; padding: 0 20px 0 0;">'
  //     .'宽（cm） : <input class="required validate-numeric" type="text" name="width">'
  //     .'</div>'
  //     .'<div style="display: inline-block;">'
  //     .'高（cm） : <input class="required validate-numeric" type="text" name="height">'
  //     .'</div>'
  //     .'<br><br>'
  //     .'备注 : '
  //     .'<textarea rows="10" cols="50" name="comment" form="exp-wb-create"></textarea>'
  //     .'<br>';
	//
  //   if($needIdCard) {
  //     $output = $output.'<br>'
  //       .'<div style="display: inline-block; padding: 0 10px 0 0;">'
  //       .'身份证正面'
  //       .'<input class="required" type="file" name="id_recto" id="id_recto">'
  //       .'</div>'
  //       .'<div style="display: inline-block;">'
  //       .'身份证反面'
  //       .'<input class="required" type="file" name="id_verso" id="id_verso">'
  //       .'</div>';
  //   }
	//
  //   $output = $output
  //   .'<br><br>'
  //   .'<input class="btn btn-lg btn-success" type="submit" value="确认订单">'
  //   .'</form>'
  //   .'（自动生成价格，在订单管理中显示可见）';
	//
  //   return $output;
  // }

  private static function getHtmlCreateForm($solution = null, $productList = null, $needIdCard = false) {

      $output =
      '<div class="registration">'
              .'<form method="POST" class="form-validate form-horizontal" id="exp-wb-create" action="'.JRoute::_(JURI::current()).'" enctype="multipart/form-data">'
                      .'<input type="hidden" value="1" name="exp-creation" />'
                              .'<input type="hidden" value="'.$solution.'" name="exp-solution" />'
                                      .'<fieldset><div class="form-box"><legend>寄件人信息</legend><div class="row"><div class="col-sm-6"><div class="control-label"><span class="spacer"><span class="before"></span><span class="text"><label id="jform_spacer-lbl" class=""><strongclass="red">*</strong> 必填字段</label></span><span class="after"></span></span></div><div class=""></div></div><div class="col-sm-6"><div class="control-label"><label> 寄件人姓名: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-username" type="text"name="name_send"></div></div><div class="col-sm-6"><div class="control-label"><label> 寄件人街道: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-username" type="text"name="addr_send_stre"></div></div><div class="col-sm-6"><div class="control-label"><label> 寄件人邮编: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-numeric" type="text" name="addr_send_post"></div></div><div class="col-sm-6"><div class="control-label"><label> 寄件人城市: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-username"type="text" name="addr_send_city"></div></div><div class="col-sm-6"><div class="control-label"><label> 寄件人省份: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-username" type="text"name="addr_send_stat"></div></div><div class="col-sm-6"><div class="control-label"><label> 寄件人国家: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-username" type="text"name="addr_send_cnty"></div></div><div class="col-sm-6"><div class="control-label"><label> 寄件人电话: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-numeric" type="text" name="phone_send"></div></div></div></div></fieldset><fieldset><div class="form-box"><legend>收件人信息</legend><div class="row"><div class="col-sm-6"><div class="control-label"><span class="spacer"><span class="before"></span><span class="text"><label id="jform_spacer-lbl" class=""><strongclass="red">*</strong> 必填字段</label></span><span class="after"></span></span></div><div class=""></div></div><div class="col-sm-6"><div class="control-label"><label> 收件人姓名: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-username" type="text"name="name_recv"></div></div><div class="col-sm-6"><div class="control-label"><label> 收件人街道: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-username" type="text"name="addr_recv_stre"></div></div><div class="col-sm-6"><div class="control-label"><label> 收件人邮编: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-numeric" type="text" name="addr_recv_post"></div></div><div class="col-sm-6"><div class="control-label"><label> 收件人城市: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-username"type="text" name="addr_recv_city"></div></div><div class="col-sm-6"><div class="control-label"><label> 收件人省份: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-username" type="text"name="addr_recv_stat"></div></div><div class="col-sm-6"><div class="control-label"><label> 收件人国家: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-username" type="text"name="addr_recv_cnty"></div></div><div class="col-sm-6"><div class="control-label"><label> 收件人电话: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-numeric" type="text" name="phone_recv"></div></div></div></div></fieldset>'
                                              .'<fieldset><div class="form-box"><legend>货物信息</legend><div class="row"><div class="col-sm-6"><div class="control-label"><span class="spacer"><span class="before"></span><spanclass="text"><label id="jform_spacer-lbl" class=""><strongclass="red">*</strong>必填字段</label></span><span class="after"></span></span><div class=""></div></div><div class="col-sm-6"><div class="control-label"><label> 保价金额: <span class="star">&nbsp;*</span></label></div><div class=""><select name="insu_amnt" class="required repackage"><option value="0" selected>0€</option><option value="1">1€</option><option value="2">2€</option><option value="3">3€</option><option value="4">4€</option><option value="5">5€</option><option value="6">6€</option><option value="7">7€</option><option value="8">8€</option><option value="9">9€</option><option value="10">10€</option><option value="11">11€</option><option value="12">12€</option><option value="13">13€</option><option value="14">14€</option><option value="15">15€</option></select></div>'
                                                      .'</div>';

      if(!($productList === null)
              && !empty($productList)){
          $output = $output
          .'<div class="col-sm-6"><div class="control-label"><label> 快递产品类别: <span class="star">&nbsp;*</span></label></div><div class=""><select name="exp-product">';
          foreach ($productList as $key => $value) {
              $output = $output
              .'<option value="'.$value.'">'.$key.'</option>';
          }
          $output = $output.'</select></div></div>';
      }

      $output = $output
      .'<div class="col-sm-6 repackage ems"><div class="control-label"><label> 重量（kg）: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-numeric" type="text" name="weight"></div></div><div class="col-sm-6 repackage ems"><div class="control-label"><label> 长（cm）: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-numeric" type="text" name="length"></div></div><div class="col-sm-6 repackage ems"><div class="control-label"><label> 宽（cm）: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-numeric" type="text" name="width"></div></div><div class="col-sm-6 repackage ems"><div class="control-label"><label> 高（cm）: <span class="star">&nbsp;*</span></label></div><div class=""><input class="required validate-numeric" type="text" name="height"></div></div><div class="col-sm-12 ena"><div class="control-label"><label> 请选择: <span class="star">&nbsp;*</span></label></div><div class=""><select name="enaDemand" class="repackage" aria-invalid="false"><optionvalue="2|900|2KG">2罐900克（包税）|2KG</option><option value="2|800|2KG">2罐800克（包税）|2KG</option><option value="2|12004KG">2罐1200克（包税）|4KG</option><option value="3|800|3KG">3罐800克（包税）|3KG</option><option value="3|900|4KG">3罐900克（包税）|4KG</option><option value="3|1200|6KG">3罐1200克（包税）|6KG</option><option value="4|800|4KG">4罐800克（包税）|4KG</option><option value="4|900|5KG">4罐900克（包税）|5KG</option><option value="4|1200|7KG">4罐1200克（包税）|7KG</option><option value="5|800|5.5KG">5罐800克（包税）|5,5KG</option><option value="5|900|6KG">5罐900克（包税）|6KG</option><option value="6|800|7KG">6罐800克（包税）|7KG</option><option value="6|900|7KG">6罐900克（包税）|7KG</option></select></div></div><div class="col-sm-6"><div class="control-label"><label> 包裹内容（请如实填报）: <span class="star">&nbsp;*</span></label></div><div class=""><textarea class="required" rows="10" cols="50" name="cargo_info" form="exp-wb-create"></textarea></div></div><div class="col-sm-6"><div class="control-label"><label> 备注 : <span class="star">&nbsp;*</span></label></div><div class=""><textarea rows="10" cols="50" name="comment" form="exp-wb-create"></textarea></div></div>'
              .'</div></div></fieldset>';

      if($needIdCard) {
          $output = $output.'<fieldset><div class="form-box"><legend>身份证信息</legend><div class="row"><div class="col-sm-6"><div class="control-label"><span class="spacer"><span class="before"></span><spanclass="text"><label id="jform_spacer-lbl" class=""><strongclass="red">*</strong>必填（格式为jpg, png, gif, jpeg；文件小于 1MB）</label></span><span class="after"></span></span></div><div class=""></div></div><div class="col-sm-6"><div class="control-label"><label> 身份证正面 : <span class="star">&nbsp;*</span></label></div><div class=""><input type="hidden" name="MAX_FILE_SIZE" value="1048576" /><input class="required" type="file" name="id_recto" id="id_recto"></div></div><div class="col-sm-6"><div class="control-label"><label> 身份证反面 : <span class="star">&nbsp;*</span></label></div><div class=""><input type="hidden" name="MAX_FILE_SIZE" value="1048576" /><input class="required" type="file" name="id_verso" id="id_verso"></div></div></div></div></fieldset>';
      }

      $output = $output
      .'<fieldset><div class=""><div class="row"><div class="col-sm-6"><div class=""></div></div><div class="col-sm-6"><div class="control-label"><label></label></div><div class=""><input class="btn btn-lg btn-success" type="submit" value="确认订单"></div></div><div id="shippingResultBloc" class="col-sm-6" style="display:none;"><div class="control-label"><label>运费</label></div><div class=""><h3 id="shippingResult" style="margin-top:0px;"></h3></div></div></div></div></fieldset>'
                      .'</form>'
                              .'</div>';

      return $output;
  }
}
