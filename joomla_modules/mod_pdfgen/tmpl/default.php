<?php
// No direct access
defined('_JEXEC') or die;
require_once( dirname(__FILE__) . '/../jHelper.php' );

$user = JFactory::getUser();
// echo ($user->guest);
// echo ($user->id);

// if (($user->guest)
// || !($user->id > 0)){
//   JFactory::getApplication()->redirect(JURI::base()
//   .'index.php?option=com_users&view=login', $error, 'error' );
// }

//$waybill = JRequest::getVar('waybill', '0');
$jinput = JFactory::getApplication()->input;
$solution = $jinput->get('exp-solution', null);
$waybill = $jinput->get('waybillid', null);
$getwaybill = $jinput->get('getwaybill', "0");

//Check variable exists
if(
  ($getwaybill === "1")
  && ($solution === "EMS")
  && ($waybill !== null)
  && (strlen($waybill)===12)
){
  modPDFGenHelper::getPDF($waybill);
}
?>

<?php if(($solution === "EMS") && ($waybill !== null) && (strlen($waybill)===12)) : ?>
<button name="Generate" class="btn btn-primary" onclick="window.location.href=window.location.href+'&getwaybill=1'">生成PDF</button>
<br>
<?php endif; ?>

<?php if(($solution === "LAP") && ($waybill !== null)) : ?>
  <button name="Download" class="btn btn-primary" onclick="window.location.href=<?php echo JUri::base();?>/media/about.pdf'">用户说明下载</button>
  <br>
<?php endif; ?>
