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

$model = $this->getModel();
$accessError = $model->getAccessError();
?>
<?php

if (isset($accessError) || ! empty($accessError)) {
    // access_failure
    $error = $accessError['error'];
    require_once 'access_failure.php';
} else {
    require_once 'formulaire.php';
}
?>
