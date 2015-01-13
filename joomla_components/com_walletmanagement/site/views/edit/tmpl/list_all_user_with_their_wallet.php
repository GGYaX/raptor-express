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

$allWallet = $model->getAllUserWithTheirWallet();
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
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.css" />
<script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<div class="row">
	<div class="col-xs-12 col-md-12">
        <table id="dataTable" class="display" cellspacing="0" width="100%">
            <thead>
            	<tr>
            		<th>用户名</th>
            		<th>用户姓名</th>
            		<th>EMS钱包号码</th>
            		<th>EMS钱包金额</th>
            		<th>La poste钱包号码</th>
            		<th>La poste钱包金额</th>
            		<th>修改按钮</th>
            	</tr>
        	</thead>
        	<tbody>
                <?php
                foreach ($allWallet as $k => $v) :
                ?>
                <tr>
            		<td><?php echo $k;?></td>
            		<td><?php echo $v['name'];?></td>
            		<td><?php echo $v['emsId'];?></td>
            		<td><?php echo $v['emsAmount'];?></td>
            		<td><?php echo $v['laposteId'];?></td>
            		<td><?php echo $v['laposteAmount'];?></td>
                    <td><a href="<?php echo JURI::current() ?>?task=edit&view=edit&uid=<?php echo $v['uid'];?>">点击修改</a></td>
            	</tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
jQuery(document).ready(function() {
    jQuery('#dataTable').DataTable();
} );
</script>