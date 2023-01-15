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

$dirname       = \basename(\dirname(__DIR__));
$moduleHandler = \xoops_getHandler('module');
$xoopsModule   = XoopsModule::getByDirname($dirname);
$moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
$sysPathIcon32 = $moduleInfo->getInfo('sysicons32');

$adminmenu[] = [
    'title' => \_MI_XOOPSSECURE_ADMENU1,
    'link' => 'admin/index.php',
    'icon' => $sysPathIcon32 . '/dashboard.png',
];
$adminmenu[] = [
    'title' => \_MI_XOOPSSECURE_ADMENU2,
    'link' => 'admin/scanhome.php',
    'icon' => $sysPathIcon32 . '/security.png',
];
$adminmenu[] = [
    'title' => \_MI_XOOPSSECURE_ADMENU3,
    'link' => 'admin/serverhome.php',
    'icon' => $sysPathIcon32 . '/exec.png',
];
$adminmenu[] = [
    'title' => \_MI_XOOPSSECURE_ADMENU4,
    'link' => 'admin/download.php',
    'icon' => $sysPathIcon32 . '/download.png',
];

$adminmenu[] = [
    'title' => \_MI_XOOPSSECURE_ABOUT,
    'link' => 'admin/about.php',
    'icon' => $sysPathIcon32 . '/about.png',
];
