<?php
 
class CtrlUtil {
 
	public static function setOpt($objItem, $optIds) {		
		foreach($optIds as $optId) {
			$opt = Opt::find($optId);
			$objItem->optStr .= $opt->name . ' ';
			$objItem->optPrice += $opt->price;
		}
		$objItem->save();
	}
}