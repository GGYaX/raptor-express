<?php
/**
* @package Joomla.Administrator
* @subpackage com_helloworld
*
* @copyright Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/

// No direct access to this file
defined('_JEXEC') or die();
?>
<?php

$jinput = JFactory::getApplication()->input;
$walletIdToEdit = $jinput->get('walletid');
$walletType = $jinput->get('wallettype');
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
<div class="row">
	<div class="col-xs-12 col-md-12">
		<form
			action="<?php echo JURI::current() ?>?task=editwalletid&view=edit&walletid=<?php echo $walletIdToEdit;?>&wallettype=<?php echo $walletType;?>"
			method="post" accept-charset="utf-8"
			class="form-validate form-horizontal">
			<table class="tg">
				<tr>
					<th class="tg-dm0n"></th>
					<th class="tg-s6z2">当前钱包ID: <?php echo $walletIdToEdit;?></th>
					<th class="tg-s6z2">当前钱包类别: <?php echo $walletType;?></th>
				</tr>
				<tr>
					<td class="tg-5klj">修改为</td>
					<td class="tg-031e tg-s6z2"><input type="text" name="newwalletid"></td>
					<td class="tg-031e tg-s6z2">N/A</td>
				</tr>
			</table>
			<button type="submit"
				class="btn btn-rounded btn-primary btn-lg smooth-scroll validate">点击修改</button>
		</form>
	</div>
</div>