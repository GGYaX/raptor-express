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
<div id="system-message-container">
    <?php foreach ($msgArray as $v):?>
	<div id="system-message">
		<a class="close" data-dismiss="alert">×</a>
		<h4 class="alert-heading"><?php echo $v['msgHeader'];?></h4>
		<div class="alert alert-<?php echo $v['level'];?>">
			<div>
				<p><?php echo $v['msgBody'] ;?></p>
			</div>
		</div>
	</div>
	<?php endforeach;?>
</div>