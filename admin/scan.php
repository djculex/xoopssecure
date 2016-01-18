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

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once __DIR__ . '/admin_header.php';
include_once(XOOPS_ROOT_PATH.'/class/template.php');
require_once XOOPS_ROOT_PATH . '/modules/xoopssecure/class/log.php';
require_once XOOPS_ROOT_PATH . '/modules/xoopssecure/class/scan.php';
require_once XOOPS_ROOT_PATH . '/modules/xoopssecure/class/mech.php';
include_once XOOPS_ROOT_PATH . '/modules/xoopssecure/class/backup.php';

$scan = new xoopsSecure_scan;
$log  = new xoopsSecure_log;
$sys = new xoopsSecure_mech;
$zip = new xoopsSecureZipper;

global $xoopsUser, $xoopsLogger;
$xoopsLogger->activated = false;
//error_reporting(E_ALL);

set_time_limit(999999);
$task = intval($_GET['scantype']);
$scanref = intval($_GET['ref']);
$scanurl = $_GET['scanurl'];
$scan->inittime = $_GET['scanstart'];

// Quick scan = 1
if ($task == 1) {
    $scan->scantype = $scanref;
    $info = $scan->getnewfileinfo($url = $scanurl);
    header('Content-type: application/json');
    echo "{\"result\":$info}";
}

// Developer scan
if ($task == 2) {
    $type = xoopssecure_GetModuleOption('checkstylestyle', $repmodule='xoopssecure');
    $_SERVER['argc'] = 5;
    
    $standard = '--standard='.$type[0];
    $report = "--report=full";
    $reportWidth = "--report-width=110";
    
    $url = $_GET['path'];

    $_SERVER['argv'] = array("phpcs.php",$standard,$url,$report,$reportWidth);
    echo "<div class='xoopssecure_csf_report_cont'><p>";
    echo sprintf(_AM_XOOPSSECURE_DEVELOPERSCANRESULTTITLE, $url, $type[0]);
    echo "</p>";
    echo "<div id = 'xoopssecure_showoksign' style='display:none;'><img src = '../assets/images/okstamp.PNG' "
        ." height='100px' width= '100px' /></div>";
    echo '<div class="xoopssecure_csf_report"><code>';
    include XOOPS_ROOT_PATH. '/modules/xoopsSecure/plugins/codesniffer/CodeSniffer/phpcs.php';
    echo '</code></div>';
    echo '</div>';
}

// full scan = 3
if ($task == 3) {
    $scan->scantype = $scanref;
    if ($scanref == 3) {
        $scan->emptyFiles();
    }
    if ($scanurl !== XOOPS_ROOT_PATH) {
        $scan->getnewfileinfo($url = XOOPS_ROOT_PATH);
    }
    $info = $scan->getnewfileinfo($url = $scanurl);
    header('Content-type: application/json');
    echo "{\"result\":$info}";
}

// Single file scan = 4
if ($task == 4) {
    $scan->scantype = $scanref;
    $file = $_GET['file'];
    //$scan->savefileinfo ($file);
    $scanref == 3 ? $scan->getLines(json_decode($file), $scanref) : $scan->gethashes(json_decode($file), $scanref);
}

// indexfile creating
if ($task == 5) {
    $dir = $_GET['file'];
    // auto indexfile creation
    $scan->autoindexcreate == 1 ? $scan->createindexfiles($dir) : '';
}

// Chmodding
if ($task == 6) {
    $dir = $_GET['file'];
    // auto indexfile creation
    header('content-type: application/json; charset=utf-8');
    if ($scan->autochmod[0] == 1) {
        echo $scan->recursiveChmod(str_replace("\\", "/", $dir), $scanref);
    } else {
        break;
    }
}

// create .httaccess
if ($task == 7) {
    $dir = $_GET['file'];
    // auto indexfile creation
    $scan->autoHtaccessCreate == 1 ? $scan->createHttaccess($dir) : '';
}

// Delete from log
if ($task == 8) {
    header('content-type: application/json; charset=utf-8');
    $id = intval($_GET['id']);
    $table = ($_GET['table']);
    $scan->deleteById($id, $table);
}

// add to ignore
if ($task == 9) {
    $file = $_GET['file'];
    $linenumber = $_GET['ln'];
    $scan->Ignore($file, $linenumber);
    header('content-type: application/json; charset=utf-8');
}

// Empty ignore
if ($task == 10) {
    header('content-type: application/json; charset=utf-8');
}

// indexfile check
if ($task == 11) {
    $dir = $_GET['file'];
    // check
    $scan->checkMissingIndexfile($dir);
}

// Set develop path
if ($task == 12) {
    //$dir = $_GET['developpath'];
    $scan->developPath = $_GET['developpath'];
    // check
    //$scan->checkMissingIndexfile($dir);
}

// Set Full scan path
if ($task == "12f") {
    //$dir = $_GET['developpath'];
    $scan->urlToScan = $_GET['urlToScan'];
    // check
    //$scan->checkMissingIndexfile($dir);
}

// Show Malware log
if ($task == 13) {
    $log->getIssues('file');
    $log->getIssues('dir');
    $xoopsTpl->display(XOOPS_ROOT_PATH .'/modules/xoopssecure/templates/admin/showscan.tpl');
}

// ADD file / dir to ignore
if ($task == 14) {
    $file = $_GET['file'];
    $type = $_GET['ref'];
    $val = $_GET['val'];
    $table = isset($_GET['table']) ? $_GET['table'] : '';
    if ($val != 'clear') {
        $scan->ignoreFile($file, $type, $val);
    } else {
        $log->clear($file);
    }
}

// ADD file / dir to chmod reset list
if ($task == 16) {
    $file = $_GET['file'];
    $type = $_GET['ref'];
    $val = $_GET['val'];
    $table = $_GET['table'];
    $scan->ignoreFile($file, $type, $val);
}

// Get list back of ignore dirs / files
if ($task == 17) {
    $genus = $_GET['ref'];
    $val = $_GET['val'];
    $scan->getIgnores($genus, $val);
}

// do backup
if ($task == 18) {
    if ($this->backuptype != 'none') {
        $zip->doZip($zip->archive, $zip->dirToBackup);
        $nl = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $zip->archive);
        $link  = "<a id = 'xoopssecure_bdl' href = '".$nl."'> "
        . _AM_XOOPSSECURE_BACKUP_LINK_DESC." "
        . $nl
        . "</a>";
        echo $link;
    }
}

// download the zip file
if ($task == 19) {
    if ($scan->autobackup = 1 || $scanref = 18) {
        xoopssecure_DownloadFile($zip->archive);
    }
}

//delete backupfolder from server
if ($task == 20) {
    if ($scan->autobackup = 1 || $scanref = 18) {
        //xoopssecure_deleteBackupFolder ($zip->archive);
        xoopssecure_deleteFolder($zip->dest);
    }
}
