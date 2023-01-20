<?php

declare(strict_types=1);

use XoopsModules\Xoopssecure\FileH;

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

if (isset($templateMain)) {
    $fh = new fileH();
    $GLOBALS['xoopsTpl']->assign('buymecoffey', $fh::buymecoffey());
    $GLOBALS['xoopsTpl']->assign('maintainedby', $helper->getConfig('maintainedby'));
    $GLOBALS['xoopsTpl']->display("db:{$templateMain}");
}
xoops_cp_footer();
