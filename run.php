<?php
/**
 * ****************************************************************************
 * marquee - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Hervé Thouzard (http://www.herve-thouzard.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         marquee
 * @author 			Hervé Thouzard (http://www.herve-thouzard.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */


include_once("../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/modules/xoopsSecure/include/functions.php");
include_once (XOOPS_ROOT_PATH.'/class/template.php');
include_once XOOPS_ROOT_PATH . '/modules/xoopssecure/class/log.php';
include_once XOOPS_ROOT_PATH . '/modules/xoopssecure/class/scan.php';
include_once XOOPS_ROOT_PATH . '/modules/xoopssecure/class/mech.php';

include_once(XOOPS_ROOT_PATH."/header.php");
$scan = new xoopsSecure_scan;
$log  = new xoopsSecure_log;
$sys = new xoopsSecure_mech;

global $xoopsUser, $xoTheme, $xoopsTpl,$xoopsLogger, $scan;
$xoopsLogger->activated = true;
error_reporting(E_ALL); 

set_time_limit(999999);

$lang = $xoopsConfig['language'];
if ( file_exists(XOOPS_ROOT_PATH.'/modules/xoopsSecure/language/'.$lang.'/admin.php') ) {
    include(XOOPS_ROOT_PATH
    . '/modules/xoopsSecure/language/'
    . $lang.'/admin.php');
} else {
    include(XOOPS_ROOT_PATH
    . '/modules/xoopsSecure/language/english/admin.php');
}

if (TRUE == $scan->checkCronDate ()) {

$adminscript  =  "var xoopssecure_url = '" . XOOPS_URL . "/modules/xoopssecure/admin/'\n";
$adminscript .=  "var xoopssecure_anonurl = '" . XOOPS_URL . "/modules/xoopssecure/'\n";
$adminscript .=  "var xoopssecure_xoopsurl = '" . XOOPS_URL . "'\n";
$adminscript .=  "var xoopssecure_root = '".XOOPS_ROOT_PATH."'\n";
$adminscript .=  "var xoopssecure_scanurl = '" . $scan->urlToScan . "';\n";
$adminscript .=  "var xoopssecure_malwaretitle = '" . _XOOPSSECURETITLEMALWARE . "';\n";
$adminscript .=  "var xoopssecure_autoindexfilestitle = '" . _XOOPSSECURETITLEINDEXFILES . "';\n";
$adminscript .=  "var xoopssecure_checkindexfilestitle = '" . _XOOPSSECURETITLECHECKINDEXFILES . "';\n";
$adminscript .=  "var xoopssecure_autoindexfiles = {$scan->autoindexcreate};\n";
$adminscript .=  "var xoopssecure_indexfiletype = {$scan->indexfiletype};\n";
$adminscript .=  "var xoopssecure_autochmod = {$scan->autochmod[0]};\n";
$adminscript .=  "var xoopssecure_autochmodtitle = '"._XOOPSSECURETITLECHMOD."';\n";
$adminscript .=  "var xoopssecure_dbhasfiles = ".$scan->xoopssecure_fullscan_hasFiles().";\n";
$adminscript .=  "var xoopssecure_dbhasissues = ".$scan->xoopssecure_dbHasMallIssues().";\n";

$xoTheme->addScript('','',$adminscript);
$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/jquery.js');
$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/jquery-ui.js');
$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/xoopssecure.js');
$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/jquery.colorbox.js');
$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/jqueryFileTree.js');
$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/xoopssecure_cron.js');
$xoTheme->addStylesheet('/modules/xoopssecure/assets/css/base/jquery.ui.all.css');
$xoTheme->addStyleSheet('/modules/xoopssecure/assets/css/xoopsSecure.css');

$xoopsTpl->display(XOOPS_ROOT_PATH .'/modules/xoopssecure/templates/xoopssecure_run.tpl'); 
} else {
    echo "NOT TRUE";
}



/*
$scan->cronscan = true;
$hash = array ();
$files = array();

$scan->scantype = 1;
$scan->getnewfileinfo ($url = XOOPS_ROOT_PATH);

$info = $scan->getnewfileinfo ($url = $scanurl);
header('Content-type: application/json');
echo "{\"result\":$info}"; 
*/
include(XOOPS_ROOT_PATH."/footer.php");