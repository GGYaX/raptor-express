<?php
/**
* @package Joomla.Administrator
* @subpackage com_pricecalc
*
* @copyright Copyright (C) 2014 
* @license GNU??? BSD???
*/
  defined('_JEXEC') or die;
  $document = JFactory::getDocument();
  $document->addStyleSheet('media/mod_pricecalc/pricecalc.css');
  //$document->addScript('media/mod_pricecalc/angular.min.js');
  $document->addScriptDeclaration('
    function isNumber(value){
      return !isNaN(value) && 
         parseInt(Number(value)) == value && 
         !isNaN(parseInt(value, 10)) && 
         value>=0;
    }
    function calculate(){
      var checked = true;
      if (!isNumber(document.reinputform.length.value)) {
	document.reinputform.length.value="请输入一个整数";
	checked = false;
      }
      if (!isNumber(document.reinputform.width.value)) {
	document.reinputform.width.value="请输入一个整数";
	checked = false;
      }
      if (!isNumber(document.reinputform.height.value)) {
	document.reinputform.height.value="请输入一个整数";
	checked = false;
      }
      if (!isNumber(document.reinputform.weight.value)) {
	document.reinputform.weight.value="请输入一个整数";
	checked = false;
      }
      if(!checked) return;
      
      var volume = document.reinputform.length.value * 
	document.reinputform.width.value * 
	document.reinputform.height.value / 5000;
	
      console.log(volume);
	
      var effectif = (volume > document.reinputform.weight.value) ?
	volume : document.reinputform.weight.value;

      var toCal = Math.ceil(effectif);
      if(toCal<'.$params->get('min_weight', '1').'){
	toCal = '.$params->get('min_weight', '1').';
      }
      if(toCal>'.$params->get('max_weight', '1').'){
	window.alert("特殊订单，请联系客服，谢谢");
	return;
      }
      console.log(toCal);
      
      var emsToFind = "ems"+toCal;
      var lapToFind = "lap"+toCal;
      var reToFind = "re"+toCal;
      var bpToFind = "bp"+toCal;
      console.log(emsToFind+lapToFind+reToFind+bpToFind);
      
      var jsonStr = document.getElementById(\'priceinfo\').innerHTML;
      console.log(jsonStr);
      var jsonObj = JSON.parse(jsonStr);
      
      var emsFound = jsonObj[emsToFind];
      var lapFound = jsonObj[lapToFind];
      var reFound = jsonObj[reToFind];
      var bpFound = jsonObj[bpToFind];
      console.log(emsFound+" "+lapFound+" "+reFound+" "+bpFound);
      
      
      document.getElementById(\'ems_price\').innerHTML = emsFound;
      document.getElementById(\'lap_price\').innerHTML = lapFound;
      document.getElementById(\'re_price\').innerHTML = reFound;
      document.getElementById(\'bp_price\').innerHTML = bpFound;
      
      var preHideClassName=" prehide"; //keep a space before
      var list = document.getElementsByClassName(preHideClassName);
      for(var j=0;j<16;j++){
	for (var i = 0; i < list.length; i++) {
	    list[i].className=list[i].className.replace(preHideClassName,"");
	}
      }
      
    }
  ');
?>
<div class="container pricing-table style-2">
  <!-- BO Input fields -->
  <div style="text-align:center">
    <h1>请输入货物尺寸</h1>
    <form name="reinputform" class="form-inline">
      <input id="raptor-pricecalc-length" type="text" name="length" class="input-small" tabindex="0" size="18" placeholder="长(cm)">
      <input id="raptor-pricecalc-width" type="text" name="width" class="input-small" tabindex="0" size="18" placeholder="宽(cm)">
      <input id="raptor-pricecalc-height" type="text" name="height" class="input-small" tabindex="0" size="18" placeholder="高(cm)">
      <br><br>
      <input id="raptor-pricecalc-weight" type="text" name="weight" class="input-small" tabindex="0" size="18" placeholder="重量(kg)">
    </form>
    <br>
    <button tabindex="0" name="Calculate" class="btn btn-primary" onclick="calculate()">计算</button>
  </div>
  <!-- EO Input fields -->
  
  <hr>
  
  <!-- BO Display results -->
  <div class="row">
  
    <div class="col col-sm-3  no-padding">
      <div class="col-header text-center">
	<h2>EMS</h2>
	<p></p>
      </div>
      <div class="col-body">
	<ul>
	  <li class="row0">就是有优点</li>
	  <li class="row1">缺点</li>
	  <li class="row0 prehide">
	    <span class="big-number"><sup>€</sup></span>
	    <span class="big-number" id="ems_price"></span>
	    <span class="big-number"><sup>.起</sup></span>
	  </li>
	</ul>
      </div>
      <div class="col-footer text-center prehide">
	<a class="btn btn-lg btn-default " title="下单" href="/raptor-express/joomla/index.php/registre">下单</a>
      </div>
    </div>
    
    <div class="col col-sm-3  no-padding">
      <div class="col-header text-center">
	<h2>La poste</h2>
	<p></p>
      </div>
      <div class="col-body">
	<ul>
	  <li class="row0">Laposte优点</li>
	  <li class="row1">缺点</li>
	  <li class="row0 prehide">
	    <span class="big-number"><sup>€</sup></span>
	    <span class="big-number" id="lap_price"></span>
	    <span class="big-number"><sup>.起</sup></span>
	  </li>
	</ul>
      </div>
      <div class="col-footer text-center prehide">
	<a class="btn btn-lg btn-default " title="下单" href="/raptor-express/joomla/index.php/registre">下单</a>
      </div>
    </div>
    
    <div class="col col-sm-3  col-featured shadow  no-padding">
      <div class="col-header text-center">
	<h2>报税</h2>
	<p></p>
      </div>
      <div class="col-body">
	<ul>
	  <li class="row0">优点</li>
	  <li class="row1">缺点</li>
	  <li class="row0 prehide">
	    <span class="big-number"><sup>€</sup></span>
	    <span class="big-number" id="re_price"></span>
	    <span class="big-number"><sup>.起</sup></span>
	  </li>
	</ul>
      </div>
      <div class="col-footer text-center prehide">
	<a class="btn btn-lg btn-success " title="下单" href="/raptor-express/joomla/">下单</a>
      </div>
    </div>
    
    <div class="col col-sm-3  no-padding">
      <div class="col-header text-center">
	<h2>Bpost</h2>
	<p></p>
      </div>
      <div class="col-body">
	<ul>
	  <li class="row0">优点</li>
	  <li class="row1">缺点</li>
	  <li class="row0 prehide">
	    <span class="big-number"><sup>€</sup></span>
	    <span class="big-number" id="bp_price"></span>
	    <span class="big-number"><sup>.起</sup></span>
	  </li>
	</ul>
      </div>
      <div class="col-footer text-center prehide">
	<a class="btn btn-lg btn-default " title="下单" href="/raptor-express/joomla/">下单</a>
      </div>
    </div>
    
  </div>
  <!-- EO Display results -->
  
  <div id="priceinfo" style="display:none">
    <?php echo $priceCalcInfo; ?>
  </div>
</div>