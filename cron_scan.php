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
include_once (XOOPS_ROOT_PATH.'/class/template.php');
include_once(XOOPS_ROOT_PATH."/modules/xoopsSecure/include/functions.php");
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

// full scan = 3
if ($task == 3) {
    $scan->scantype = $scanref;
    $scan->emptyIssues ();
    if ($scanref == 3) {
        $scan->emptyFiles ();
    }
    if ($scanurl !== XOOPS_ROOT_PATH) {
        $scan->getnewfileinfo ($url = XOOPS_ROOT_PATH);
    }
    $info = $scan->getnewfileinfo ($url = $scanurl);
    header('Content-type: application/json');
    echo "{\"result\":$info}";    
}

// Single file scan = 4
if ($task == 4) {
    $scan->scantype = $scanref;
    $file = $_GET['file'];
    //$scan->savefileinfo ($file);
    $scanref == 3 ? $scan->getLines (json_decode($file), $scanref) : $scan->gethashes(json_decode($file), $scanref);
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
        echo $scan->recursiveChmod (str_replace ("\\", "/", $dir), $scanref);
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

// indexfile check
if ($task == 11) {
    $dir = $_GET['file'];
    // check
    $scan->checkMissingIndexfile($dir);
}

