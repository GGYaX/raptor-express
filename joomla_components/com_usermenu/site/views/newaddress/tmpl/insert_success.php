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
	<div id="system-message">
		<div class="alert alert-success">
			<a class="close" data-dismiss="alert">×</a>
			<div>
                <?php echo $result['succes']; ?>
			</div>
		</div>
	</div>
</div>
<?php require_once 'bloc.php';?>