<?php
/**
 * Module's entry point
 * 
 * @package    com.express
 * @subpackage Modules
 * @license    ???
 */
class modPriceCalcHelper
{
    /**
     * Retrieves the message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    public static function getPrice( $params )
    {
      $min_weight = $params->get('min_weight', '1');
      $max_weight = $params->get('max_weight', '1');
      $scale = 1;
      
      $result = "{";
      $result_re = "";
      $result_ems = "";
      $result_lap = "";
      $result_bp = "";
      $re_0 = $params->get('re_0', '1');
      $re_1 = $params->get('re_1', '1');
      $ems_0 = $params->get('ems_0', '1');
      $ems_1 = $params->get('ems_1', '1');
      $lap_0 = $params->get('lap_0', '1');
      $lap_1 = $params->get('lap_1', '1');
      $bp_0 = $params->get('bp_0', '1');
      $bp_1 = $params->get('bp_1', '1');
      $temp = 0;
      for($i=$min_weight;$i<=$max_weight;$i=$i+$scale){
	$temp = $re_0 + $i*$re_1;
	$result_re = $result_re.'"re'.$i.'":'.$temp.',';
	$temp = $ems_0 + $i*$ems_1;
	$result_ems = $result_ems.'"ems'.$i.'":'.$temp.',';
	$temp = $lap_0 + $i*$lap_1;
	$result_lap = $result_lap.'"lap'.$i.'":'.$temp.',';
	$temp = $bp_0 + $i*$bp_1;
	$result_bp = $result_bp.'"bp'.$i.'":'.$temp.',';
      }
      $result = $result.$result_re.$result_ems.$result_lap.$result_bp;
      $result = substr($result, 0, -1);
      $result = $result.'}';
      return $result;
    }
}
?>