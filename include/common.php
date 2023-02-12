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
 * @ignore path defines
 */

if (!defined('XOOPS_ICONS32_PATH')) {
    define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
}
if (!defined('XOOPS_ICONS32_URL')) {
    define('XOOPS_ICONS32_URL', XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
}
define('XOOPSSECURE_DIRNAME', 'xoopssecure');
define('XOOPSSECURE_PATH', XOOPS_ROOT_PATH . '/modules/' . XOOPSSECURE_DIRNAME);
define('XOOPSSECURE_URL', XOOPS_URL . '/modules/' . XOOPSSECURE_DIRNAME);
define('XOOPSSECURE_ICONS_PATH', XOOPSSECURE_PATH . '/assets/icons');
define('XOOPSSECURE_ICONS_URL', XOOPSSECURE_URL . '/assets/icons');
define('XOOPSSECURE_IMAGE_PATH', XOOPSSECURE_PATH . '/assets/images');
define('XOOPSSECURE_IMAGE_URL', XOOPSSECURE_URL . '/assets/images');
define('XOOPSSECURE_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . XOOPSSECURE_DIRNAME);
define('XOOPSSECURE_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . XOOPSSECURE_DIRNAME);
define('XOOPSSECURE_UPLOAD_FILES_PATH', XOOPSSECURE_UPLOAD_PATH . '/files');
define('XOOPSSECURE_UPLOAD_FILES_URL', XOOPSSECURE_UPLOAD_URL . '/files');
define('XOOPSSECURE_UPLOAD_IMAGE_PATH', XOOPSSECURE_UPLOAD_PATH . '/images');
define('XOOPSSECURE_UPLOAD_IMAGE_URL', XOOPSSECURE_UPLOAD_URL . '/images');
define('XOOPSSECURE_UPLOAD_SHOTS_PATH', XOOPSSECURE_UPLOAD_PATH . '/images/shots');
define('XOOPSSECURE_UPLOAD_SHOTS_URL', XOOPSSECURE_UPLOAD_URL . '/images/shots');
define('XOOPSSECURE_ADMIN', XOOPSSECURE_URL . '/admin/index.php');
$localLogo = XOOPSSECURE_IMAGE_URL . '/culex_logo.png';
// Module Information
$copyright = "<a href='https://www.culex.dk' title='Culex.dk' target='_blank'><img src='" . $localLogo . "' alt='Culex.dk' ></a>";
require_once XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
require_once XOOPSSECURE_PATH . '/include/functions.php';
