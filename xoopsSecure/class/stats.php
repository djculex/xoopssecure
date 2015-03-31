<?php
/**
 * Stats for XoopsSecure
 *
 * Providing the tools for showing various statistics for XoopsSecure
 *
 * @copyright  2015 Xoops.org (Culex.dk)
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @version    Release: @1.0@
 * @link       http://xoops.org
 * @since      Class available since Release 1.0.0
 */ 
 
 class XoopsSecureStats {
	
	var $totalscans;

	function __construct() {
		$totalscans = $this->getCount ('inittime');
		//$lastscan = $this->getLastScan($type);
	}
	/**
	 * Get Date of last scan based on type
	 * @param integer $type of scan (1,3 or cron)
	 * @return date $date
	 */
	 function getLastScan ($type)
	 {
		 global $xoopsDB;
		 $sql = "SELECT ".$what." FROM ".$xoopsDB->prefix('xoopsecure_stats');
		 $result = $xoopsDB->queryF($sql);
		 $count = $xoopsDB->getRowsNum($result);
		 return $count;
	 }
	 
	/**
	 * Get counts from table
	 * @param string $what to count
	 * @return integer $count
	 */
	 function getCount ($what)
	 {
		 global $xoopsDB;
		 $sql = "SELECT ".$what." FROM ".$xoopsDB->prefix('xoopsecure_stats');
		 $result = $xoopsDB->queryF($sql);
		 $count = $xoopsDB->getRowsNum($result);
		 return $count;
	 }
	/**
	 * Insert stats to stats
	 * @param timestamp date of scan 
	 * @param array issuenr issues this day
	 * @param array badusers users with bad ip
	 * @return void
	 */
	 function doStats ($inittime, $type, $issues, $badusers)
	 {
		global $xoopsDB;
		if ($this->checkTodaysStats () > 0) {
			 $sql = "UPDATE ".$xoopsDB->prefix('xoopsecure_stats'). " SET inittime = ".$inittime.",
                typenr = ".$type.", issuenr = ".count($issues).", issues = '".serialize($issues)."', badusers = '".serialize($badusers)."'
				 WHERE from_unixtime(inittime, '%m-%d-%Y') = '".date('m-d-Y',$inittime)."'";
		} else {
			$sql = "INSERT INTO ".$xoopsDB->prefix('xoopsecure_stats'). "(
                inittime,
				typenr,
                issuenr,
                issues,
                badusers) 
                    VALUES (
                        '".$inittime."',
						".$type.",
                        ".count($issues).",
                        '".serialize($issues)."',
                        '".serialize($badusers)."'
                    )";
		}
		$result = $xoopsDB->queryF($sql);
	 }
	 
	/**
	 * check if stats from db when of a  scan previously was done (last 1 day)
	 *
	 * @return false or true
	 */ 
	function checkTodaysStats ()
	{
		global $xoopsDB;
		$sql = "SELECT inittime FROM ".$xoopsDB->prefix("xoopsecure_stats")." WHERE from_unixtime(inittime, '%m-%d-%Y') = '".date('m-d-Y', time())."'";
        $result = $xoopsDB->queryF($sql);
		$count = $xoopsDB->getRowsNum($result);
		return $count;
	}
	
	/**
	 * Removes issues from db when a scan previously was done (last 1 day)
	 *
	 * @return void
	 */ 
	function rmTodaysData ()
	{
		global $xoopsDB;
		$tdm = strtotime('today midnight');
		$tmm = strtotime('tomorrow midnight');
		$sql = "DELETE FROM ".$xoopsDB->prefix("xoopsecure_stats")." WHERE inittime BETWEEN ".$tdm." AND ".$tmm."";
        $result = $xoopsDB->queryF($sql);
	}
}
?>