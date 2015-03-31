<?php
/**
 * Marquee module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright	The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license             http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package	Marquee
 * @since		2.5.0
 * @author 	Hervé Thouzard (http://www.herve-thouzard.com)
 * @version	$Id $
**/

include("../../../include/cp_header.php");

if ( !@include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/language/" . $xoopsConfig['language'] . "/main.php")) {
    include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/language/english/main.php");
}

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    include_once(XOOPS_ROOT_PATH."/class/template.php");
    $xoopsTpl = new XoopsTpl();
}

xoops_cp_header();

// Define Stylesheet and JScript
$xoTheme->addStylesheet( XOOPS_URL . "/modules/" . $xoopsModule->getVar("dirname") . "/assets/css/admin.css" );
//$xoTheme->addScript("browse.php?Frameworks/jquery/jquery.js");
//$xoTheme->addScript("browse.php?modules/" . $xoopsModule->getVar("dirname") . "/assets/js/admin.js");
;
