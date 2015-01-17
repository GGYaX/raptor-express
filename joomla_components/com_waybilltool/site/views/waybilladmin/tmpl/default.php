<?php
/**
* @package Joomla.Administrator
* @subpackage com_waybilltool
*
* @copyright Copyright (C) 2014
* @license GNU??? BSD???
*/
defined('_JEXEC') or die;
?>

<script>
var LNO ={'1KG':21,'2KG':28,'3KG':31,'4KG':32,'5KG':35,'6KG':38,'7KG':41,'8KG':51,'9KG':60,'10KG':68,'11KG':96,'12KG':96,'13KG':96,'14KG':96,'15KG':96};var ENA ={'2|900|2KG':19,'2|800|2KG':19,'2|12004KG':26,'3|800|3KG':21,'3|900|4KG':30,'3|1200|6KG':40,'4|800|4KG':31,'4|900|5KG':32,'4|1200|7KG':43,'5|800|5.5KG':38,'5|900|6KG':42,'6|800|7KG':43,'6|900|7KG':45};var ENO ={'1KG':11,'2KG':15,'3KG':19,'4KG':23,'5KG':27,'6KG':31,'7KG':35,'8KG':39,'9KG':43,'10KG':47,'11KG':51,'12KG':55,'13KG':59,'14KG':63,'15KG':67,'16KG':71,'17KG':75,'18KG':79,'19KG':83,'20KG':87};
jQuery(document).ready(function() {
  var expProduct = jQuery('[name=exp-product]');
  var blocs = jQuery('.repackage');
  var length = jQuery('[name=length]');
  var width = jQuery('[name=width]');
  var hight = jQuery('[name=height]');
  var weight = jQuery('[name=weight]');
  var enaDemand = jQuery('[name=enaDemand]');
  var insuAmount = jQuery('[name=insu_amnt');
  c(expProduct);
  expProduct.change(function() {
    c(expProduct);
  });
  for (var i = blocs.length - 1; i >= 0; i--) {
    jQuery(blocs[i]).change(function() {
      if(expProduct.val() === 'ENO' || expProduct.val() === 'LNO') {
        var lengthFloat = parseFloat(length.val());
        var widthFloat = parseFloat(width.val());
        var hightFloat = parseFloat(hight.val());
        var weightFloat = parseFloat(weight.val());
        if((isFinite(lengthFloat)&&isFinite(widthFloat)&&isFinite(hightFloat)&&isFinite(weightFloat))) {
          calculAndDisplay();
        }
      } else {
        if(!length.val()) length.val('1');
        if(!width.val()) width.val('1');
        if(!hight.val()) hight.val('1');
        if(!weight.val()) weight.val('1');
        calculAndDisplay();
      }
    });
  };
  function calculAndDisplay() {
    var r = calcul();
    if(r && isFinite(r)) {
      cleanResult(r+'€')
      jQuery('#shippingResultBloc').show();
    } else {
      cleanResult('暂时无法计算，请直接点击确认订单');
      jQuery('#shippingResultBloc').show();
    }
  }
  function c (expProduct) {
    cleanResult('');
    jQuery('#shippingResultBloc').hide();
    if(expProduct.val() === 'ENO' || expProduct.val() === 'LNO') {
      jQuery('.ena').hide();
      jQuery('.ems').show();
    } else if(expProduct.val() === 'ENA') {
      calculAndDisplay();
      jQuery('.ems').hide();
      jQuery('.ena').show();
      if(!length.val()) length.val('1');
      if(!width.val()) width.val('1');
      if(!hight.val()) hight.val('1');
      if(!weight.val()) weight.val('1');
    }
  }

  function hideResult() {
    jQuery('#shippingResultBloc').hide();
  }
  function cleanResult(v) {
    jQuery('#shippingResult').text(v);
  }

  function calcul() {
    var lengthFloat = parseFloat(length.val());
    var widthFloat = parseFloat(width.val());
    var hightFloat = parseFloat(hight.val());
    var weightFloat = parseFloat(weight.val());
    if(expProduct.val() === 'ENA') {
      return parseFloat(ENA['' + enaDemand.val()] + parseFloat(insuAmount.val()));
    } else if(isFinite(lengthFloat)&&isFinite(widthFloat)&&isFinite(hightFloat)&&isFinite(weightFloat)) {
      if(expProduct.val() === 'ENO') {
        return parseFloat(ENO['' + calculWeight((lengthFloat),(widthFloat),(hightFloat),(weightFloat)) + 'KG'] + parseFloat(insuAmount.val()));
      } else if(expProduct.val() === 'LNO') {
        return parseFloat(LNO['' + calculWeight((lengthFloat),(widthFloat),(hightFloat),(weightFloat)) + 'KG'] + parseFloat(insuAmount.val()));
      }
    }
    return '';
  }

  function calculWeight(length,width,hight,weight) {
    return Math.max(Math.ceil(length*width*hight/5000),Math.ceil(weight));
  }
});
</script>
<?php echo $this->userlist; ?>
