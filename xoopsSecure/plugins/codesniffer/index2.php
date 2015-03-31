<?php
/**
 * WebCodeSniffer 0.2
 *
 * PHP version 5
 *
 * @author Laurent Abbal <laurent@abbal.com>
 * @link   http://www.webcodesniffer.net
 */

 // Notifications
//require 'CodeSniffer/wcs_notification.php';
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/include/cp_header.php';
include_once dirname(dirname(dirname(__FILE__))) . '/admin/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';

    $_SERVER['argc'] = 3;
    $standard = '--standard=Xoops';
    $url = XOOPS_ROOT_PATH.'/modules/xoopsSecure/admin/';
    $_SERVER['argv'] = array("phpcs.php",$standard,$url);
    
    
    echo '<div class="report"><pre>';
    include XOOPS_ROOT_PATH. '/modules/xoopsSecure/plugins/codesniffer/CodeSniffer/phpcs.php';
    echo '</pre></div>';
