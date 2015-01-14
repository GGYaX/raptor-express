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

// La poste计算
var LNO =
['1KG'=>21,
'2KG'=>28,
'3KG'=>31,
'4KG'=>32,
'5KG'=>35,
'6KG'=>38,
'7KG'=>41,
'8KG'=>51,
'9KG'=>60,
'10KG'=>68,
'11KG'=>96,
'12KG'=>96,
'13KG'=>96,
'14KG'=>96,
'15KG'=>96];

// 奶粉 array1
var ENA1 =
['2|900|2KG'=>19,
'2|800|2KG'=>19,
'2|12004KG'=>26,
'3|800|3KG'=>21,
'3|900|4KG'=>30,
'3|1200|6KG'=>40,
'4|800|4KG'=>31,
'4|900|5KG'=>32,
'4|1200|7KG'=>43,
'5|800|5.5KG'=>38,
'5|900|6KG'=>42,
'6|800|7KG'=>43,
'6|900|7KG'=>45];

// 奶粉 array2
var ENA2 =
['2|900'=>19,
'2|800'=>19,
'2|1200'=>26,
'3|800'=>21,
'3|900'=>30,
'3|1200'=>40,
'4|800'=>31,
'4|900'=>32,
'4|1200'=>43,
'5|800'=>38,
'5|900'=>42,
'6|800'=>43,
'6|900'=>45];

// 奶粉 array3
var ENA3 =
['2KG'=>19,
'3KG'=>21,
'4KG'=>31,
'5KG'=>32,
'6KG'=>42,
'7KG'=>45];

// EMS计算
var ENO =
['1KG'=>11,
'2KG'=>15,
'3KG'=>19,
'4KG'=>23,
'5KG'=>27,
'6KG'=>31,
'7KG'=>35,
'8KG'=>39,
'9KG'=>43,
'10KG'=>47,
'11KG'=>51,
'12KG'=>55,
'13KG'=>59,
'14KG'=>63,
'15KG'=>67,
'16KG'=>71,
'17KG'=>75,
'18KG'=>79,
'19KG'=>83,
'20KG'=>87];

</script>

<style type="text/css">
.tg {
  border-collapse: collapse;
  border-spacing: 0;
  border-color: #999;
  border: none;
  margin: 0 auto;
}

.tg td {
  font-family: Arial, sans-serif;
  font-size: 14px;
  padding: 10px 5px;
  border-style: solid;
  border-width: 0px;
  overflow: hidden;
  word-break: normal;
  border-color: #999;
  color: #444;
  background-color: #F7FDFA;
}

.tg th {
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  padding: 10px 5px;
  border-style: solid;
  border-width: 0px;
  overflow: hidden;
  word-break: normal;
  border-color: #999;
  color: #fff;
  background-color: #26ADE4;
}

.tg .tg-s6z2 {
  text-align: center
}

.tg .tg-dm0n {
  background-color: #f7fdfa
}

.tg .tg-5klj {
  background-color: #26ade4;
  color: #ffffff
}
</style>
<?php

JHTML::_('behavior.formvalidation');
echo $this->form;

?>
