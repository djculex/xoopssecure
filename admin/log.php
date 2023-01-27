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

use Xmf\Request;
use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use XoopsModules\Xoopssecure\FileH;
use XoopsModules\Xoopssecure\SpamScanner;
use XoopsModules\Xoopssecure\Db;

require __DIR__ . '/header.php';
$templateMain = 'xoopssecure_admin_log.tpl';
$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/bootstrap.css'));
$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/admin/style.css'));
$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/admin/style.css'));

$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootstrap.bundle.js'));
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootbox.min.js'));
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/scannerAdmin.js'));

$adminObject = \Xmf\Module\Admin::getInstance();
$dat = new db();

// It recovered the value of argument op in URL$
$op                 = Request::getString('op', 'list');
$date                 = isset($_GET['starttime']) ? $_GET['starttime'] : $dat->getLatestTimeStamp();
$moduleDirName      = $GLOBALS['xoopsModule']->getVar('dirname');
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

\xoops_loadLanguage('scanner', $moduleDirName);
\xoops_loadLanguage('log', $moduleDirName);

//xoops_cp_header();
$resMal = $dat->loadMalIssue($date);
$resIF =  $dat->loadIFissues($date);
$resFP =  $dat->loadFPissues($date);
$resCS = $dat->loadCsIssue($date);
$resERR = $dat->loadErrissues($date);

// help file from admin
$GLOBALS['xoopsTpl']->assign(
    [
    'lang_log_CONFOMITFILE_TEXT'    => _LOG_XOOPSSECURE_CONFOMITFILE_TEXT,
    'lang_log_CONFOMITDIRS_TEXT'    => _LOG_XOOPSSECURE_CONFOMITDIRS_TEXT,
    'lang_log_CONFDELISSUE_TEXT'    => _LOG_XOOPSSECURE_CONFDELISSUE_TEXT,
    'lang_log_CONFYES'                => _OK,
    'lang_log_CONFNO'                => _CANCEL,
    'lang_log_SCANDATEHUMAN'        => date('d-m-Y H:i:s', intval(($date / 1000))),
    'lang_log_SCANDATEDELETE'        => intval($date),
    'lang_log_CONFDELETELOG_TEXT'    => _LOG_XOOPSSECURE_CONDELETELOG_TEXT,
    'lang_log_NOTHINGHERE_TITLE'    => _LOG_XOOPSSECURE_NOTHINGHERE_TITLE,
    'lang_log_NOTHINGHERE_DESC'        => _LOG_XOOPSSECURE_NOTHINGHERE_DESC,
    ]
);

$GLOBALS['xoopsTpl']->assign('resMal', $resMal);
$GLOBALS['xoopsTpl']->assign('resIF', $resIF);
$GLOBALS['xoopsTpl']->assign('resFP', $resFP);
$GLOBALS['xoopsTpl']->assign('resCS', $resCS);
$GLOBALS['xoopsTpl']->assign('resERR', $resERR);
require __DIR__ . '/footer.php';
