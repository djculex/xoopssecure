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
 * log plugin for xoops module XoopsSecure
 *
 * @copyright module for xoops
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @since     1.0
 * @min_xoops 2.5.11
 * @author    Michael Albertsen - Website:<https://culex.dk>
 * @ignore Language defines
 */

$moduleDirName      = \basename(\dirname(__DIR__, 2));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

// ---------------- LOG file --------------------
\define('_LOG_XOOPSSECURE_CONFOMITFILE_TEXT', 'This will remove all issues regarding this file & ignore this file in future scans ?');
\define('_LOG_XOOPSSECURE_CONFOMITDIRS_TEXT', 'This will remove all issues regarding this DIR & ignore this dir in future scans ?');
\define('_LOG_XOOPSSECURE_CONFDELISSUE_TEXT', 'This will remove all issues regarding this file ?');

\define('_LOG_XOOPSSECURE_FULLFILEPATH', 'Full path : ');
\define('_LOG_XOOPSSECURE_FILEPERMISSIONS', 'File permissions : ');
\define('_LOG_XOOPSSECURE_LASTCHANGED', 'Last changed : ');
\define('_LOG_XOOPSSECURE_DELETEALLFORFILE', 'Delete all issues for this file');
\define('_LOG_XOOPSSECURE_IGNORDIR', 'Ignor parent dir in the future');
\define('_LOG_XOOPSSECURE_DELANDIGNORFILE', 'Delete & Ignore this file in future scans');
\define('_LOG_XOOPSSECURE_TOOLTIP_DELISSUE', 'Delete this issue. Not a concern.');
\define('_LOG_XOOPSSECURE_TOOLTIP_SHOWSOURCE', 'Show source code for issue.');

\define('_LOG_XOOPSSECURE_SCANTYPE_TITLE', 'Click to open issues about');
\define('_LOG_XOOPSSECURE_SCANTYPE_CURRENTSEL', '(current)');
\define('_LOG_XOOPSSECURE_SCANTYPE_INDEXFILES', 'Index files');
\define('_LOG_XOOPSSECURE_SCANTYPE_FILEPERMISSIONS', 'File Permissions');
\define('_LOG_XOOPSSECURE_SCANTYPE_MALLWARE', 'Mallware');
\define('_LOG_XOOPSSECURE_SCANTYPE_CODINGSTANDARDS', 'Coding standards');

\define('_LOG_XOOPSSECURE_ISSUE_DATE', 'Date');
\define('_LOG_XOOPSSECURE_ISSUE_TITLE', 'Title');
\define('_LOG_XOOPSSECURE_ISSUE_LINENUMBER', 'Line number');
\define('_LOG_XOOPSSECURE_ISSUE_DESC', 'Description');
\define('_LOG_XOOPSSECURE_ISSUE_ACTION', 'Action');
\define('_LOG_XOOPSSECURE_DROP_DEFAULT', 'Show log by date');
\define('_LOG_XOOPSSECURE_DELETETHISLOG', 'Delete this scan');
\define('_LOG_XOOPSSECURE_CONDELETELOG_TEXT', 'Delete this scan from database');

\define('_LOG_XOOPSSECURE_NOTHINGHERE_TITLE', 'No logged scans yet!');
\define('_LOG_XOOPSSECURE_NOTHINGHERE_DESC', 'Come back after you have completed a scan.');

\define('_LOG_XOOPSSECURE_SCANNER_RESULTCHECKSUCCESS', 'Perfect!');
\define('_LOG_XOOPSSECURE_SCANNER_RESULTCHECKSUCCESSH', 'Looks like everything is super.');
