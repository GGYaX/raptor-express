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
jimport('joomla.log.log');
$historic = $this->get('HistoricByCurrentUser');
?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.css" />
<script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<div class="row">
	<div class="col-xs-12 col-md-12 container">
        <table id="dataTable" class="display" cellspacing="0" width="100%">
            <thead>
            	<tr>
            		<th>时间</th>
            		<th>类别</th>
            		<th>包裹-号码</th>
            		<th>包裹-实际扣款金额</th>
            		<th>包裹-寄信人姓名</th>
            		<th>包裹-收件人姓名</th>
            		<th>包裹-长宽高重</th>
            		<th>包裹-包裹内容</th>
            		<th>包裹-备注</th>
            		<th>钱包-金额变动</th>
            		<th>钱包-哪个钱包</th>
            		<th>钱包-备注</th>
            	</tr>
        	</thead>
        	<tbody>
                <?php
                foreach ($historic as $k => $value) :
                $v = (array) $value;
                ?>
                <tr>
            		<td><?php echo $v['operation_date'];?></td>
            		<td><?php echo ($v['operation_type'] == 'ORD') ? '新建订单':'钱包变动';?></td>
            		<?php if ($v['operation_type'] == 'ORD'):?>
            		<td><?php echo $v['orderId'];?></td>
            		<td><?php echo $v['oAmount'];?></td>
            		<td><?php echo $v['oSenderName'];?></td>
            		<td><?php echo $v['oReceiverName'];?></td>
            		<td><?php echo $v['oLength'] . 'cm - ' . $v['oWide'] . 'cm - '. $v['oHeight'] . 'cm / ' . $v['oWeight'] . 'kg';?></td>
            		<td><?php echo $v['oCargoInfo'];?></td>
            		<td><?php echo $v['oComment'];?></td>
            		<td>N/A</td>
            		<td>N/A</td>
            		<td>N/A</td>
            		<?php else :?>
            		<td>N/A</td>
            		<td>N/A</td>
            		<td>N/A</td>
            		<td>N/A</td>
            		<td>N/A</td>
            		<td>N/A</td>
            		<td>N/A</td>
            		<td><?php echo $v['bAmount'];?></td>
            		<td><?php echo ($v['bWalletType'] == 'LAP') ? 'La Poste' : 'EMS';?></td>
            		<td><?php echo $v['bComment'];?></td>
            		<?php endif;?>
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