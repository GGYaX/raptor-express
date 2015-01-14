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
