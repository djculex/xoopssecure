<?php

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;


if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();

$i = 1;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]["link"] = 'admin/index.php';
$adminmenu[$i]["icon"] = $pathIcon32.'/home.png';

$i++;
$adminmenu[$i]["title"] = MI_XOOPSSECURE_MENU_SCAN;
$adminmenu[$i]["link"] = 'admin/check.php';
$adminmenu[$i]["icon"] = $pathIcon32.'/globe.png';

$i++;
$adminmenu[$i]["title"] = MI_XOOPSSECURE_MENU_SHOWLOG;
$adminmenu[$i]["link"] = 'admin/showlog.php';
$adminmenu[$i]["icon"] = $pathIcon32.'/globe.png';

$i++;
$adminmenu[$i]["title"] = MI_XOOPSSECURE_MENU_CONFIG;
$adminmenu[$i]["link"] = 'admin/config.php';
$adminmenu[$i]["icon"] = $pathIcon32.'/globe.png';

$i++;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"] = 'admin/about.php';
$adminmenu[$i]["icon"] = $pathIcon32.'/about.png';
