<?php
defined('_JEXEC') or die();
?>
<?php

$result = $this->get('insert');
if (! empty($result['errors'])) {
    $errors = $result['errors'];
    require_once 'insert_failure.php';
} else {
    require_once 'insert_success.php';
}
?>
<?php

require_once 'formulaire.php';
?>