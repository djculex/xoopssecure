<?php

declare(strict_types=1);

namespace XoopsModules\Xoopssecure;

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
 * @package   xoopsSecure
 * @since     1.0
 * @min_xoops 2.5.11
 * @author    Culex - Email:culex@culex.dk - Website:https://www.culex.dk
 */

/**
 * Interface  Constants
 */
interface Constants
{
    // Constants for tables
    public const TABLE_ISSUES = 0;
    public const TABLE_STATS = 1;

    // Constants for status
    public const STATUS_NONE = 0;
    public const STATUS_OFFLINE = 1;
    public const STATUS_SUBMITTED = 2;
    public const STATUS_APPROVED = 3;
    public const STATUS_BROKEN = 4;
}
