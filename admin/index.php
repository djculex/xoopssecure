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

use XoopsModules\Xoopssecure\Common;
use XoopsModules\Xoopssecure\FileH;

require_once \dirname(__DIR__) . '/preloads/autoloader.php';
require __DIR__ . '/header.php';

// Template Index
$templateMain = 'xoopssecure_admin_index.tpl';

$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootstrap.bundle.js'));
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/scannerAdmin.js'));

// Render Index
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('index.php'));
$GLOBALS['xoopsTpl']->assign('index', $adminObject->displayIndex());


$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootbox.min.js'));
require __DIR__ . '/footer.php';
