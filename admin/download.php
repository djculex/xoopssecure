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

/*
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
use Xmf\Request;
use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use XoopsModules\Xoopssecure\Xoopssecure_FileH;
use XoopsModules\Xoopssecure\Xoopssecure_SpamScanner;
use XoopsModules\Xoopssecure\Xoopssecure_Db;
use XoopsModules\Xoopssecure\Xoopssecure_Api;

require __DIR__.'/header.php';
/*
 * Vars defined by inclusion of ./admin_header.php
 *
 * @var \XoopsModules\Xoopssecure\Admin $admin
 * @var \Xmf\Module\Admin $adminObject
 * @var \XoopsModules\Xoopssecure\Helper $helper
 * @var string $moduleDirName
 * @var string $moduleDirNameUpper
 */
$templateMain = 'xoopssecure_admin_download.tpl';
$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/bootstrap.css'));
$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/admin/style.css'));
$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/admin/style.css'));

$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootstrap.bundle.js'));
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootbox.min.js'));
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/scannerAdmin.js'));

$adminObject = Admin::getInstance();

$moduleDirName      = $GLOBALS['xoopsModule']->getVar('dirname');
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
xoops_loadLanguage('scanner', $moduleDirName);
xoops_loadLanguage('mech', $moduleDirName);
xoops_loadLanguage('download', $moduleDirName);

$fileClass = new Xoopssecure_fileH();
$api       = new Xoopssecure_Api();

$GLOBALS['xoopsTpl']->assign('backupfiles', $fileClass->getBackupsFiles());
$GLOBALS['xoopsTpl']->assign('downloadfiles', $api->parseObjs());
require __DIR__.'/footer.php';
