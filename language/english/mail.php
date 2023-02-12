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
 * XoopsSecure modules
 *
 * @copyright module for xoops
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @since     1.0
 * @min_xoops 2.5.11
 * @author    Michael Albertsen
 * @ignore Language defines
 */

$moduleDirName = basename(dirname(__DIR__, 2));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

define("MAIL_XOOPSSECURE_MAIL_SENDERNAME", "XoopSecure");
define("MAIL_XOOPSSECURE_MAIL_FROM", "XoopSecure cron scan");
define("MAIL_XOOPSSECURE_FILESCHANGED", "These files have changed in the last %s hours");
