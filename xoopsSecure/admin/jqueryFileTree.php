<?php
//
// jQuery File Tree PHP Connector
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// Output a list of files for jQuery File Tree
//
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once __DIR__ . '/admin_header.php';
global $xoopsLogger;
$xoopsLogger->activated = false;
//error_reporting(E_ALL);

$type = (isset($_GET['config'])) ? $_GET['config'] : 'develop';
$_POST['dir'] = urldecode($_POST['dir']);
$root = isset($root) ? $root : XOOPS_ROOT_PATH;

xoopssecure_ignoreFileTree($root, $dir, $type);
