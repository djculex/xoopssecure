<?php
declare(strict_types=1);
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

use XoopsModules\Xoopssecure;

/**
 * Prepares system prior to attempting to uninstall module
 *
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to uninstall, false if not
 */
function xoops_module_pre_uninstall_xoopssecure(\XoopsModule $module)
{
    // Do some synchronization
    return true;
}

/**
 * Performs tasks required during uninstallation of the module
 *
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if uninstallation successful, false if not
 */
function xoops_module_uninstall_xoopssecure(\XoopsModule $module)
{
    $moduleDirName      = \basename(\dirname(__DIR__));
    $moduleDirNameUpper = \mb_strtoupper($moduleDirName);

    $helper = Xoopssecure\Helper::getInstance();

    $success = true;
    $helper->loadLanguage('admin');

    //------------------------------------------------------------------
    // Rename uploads folder to BAK and add date to name
    //------------------------------------------------------------------
    $uploadDirectory = $GLOBALS['xoops']->path("uploads/$moduleDirName");
    $dirInfo = new \SplFileInfo($uploadDirectory);
    if ($dirInfo->isDir()) {
        // The directory exists so rename it
        $date = date('Y-m-d');
        if (!rename($uploadDirectory, $uploadDirectory . "_bak_$date")) {
            $module->setErrors(sprintf(constant('CO_' . $moduleDirNameUpper . '_ERROR_BAD_DEL_PATH'), $uploadDirectory));
            $success = false;
        }
    }
    unset($dirInfo);
    /*
    //------------ START ----------------
    //------------------------------------------------------------------
    // Remove xsitemap.xml from XOOPS root folder if it exists
    //------------------------------------------------------------------
    $xmlfile = $GLOBALS['xoops']->path('xsitemap.xml');
    if (is_file($xmlfile)) {
        if (false === ($delOk = \unlink($xmlfile))) {
            $module->setErrors(\sprintf(\_AM_XOOPSSECURE_ERROR_BAD_REMOVE, $xmlfile));
        }
    }
    //    return $success && $delOk; // use this if you're using this routine
    */

    return $success;
    //------------ END  ----------------
}
