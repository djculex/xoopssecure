<?php

declare(strict_types=1);

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

use Xmf\Module\Admin;
use XoopsModules\Xoopssecure\FileH;
use XoopsModules\Xoopssecure\Helper;

require dirname(__DIR__, 3) . '/include/cp_header.php';
require_once dirname(__DIR__) . '/include/common.php';

$sysPathIcon16 = '../' . $GLOBALS['xoopsModule']->getInfo('sysicons16');
$sysPathIcon32 = '../' . $GLOBALS['xoopsModule']->getInfo('sysicons32');
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
$modPathIcon16 = XOOPSSECURE_URL . '/' . $GLOBALS['xoopsModule']->getInfo('modicons16') . '/';
$modPathIcon32 = XOOPSSECURE_URL . '/' . $GLOBALS['xoopsModule']->getInfo('modicons32') . '/';

// Get instance of module
$helper = Helper::getInstance();
$myts = MyTextSanitizer::getInstance();
//
if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    include_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new XoopsTpl();
}

// Load languages
xoops_loadLanguage('admin', 'xoopssecure');
xoops_loadLanguage('modinfo', 'xoopssecure');

// Local admin menu class
if (file_exists($GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php'))) {
    include_once $GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php');
} else {
    redirect_header('../../../admin.php', 5, _AM_MODULEADMIN_MISSING);
}

xoops_cp_header();

$fh = new fileH();
$crontest = $fh->timeForCron;

$script = "var xoopsSecureSysUrl = '" . XOOPS_URL . "/modules/xoopssecure/admin/';" . "\n";
$GLOBALS['xoTheme']->addScript('', '', $script);

// System icons path
$GLOBALS['xoopsTpl']->assign('sysPathIcon16', $sysPathIcon16);
$GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
$GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
$GLOBALS['xoopsTpl']->assign('modPathIcon32', $modPathIcon32);

$adminObject = Admin::getInstance();
$style = XOOPSSECURE_URL . '/assets/css/admin/style.css';
$GLOBALS['xoTheme']->addStylesheet('', '', $style);
