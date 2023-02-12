<?php

declare(strict_types=1);

use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Helper;
use XoopsModules\Xoopssecure\SpamScanner;
use Xmf\Request;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Xoops IScanner module for xoops
 *
 * @copyright 2021 XOOPS Project (https://xoops.org)
 * @license   GPL 2.0 or later
 * @package   Xoopssecure
 * @since     1.0
 * @min_xoops 2.5.11
 * @author    Culex - Email:culex@culex.dk - Website:https://www.culex.dk
 */

//

//$helper       = XoopsModules\Xoopssecure\Helper::getInstance();
require_once __DIR__ . '/include/functions.php';
if (!isset($GLOBALS['xoTheme']) || !$GLOBALS['xoTheme'] instanceof xos_opal_Theme) {
    include $GLOBALS['xoops']->path('class/theme.php');
    $GLOBALS['xoTheme'] = new xos_opal_Theme();
}

$GLOBALS['xoTheme']->addScript(
    '',
    ['type' => 'text/javascript'],
    "
                if (typeof jQuery == 'undefined') {
                    var tag = '<scr' + 'ipt type=\'text/javascript\' src=\'" .
    XOOPS_URL . "/browse.php?Frameworks/jquery/jquery.js\'></scr' + 'ipt>';            	    
                    document.write(tag);            	    
	            };"
);
$script = "var xoopsSecureSysUrl = '" . XOOPS_URL . "/modules/xoopssecure/admin/';" . "\n";
$GLOBALS['xoTheme']->addScript('', '', $script);

$GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/jquery.js');
$GLOBALS['xoTheme']->addScript(XOOPS_URL . "/modules/xoopssecure/assets/js/bootstrap.min.js");
$GLOBALS['xoTheme']->addScript(XOOPS_URL . "/modules/xoopssecure/assets/js/bootbox.min.js");
$GLOBALS['xoTheme']->addScript(XOOPS_URL . "/modules/xoopssecure/assets/js/typeahead.js");
$GLOBALS['xoTheme']->addScript(XOOPS_URL . "/modules/xoopssecure/assets/js/scannerAdmin.js");
$GLOBALS['xoTheme']->addStylesheet(
    '',
    ['type' => 'text/css'],
    "
	.typeahead {
        background-color: linen; 
        opacity: 0.8;
        border: 2px solid #FFF;
        border-radius: 4px;
        padding: 8px 12px;
        max-width: 550px;
        min-width: 450px; 
        font-size: 12px; 
        color: black;
        list-style: none;
        border: 1px solid gray;
    }
	.tt-menu { width:450px; }
	ul.typeahead{margin:0px;padding:10px 0px;}
	ul.typeahead.dropdown-menu li a {padding: 10px !important;	line-height: 200%;color:black;}
	ul.typeahead.dropdown-menu li:last-child a { border-bottom:0px !important; }
	.bgcolor {
        font-size:12px;
        max-width: 550px;
        min-width: 450px;
        max-height:340px;
        padding: 100px 10px 130px;
        border-radius:4px;
        text-align:center;
        margin:10px;
    }
	.demo-label {font-size:1.5em;color: #686868;font-weight: 500;color:#FFF;}
	.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
		text-decoration: none;
		background-color: yellow;
        line-height: 200%;
		outline: 0;
        opacity: 1.0;
	}"
);

// ------------------- Information ------------------- //
$modversion = [
    'name' => _MI_XOOPSSECURE_NAME,
    'version' => '1.3.4',
    'description' => _MI_XOOPSSECURE_DESC,
    'author' => 'Culex',
    'author_mail' => 'culex@culex.dk',
    'author_website_url' => 'https://www.culex.dk',
    'author_website_name' => 'Culex.dk',
    'credits' => 'Culex, Mamba',
    'license' => 'GPL 2.0 or later',
    'license_url' => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
    'help' => 'page=help',
    'release_info' => 'release_info',
    'release_file' => XOOPS_URL . '/modules/xoopssecure/docs/release_info file',
    'release_date' => '2015/03/25',
    'manual' => 'link to manual file',
    'manual_file' => XOOPS_URL . '/modules/xoopssecure/docs/install.txt',
    'min_php' => '8.0',
    'min_xoops' => '2.5.10',
    'min_admin' => '1.2',
    'min_db' => ['mysql' => '7.0', 'mysqli' => '7.0'],
    'image' => 'assets/images/xoopssecure_slogo.png',
    'dirname' => basename(__DIR__),
    'dirmoduleadmin' => 'Frameworks/moduleclasses/moduleadmin',
    'sysicons16' => '../../Frameworks/moduleclasses/icons/16',
    'sysicons32' => '../../Frameworks/moduleclasses/icons/32',
    'modicons16' => 'assets/icons/16',
    'modicons32' => 'assets/icons/32',
    'demo_site_url' => 'https://xoops.org',
    'demo_site_name' => 'XOOPS Demo Site',
    'support_url' => 'https://xoops.org/modules/newbb',
    'support_name' => 'Support Forum',
    'module_website_url' => 'www.xoops.org',
    'module_website_name' => 'XOOPS Project',
    'release' => '02/02/2023',
    'module_status' => 'RC 3',
    'system_menu' => 1,
    'hasAdmin' => 1,
    'hasMain' => 0,
    'adminindex' => 'admin/index.php',
    'adminmenu' => 'admin/menu.php',
    'onInstall' => 'include/install.php',
    'onUninstall' => 'include/uninstall.php',
    'onUpdate' => 'include/update.php',
];
// ------------------- Templates ------------------- //
$modversion['templates'] = [
    // Admin templates
    ['file' => 'xoopssecure_admin_about.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_admin_header.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_admin_index.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_admin_issues.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_admin_stats.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_admin_scanner.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_admin_log.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_admin_clone.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_admin_footer.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_admin_system.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_admin_download.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_download_backups.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_download_downloads.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_log_permissions.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_log_indexfiles.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_log_malware.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_log_codingstandards.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_log_nothinghere.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => 'xoopssecure_log_errors.tpl', 'description' => '', 'type' => 'admin'],

];
// ------------------- Mysql ------------------- //
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
// Tables
$modversion['tables'] = [
    'xoopssecure_issues',
    'xoopssecure_stats',
    'xoopssecure_log',
];
// ------------------- Config ------------------- //
// Keywords
$modversion['config'][] = [
    'name' => 'XCISSTARTPATH',
    'title' => '\_MI_XOOPSSECURE_XCISSTARTPATH',
    'description' => '\_MI_XOOPSSECURE_XCISSTARTPATH_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => XOOPS_ROOT_PATH,
];

$modversion['config'][] = [
    'name' => 'XCISDEVSTARTPATH',
    'title' => '\_MI_XOOPSSECURE_DEVXCISSTARTPATH',
    'description' => '\_MI_XOOPSSECURE_DEVXCISSTARTPATH_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => XOOPS_ROOT_PATH . "/modules/xoopssecure/test",
];

$modversion['config'][] = [
    'name' => 'XCISFILETYPES',
    'title' => '\_MI_XOOPSSECURE_SCISFILETYPES',
    'description' => '\_MI_XOOPSSECURE_SCISFILETYPES_DESC',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    /*cgi|css|eot|gif|gitignore|htaccess|htm|html|ico|inc|infected|js|json|lock|md|o|php|php3|php4|php5|php6|php7|pht|phtml|pl|py|sh|shtml|so|sql|susp|suspected|svg|swf|tpl|ttf|txt|vir|
woff|woff2|yml
*/
    'default' => "cgi|css|eot|gitignore|htaccess|htm|htmlinc|infected|js|json|lock|md|o|php|pht|phtml|pl|py|sh|shtml|so|sql|susp|suspected|swf|tpl|ttf|txt|vir|
woff|woff2|yml",
];

// Skip folders
$modversion['config'][] = [
    'name' => 'XCISOMITFOLDERS',
    'title' => '\_MI_XOOPSSECURE_SCISSKIPFOLDERS',
    'description' => '\_MI_XOOPSSECURE_SCISSKIPFOLDERS_DESC',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => "modules\\xoopssecure\\patterns\r\nmodules\\xoopssecure\\test\r\nmodules\\xoopssecure\\geshi",
];

//Skip files
$modversion['config'][] = [
    'name' => 'XCISOMITFILES',
    'title' => '\_MI_XOOPSSECURE_SCISSKIPFILES',
    'description' => '\_MI_XOOPSSECURE_SCISSKIPFILES',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => "modules\\xoopssecure\\class\\geshi.php\r\nmodules\\xoopssecure\\class\\Patterns.php",
];

// Cron scan yes or no ?
$modversion['config'][] = [
    'name' => 'XCISCRONTYPE',
    'title' => '_MI_XOOPSSECURE_CRONSELECTTYPE_TITLE',
    'description' => '_MI_XOOPSSECURE_CRONSELECTTYPE_DESC',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'XCISCRONINTERVAL',
    'title' => '_MI_XOOPSSECURE_CRONINTERVAL_TITLE',
    'description' => '_MI_XOOPSSECURE_CRONINTERVAL_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '24',
];

// BACKUP
$modversion['config'][] = [
    'name' => 'XCISBACKUPTYPE',
    'title' => '_MI_XOOPSSECURE_BACKUPSELECTTYPE_TITLE',
    'description' => '_MI_XOOPSSECURE_BACKUPSELECTTYPE_DESC',
    'formtype' => 'select',
    'valuetype' => 'array',
    'options' => [
        _MI_XOOPSSECURE_NONE => 'none',
        _MI_XOOPSSECURE_MIN => 'Minimum',
        _MI_XOOPSSECURE_FULL => 'Full',
        _MI_XOOPSSECURE_CUSTOM => 'Custom'
    ],
];

$obj = xoopssecure_backupFilesMin();
$modversion['config'][] = [
    'name' => 'XCISBACKUPCUSTOMFILES',
    'title' => '_MI_XOOPSSECURE_BACKUPCUSTOMFILES_TITLE',
    'description' => '_MI_XOOPSSECURE_BACKUPCUSTOMFILES_DESC',
    'formtype' => 'textarea',
    'valuetype' => 'string',
    'default' => implode("\n", $obj),
];

$modversion['config'][] = [
    'name' => 'XCISAUTOBACKUP',
    'title' => '_MI_XOOPSSECURE_AUTOBACKUP_TITLE',
    'description' => '_MI_XOOPSSECURE_AUTOBACKUP_DESC',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1,
];

$modversion['config'][] = [
    'name' => 'XCISAUTOBACKUPINTERVAL',
    'title' => '_MI_XOOPSSECURE_AUTOBACKUPINTERVAL_TITLE',
    'description' => '_MI_XOOPSSECURE_AUTOBACKUPINTERVAL_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '7',
];

$modversion['config'][] = [
    'name' => 'XCISAUTOBACKUPDELETE',
    'title' => '_MI_XOOPSSECURE_AUTOBACKUPDELETE_TITLE',
    'description' => '_MI_XOOPSSECURE_AUTOBACKUPDELETE_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '7',
];

// Check for updates
$modversion['config'][] = [
    'name' => 'XCISCHECKUPDATEDREPOS',
    'title' => '\_MI_XOOPSSECURE_CHECKUPDATEDREPOS_TITLE',
    'description' => '\_MI_XOOPSSECURE_CHECKUPDATEDREPOS_DESC',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => "https://api.github.com/repos/XOOPS/XoopsCore25/releases/latest\r\nhttps://api.github.com/repos/djculex/xoopsSecure/releases/latest",
];
