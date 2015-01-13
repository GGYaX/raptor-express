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

    //1
    if($solution === null){
      $this->form = '<h1>选择快递公司</h1>';
      $this->form = $this->form . SolutionChooserHelper::getSolutionChooser();
    }
    //3
    else if($creation !== null) {
			$toPersistData = WaybillParamsCheckerHelper::checkCreateParams($user->id);
			if($toPersistData !== null){
				$model = $this->getModel();

				if($model->insertNewWaybill($toPersistData)===true){
					$this->form = '<h1>订单已保存:)</h1>';
					$this->form = $this->form . '<br>';
					$this->form = $this->form .
						'<button class="btn btn-lg btn-success" onclick="location.href= \''.
							JURI::base().
							'index.php?option=com_waybilltool&view=waybillusershow'.
							'?exp-solution='.$solution.'&waybillid='.'FR000000UUUT'.
							'\'">查看此订单</button>';
				} else {
					$this->form = '<h1>订单未保存:(</h1>';
					$this->form = $this->form . '请联系管理员';
				}
			} else {
				$this->form = '<h1>订单未保存:(</h1>';
				$this->form = $this->form . '请联系管理员';
			}
    }
    //2
    else {
			$this->form = '<h1>填写订单信息</h1>' . "（显示对应钱包余额）<br>";;
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

  private static function getHtmlCreateForm($solution = null, $productList = null, $needIdCard = false) {

    $output ='（显示地址本列表？）<br>'.
      '<form method="POST" class="form-validate" id="exp-wb-create" action="'.JRoute::_(JURI::current()).'">'
      .'<input type="hidden" value="1" name="exp-creation" />'
      .'<input type="hidden" value="'.$solution.'" name="exp-solution" />'
      .'<div style="display: inline-block; padding: 0 20px 0 0;">'
      .'寄件人姓名 : <input class="required validate-username" type="text" name="name_send">'
      .'<br>'
      .'寄件人街道 : <input class="required validate-username" type="text" name="addr_send_stre">'
      .'<br>'
			.'寄件人邮编 : <input class="required validate-numeric" type="text" name="addr_send_post">'
			.'<br>'
			.'寄件人城市 : <input class="required validate-username" type="text" name="addr_send_city">'
			.'<br>'
			.'寄件人省份 : <input class="required validate-username" type="text" name="addr_send_stat">'
			.'<br>'
			.'寄件人国家 : <input class="required validate-username" type="text" name="addr_send_cnty">'
			.'<br>'
      .'寄件人电话 : <input class="required validate-numeric" type="text" name="phone_send">'
      .'</div>'
      .'<div style="display: inline-block">'
      .'收件人姓名 : <input class="required validate-username" type="text" name="name_recv">'
      .'<br>'
			.'收件人街道 : <input class="required validate-username" type="text" name="addr_recv_stre">'
			.'<br>'
			.'收件人邮编 : <input class="required validate-numeric" type="text" name="addr_recv_post">'
			.'<br>'
			.'收件人城市 : <input class="required validate-username" type="text" name="addr_recv_city">'
			.'<br>'
			.'收件人省份 : <input class="required validate-username" type="text" name="addr_recv_stat">'
			.'<br>'
			.'收件人国家 : <input class="required validate-username" type="text" name="addr_recv_cnty">'
			.'<br>'
      .'收件人电话 : <input class="required validate-numeric" type="text" name="phone_recv">'
      .'</div>'
      .'<br><br>'
      .'<div style="display: inline-block; padding: 0 20px 0 0;">'
      .'保价金额 : <input class="required validate-numeric" type="text" name="insu_amnt">'
      .'</div>';

    if(!($productList === null)
        && !empty($productList)){
      $output = $output
        .'<div style="display: inline-block">'
        .'快递产品类别 : '
        .'<select name="exp-product">';
      foreach ($productList as $key => $value) {
        $output = $output
          .'<option value="'.$value.'">'.$key.'</option>';
      }
      $output = $output.'</select></div>';
    }

    $output = $output . '<br><br>'
      .'包裹内容（请如实填报） : '
      .'<textarea class="required" rows="10" cols="50" name="comment" form="exp-wb-create"></textarea>'
      .'<br>'
      .'重量（kg） : <input class="required validate-numeric" type="text" name="weight">'
      .'<br>'
      .'<div style="display: inline-block; padding: 0 20px 0 0;">'
      .'长（cm） : <input class="required validate-numeric" type="text" name="length">'
      .'</div>'
      .'<div style="display: inline-block; padding: 0 20px 0 0;">'
      .'宽（cm） : <input class="required validate-numeric" type="text" name="width">'
      .'</div>'
      .'<div style="display: inline-block;">'
      .'高（cm） : <input class="required validate-numeric" type="text" name="height">'
      .'</div>'
      .'<br><br>'
      .'备注 : '
      .'<textarea rows="10" cols="50" name="comment2" form="exp-wb-create"></textarea>'
      .'<br>';

    if($needIdCard) {
      $output = $output.'<br>'
        .'<div style="display: inline-block; padding: 0 10px 0 0;">'
        .'身份证正面'
        .'<input class="required" type="file" name="id_recto" id="id_recto">'
        .'</div>'
        .'<div style="display: inline-block;">'
        .'身份证反面'
        .'<input class="required" type="file" name="id_verso" id="id_verso">'
        .'</div>';
    }

    $output = $output
    .'<br><br>'
    .'<input class="btn btn-lg btn-success" type="submit" value="确认订单">'
    .'</form>'
    .'（自动生成价格，在订单管理中显示可见）';

    return $output;
  }

}