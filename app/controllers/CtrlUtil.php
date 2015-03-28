<?php
 
class CtrlUtil {
 
	public static function setOpt($objItem, $optIds) {
		if ($optIds == null) {
			$objItem->optStr = ' ';
			return $objItem;
		}	
		sort($optIds);	
		$objItem->optStr = '';
		$objItem->optPrice = 0;
		foreach($optIds as $optId) {
			$opt = Opt::find($optId);
			$objItem->optStr .= $opt->name . ' ';
			$objItem->optPrice += $opt->price;
		}
		return $objItem;
	}
	
	public static function getOptStr($optIds) {
		if ($optIds == null) {
			return ' ';
		}
		$optStr = '';
		foreach($optIds as $optId) {
			$opt = Opt::find($optId);
			$optStr .= $opt->name . ' ';
		}
		return $optStr;
	}
}