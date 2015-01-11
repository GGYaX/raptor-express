<?php
/**
* @package Joomla.Administrator
* @subpackage com_tracking
*
* @copyright Copyright (C) 2014
* @license GNU??? BSD???
*/
defined('_JEXEC') or die();
$displayResult = false;
$trackingInfo = $this->get('TrackingInfo');
if (is_array($trackingInfo) && ! empty($trackingInfo)) {
    $displayResult = true;
}
?>
<div class="row">
            <?php if($displayResult == true) :?>
            <div class="col-xs-12 col-md-12 center" style="padding-top: 30px; padding-bottom:30px;">
    			<a class="btn btn-primary btn smooth-scroll"
				data-toggle="collapse" data-target="#reDispFr">当前单号：<?php echo $trackingInfo['order_id']; ?><br />点击展开新的查询</a>
            </div>
            <?php endif;?>
	<div class="login-wrap">
		<div class="login">
    		<?php
    		$itemId = JRequest::getVar( 'Itemid' );
    		?>
            <?php if($displayResult == true) :?>
			<form
				action="<?php echo JURI::current();?>?Itemid=<?php echo $itemId;?>"
				method="post" class="form-horizontal collapse" id="reDispFr">
			<?php else :?>
                <form
					action="<?php echo JURI::current();?>?Itemid=<?php echo $itemId;?>"
					method="post" class="form-horizontal" id="reDispFr" style="padding-top: 30px;">
            <?php endif;?>
				<fieldset>
						<div class="form-group">
							<div class="col-sm-12">
								<input type="text" name="trackingnumber" id="trackingnumber"
									value="" size="15" required aria-required="true"
									oninvalid="this.setCustomValidity('<?php echo JText::_('COM_TRACKING_VIEW_FRONT_INPUT_INVALID_MESSAGE')?>')"
									oninput="setCustomValidity('')"
									placeholder="<?php echo JText::_('COM_TRACKING_VIEW_FRONT_INPUT_PLACEHOLDER');?>">
							</div>
						</div>
					</fieldset>
					<div class="action-button form-group">
						<div class="col-sm-12">
							<button type="submit" class="btn btn-primary btn smooth-scroll"><?php echo JText::_('COM_TRACKING_VIEW_FRONT_SEARCH_BUTTON_TITLE');?></button>
						</div>
					</div>
				</form>

		</div>
	</div>
</div>
<?php if ($displayResult == true) : ?>
<div id="tracRes" class="hidden"><?php echo json_encode($trackingInfo);?></div>
<div id="tracErrCL" class="hidden"><?php echo json_encode($this->get('ErrorCodeLibelle'));?></div>
<!-- Handler by JS -->
<div id="resDisp"></div>
<script>
displayResult();
</script>
<?php endif;?>
