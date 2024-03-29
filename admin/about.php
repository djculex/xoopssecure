<?php

declare(strict_types=1);

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
use XoopsModules\Xoopssecure\Xoopssecure_Helper;

require __DIR__.'/header.php';
/*
 * Vars defined by inclusion of ./admin_header.php
 *
 * @var \XoopsModules\Xoopssecure\Admin $admin
 * @var \Xmf\Module\Admin $adminObject
 * @var \XoopsModules\Xoopssecure\Xoopssecure_Helper $helper
 * @var string $moduleDirName
 * @var string $moduleDirNameUpper
 */

$templateMain = 'xoopssecure_admin_about.tpl';
$helper       = Xoopssecure_Helper::getInstance();
$adminObject  = Admin::getInstance();
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootstrap.bundle.js'));
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootbox.min.js'));
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/scannerAdmin.js'));

$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('about.php'));
$adminObject::setPaypal('culex@culex.dk');
$GLOBALS['xoopsTpl']->assign('about', $adminObject->renderAbout(false));

$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/bootstrap.css'));
require __DIR__.'/footer.php';
