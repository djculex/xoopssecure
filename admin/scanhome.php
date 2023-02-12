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

require __DIR__ . '/header.php';
$moduleDirName = $GLOBALS['xoopsModule']->getVar('dirname');
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
xoops_loadLanguage('scanner', $moduleDirName);
xoops_loadLanguage('log', $moduleDirName);

// help file from admin
$GLOBALS['xoopsTpl']->assign(
    [
        'lang_scanner_title' => _SCAN_XOOPSSECURE_TITLE,
        'lang_scanner_title_desc' => _SCAN_XOOPSSECURE_TITLE_DESC,
        'lang_scanner_title_desc_tip' => _SCAN_XOOPSSECURE_TITLE_DESC_TIP,
        'lang_scanner_title_desc_tip_desc' => _SCAN_XOOPSSECURE_TITLE_DESC_TIP_DESC,
        'lang_scanner_checkbox_create_if' => _SCAN_XOOPSSECURE_CHECKBOX_CIF,
        'lang_scanner_checkbox_set_perm' => _SCAN_XOOPSSECURE_CHECKBOX_SET_PERM,
        'lang_scanner_button_start' => _SCAN_XOOPSSECURE_BUTTON_START,
        'lang_scanner_perm_title' => _SCAN_XOOPSSECURE_PERM_TITLE,
        'lang_scanner_if_title' => _SCAN_XOOPSSECURE_IF_TITLE,
        'lang_scanner_mallware_title' => _SCAN_XOOPSSECURE_MALLWARE_TITLE,
        'lang_scanner_mallware_dropdown_full' => _SCAN_XOOPSSECURE_MALLWARE_DROP_FULL,
        'lang_scanner_mallware_dropdown_p' => _SCAN_XOOPSSECURE_MALLWARE_DROP_P,
        'lang_scanner_mallware_dropdown_i' => _SCAN_XOOPSSECURE_MALLWARE_DROP_I,
        'lang_scanner_mallware_dropdown_m' => _SCAN_XOOPSSECURE_MALLWARE_DROP_M,
        'lang_scanner_cs_title' => _SCAN_XOOPSSECURE_CS_TITLE,
        'lang_scanner_mallware_dropdown_cs' => _SCAN_XOOPSSECURE_MALLWARE_DROP_CS,
        'lang_scanner_modal_firsttimescan' => _SCAN_XOOPSSECURE_FIRSTTIMESCAN_TITLE,
        'lang_scanner_modal_firsttimescan_desc' => _SCAN_XOOPSSECURE_FIRSTTIMESCAN_DESC,

        'lang_scaner_modal_firsttimescan_healhty' => _SCAN_XOOPSSECURE_FIRSTTIMESCAN_HEALTHY,
        'lang_scaner_modal_firsttimescan_nohealhty' => _SCAN_XOOPSSECURE_FIRSTTIMESCAN_NOHEALTHY,

        'lang_scanner_modal_ok' => _SCAN_XOOPSSECURE_MALLWARE_MODAL_OK,
        'lang_scanner_modal_gettingfileswait' => _SCAN_XOOPSSECURE_MALLWARE_MODAL_GETTINGFILESWAIT,
        'lang_scanner_modal_gettingfileswait_title' => _SCAN_XOOPSSECURE_MALLWARE_MODAL_GETTINGFILESWAIT_TITLE,
        'lang_scanner_modal_jsonstacksize' => _SCAN_XOOPSSECURE_MALLWARE_SIZEOFJSON,
    ]
);

$templateMain = 'xoopssecure_admin_scanner.tpl';
$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/bootstrap.css'));
$GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/admin/style.css'));
$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/xoops.css');
$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/system/themes/transition/css/style.css');

$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootstrap.bundle.js'));
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/bootbox.min.js'));
$GLOBALS['xoTheme']->addScript($helper->url('assets/js/scannerAdmin.js'));
require __DIR__ . '/footer.php';
