<?php

declare(strict_types=1);

namespace XoopsModules\Xoopssecure;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Xoops XoopsSecure module for xoops
 *
 * @copyright 2021 XOOPS Project (https://xoops.org)
 * @license   GPL 2.0 or later
 * @package   Xoopssecure
 * @since     1.0
 * @min_xoops 2.5.11
 * @author    Culex - Email:culex@culex.dk - Website:https://www.culex.dk
 */

use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use XoopsModules\Xoopssecure\Db;

require_once dirname(__DIR__, 3) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';

require __DIR__ . '/header.php';
$helper = Helper::getInstance();

$type = $_GET['type'] ?? '';
$dir = (isset($_GET['Dir'])) ? $_GET['Dir'] : '';
$val = (isset($_GET['val'])) ? $_GET['val'] : '';
$t = time();

$fh = new FileH();
$dat = new Db();
$spam = new SpamScanner();

$filename = "";
switch ($type) {
    // Get a list of files to scan from path defined. From these only thoose
    // modified since last scan and skipping files defined in omit files
    case 'getFilesJson':
        header("Content-Type: application/json; charset=UTF-8");
        $pattern = "/^.*\.php$/i";
        $dir = $spam->startPathCs;
        //echo $dir;
        // script time : 18.29 seconds (16213 files) without db check
        // ----- // -- : 31,00 seconds (16213 files) with mod check empty db
        // ----- // -- : 38,00 seconds (63 files)      with unix - 2 days
        $f = $spam->getFilesJsonCS($dir, $pattern);
        echo json_encode($f, JSON_PRETTY_PRINT);
        break;

    // Do malware scan on single page.
    case 'singleCsScan':
        //header("Content-Type: application/json; charset=UTF-8");
        $p = $_GET['filePath'];
        $spam->timestamp = $_GET['scanstart'];
        if (!$dat->filealreadyscanned($p, $spam->timestamp)) {
            $options['format'] = "array"; // default format

            // Get user selection
            $filePath = array($_GET['filePath']);
            $resultDir = "/";
            $configFile = XOOPS_ROOT_PATH . "/modules/xoopssecure/class/phpcheckstyle/config/xoops.cfg.xml";
            $options['exclude'] = array();
            $formats = explode(',', $options['format']);
            $style = new PHPCheckstyle($formats, $resultDir, $configFile, true, false, false, $level = false);
            $style->processFiles($filePath, $options['exclude']);
            $a[] = $style->_reporter->reporters[0]->outputFile;
            $dat->parseCsArray($a, $spam->timestamp);
        }
        break;
}

$GLOBALS['xoopsLogger']->activated = false;
