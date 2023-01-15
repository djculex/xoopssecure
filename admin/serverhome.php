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
use XoopsModules\Xoopssecure\Mech;

require __DIR__ . '/header.php';


$adminObject = \Xmf\Module\Admin::getInstance();

// It recovered the value of argument op in URL$
$op                 = Request::getString('op', 'list');
$moduleDirName      = $GLOBALS['xoopsModule']->getVar('dirname');
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);
\xoops_loadLanguage('mech', $moduleDirName);
$mech = new Mech();
$testserver = $mech->testServer();
$systeminfo = $mech->systemArray();
$phpinfo = $mech->phpInfoArray();

$GLOBALS['xoopsLogger']->activated = false;

$GLOBALS['xoopsTpl']->assign('phpini', $testserver['phpini']);
$GLOBALS['xoopsTpl']->assign('testserverstats', $testserver['status']);

$GLOBALS['xoopsTpl']->assign(
    [
    'pcname'                => $testserver['status']['pcname'],
    'os'                    => $testserver['status']['os'],
    'os_builtdate'          => $testserver['status']['os_builtdate'],
    'numberofprocessors'    => $testserver['status']['numberofprocessors'],
    'processorarchetecture' => $testserver['status']['processorarchetecture'],
    'serveruptime'          => $testserver['status']['serveruptime'],
    'serveruse'             => $testserver['status']['serveruse'],
    'serveripadress'        => $testserver['status']['serveripadress'],
    ]
);

$GLOBALS['xoopsTpl']->assign('systeminfo', $systeminfo);
$GLOBALS['xoopsTpl']->assign('phpinfo', $phpinfo);
$templateMain = 'xoopssecure_admin_system.tpl';
$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/bootstrap.css'));
$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/admin/style.css'));
$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/xoops.css');
$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/system/themes/transition/css/style.css');

$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootstrap.bundle.js'));
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/scannerAdmin.js'));
require __DIR__ . '/footer.php';
