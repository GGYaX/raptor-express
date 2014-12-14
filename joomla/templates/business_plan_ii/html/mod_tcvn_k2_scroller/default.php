<?php
/*
# ------------------------------------------------------------------------
# TCVN Item Scroller for K2 - Joomla 2.5
# ------------------------------------------------------------------------
# Copyright(C) 2012-2013 www.TheCoders.vn. All Rights Reserved.
# @license http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
# Author: TheCoders.vn
# Websites: http://TheCoders.com
# ------------------------------------------------------------------------
*/
 
// no direct access
defined('_JEXEC') or die;
$url_load   = JURI::base().'modules/mod_tcvn_k2_scroller/assets/';	
?>
<style type="text/css">
#tcvn-scroller-k2<?php echo $module->id; ?> {
	width:<?php echo $sliderWidth; ?>px;
	height: <?php echo $sliderHeight; ?>px; 
}
#tcvn-scroller-k2<?php echo $module->id; ?> {
    position: relative;
}

#tcvn-scroller-k2<?php echo $module->id; ?> .buttonlight
{
	color: #777;
	padding: 0 20px 0 20px;
	text-align: center;
	background: #999999 url('<?php echo $url_load; ?>images/btn-light.png') repeat-x center top;
	border: 1px solid #cdcdcd;
	text-decoration: none;
	font-weight: bold;
	margin-top:10px;
	height: 30px;
	line-height: 30px;
	font-size: 12px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	padding:5px 10px 5px 10px;
}
#tcvn-scroller-k2<?php echo $module->id; ?> .buttonlight:hover
{
	background:url('<?php echo $url_load; ?>images/btn-dark.png') repeat-x center top;
	color:#fff;
	border: 1px solid #151515;
}
</style>
<div id="tcvn-scroller-k2<?php echo $module->id; ?>" class="tcvn-scroller-k2 <?php echo $theme.$moduleclass_sfx;?>">
    <ul>
    	<?php 
		foreach($items as $key => $item):
			if($selectthumb)
				$image = JURI::base().'modules/mod_tcvn_k2_scroller/libs/timthumb.php?src='.$item->image.'&w='.$imageWidth.'&h='.$imageHeight.'&a=c&q=99&z=0';
			else
				$image = $item->image;

			$intro = $item->introtext;
            $intro = trim($intro);
			$link  = ($linkTitle) ? '<a href="'.$item->link.'" title="'.$item->title.'">'.$item->title.'</a>' 
					: $item->title;
		?>
        <li>
            <a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>">
                <img id="thumb" class="thumb" data-bw="<?php echo $image; ?>" src="<?php echo $image; ?>" />
            </a>
			
			<!-- Show title -->
			<?php if($showTitle): ?>
			<h3><?php echo $link; ?></h3>
			<?php endif; ?>
			
			<!-- Show intro text -->
			<?php if($showDesc && !empty($intro)):?>
			<p><?php echo $intro; ?></p>
			<?php endif; ?>
			
			<!-- Show read more -->
            <?php if($showread){ ?>
			<a class="morebutton" href="<?php echo $item->link; ?>"><?php echo JText::_('READ_MORE'); ?></a>
            <?php }?>
        </li>
        <?php endforeach; ?>
    </ul>
	
	<!-- Control Button -->
    <div class="toolbar">
        <div class="left"></div>
        <div class="right"></div>
    </div>
</div>