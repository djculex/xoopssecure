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
 * Xoops IScanner module for xoops
 *
 * @copyright 2021 XOOPS Project (https://xoops.org)
 * @license   GPL 2.0 or later
 * @package   xoopssecure
 * @since     1.0
 * @min_xoops 2.5.11
 * @author    Culex - Email:culex@culex.dk - Website:https://www.culex.dk
 * @ignore Language defines
 */

require_once __DIR__ . '/common.php';

// ---------------- Admin Main ----------------
\define('_MI_XOOPSSECURE_NAME', 'Xoopssecure');
\define('_MI_XOOPSSECURE_DESC', 'Scan files and folders for insecureties, Coding Standards and backup your sql and folders.');
// ---------------- Admin Menu ----------------
\define('_MI_XOOPSSECURE_ADMENU1', 'Home');
\define('_MI_XOOPSSECURE_ADMENU2', 'Scan for issues');
\define('_MI_XOOPSSECURE_ADMENU3', 'Server issues');
\define('_MI_XOOPSSECURE_ADMENU4', 'Backup etc.');
\define('_MI_XOOPSSECURE_ADMENU5', 'Feedback');
\define('_MI_XOOPSSECURE_ABOUT', 'About');
// Config

\define('_MI_XOOPSSECURE_XCISSTARTPATH', 'Path to scan');
\define('_MI_XOOPSSECURE_XCISSTARTPATH_DESC', 'The path start scan folders / subfolders. Default is root');

\define('_MI_XOOPSSECURE_DEVXCISSTARTPATH', 'Path for CF scan');
\define('_MI_XOOPSSECURE_DEVXCISSTARTPATH_DESC', 'Path where Xoopssecure will scan for coding standards. Default is <br> ' . XOOPS_ROOT_PATH . '/modules/xoopssecure/test . ');

\define('_MI_XOOPSSECURE_SCISFILETYPES', 'Files to scan');
\define('_MI_XOOPSSECURE_SCISFILETYPES_DESC', 'Regex separated by | of file types to include in the scan. Default is most common web file types.');

\define('_MI_XOOPSSECURE_SCISSKIPFOLDERS', 'Skip folders');
\define('_MI_XOOPSSECURE_SCISSKIPFOLDERS_DESC', 'Omit folders from malware scan. These folders will not be scanned. For instance Xoopssecure\'s own malware pattern folders. The Path is from root');

\define('_MI_XOOPSSECURE_SCISSKIPFILES', 'Skip Files');
\define('_MI_XOOPSSECURE_SCISSKIPFILES_DESC', 'Omit files from malware scan. These files will not be scanned. For instance Xoopssecure\'s own malware pattern files or Geshi\'s syntax highlighter files. The Path is from root');

\define('_MI_XOOPSSECURE_KEYWORDS', 'Keywords');
\define('_MI_XOOPSSECURE_KEYWORDS_DESC', 'Insert here the keywords (separate by comma)');
\define('_MI_XOOPSSECURE_NUMB_COL', 'Number Columns');
\define('_MI_XOOPSSECURE_NUMB_COL_DESC', 'Number Columns to View');
\define('_MI_XOOPSSECURE_DIVIDEBY', 'Divide By');
\define('_MI_XOOPSSECURE_DIVIDEBY_DESC', 'Divide by columns number');
\define('_MI_XOOPSSECURE_TABLE_TYPE', 'Table Type');
\define('_MI_XOOPSSECURE_TABLE_TYPE_DESC', 'Table Type is the bootstrap html table');
\define('_MI_XOOPSSECURE_PANEL_TYPE', 'Panel Type');
\define('_MI_XOOPSSECURE_PANEL_TYPE_DESC', 'Panel Type is the bootstrap html div');
\define('_MI_XOOPSSECURE_IDPAYPAL', 'Paypal ID');
\define('_MI_XOOPSSECURE_IDPAYPAL_DESC', 'Insert here your PayPal ID for donations');
\define('_MI_XOOPSSECURE_SHOW_BREADCRUMBS', 'Show breadcrumb navigation');
\define('_MI_XOOPSSECURE_SHOW_BREADCRUMBS_DESC', 'Show breadcrumb navigation which displays the current page in context within the site structure');
\define('_MI_XOOPSSECURE_ADVERTISE', 'Advertisement Code');
\define('_MI_XOOPSSECURE_ADVERTISE_DESC', 'Insert here the advertisement code');
\define('_MI_XOOPSSECURE_MAINTAINEDBY', 'Maintained By');
\define('_MI_XOOPSSECURE_MAINTAINEDBY_DESC', 'Allow url of support site or community');
\define('_MI_XOOPSSECURE_BOOKMARKS', 'Social Bookmarks');
\define('_MI_XOOPSSECURE_BOOKMARKS_DESC', 'Show Social Bookmarks in the single page');

// Backup settings

\define("_MI_XOOPSSECURE_BACKUP_TITLE", "<br><h4>Backup settings</h4><br>");
\define("_MI_XOOPSSECURE_BACKUPSELECTTYPE_TITLE", "Select type of backup");
\define("_MI_XOOPSSECURE_BACKUPSELECTTYPE_DESC", "Type of backup to use. Both will include a complete MySql dump. <br>Minimum will include only the folders (/themes/, /uploads/, /xoops_data/, /xoops_lib/) and mainfile.php)");
\define("_MI_XOOPSSECURE_NONE", "None");
\define("_MI_XOOPSSECURE_MIN", "Minimum folders and files");
\define("_MI_XOOPSSECURE_FULL", "Full");
\define("_MI_XOOPSSECURE_CUSTOM", "Custom folders and files (see below for specification)");
\define("_MI_XOOPSSECURE_BACKUPCUSTOMFILES_TITLE", "Backup custom files");
\define("_MI_XOOPSSECURE_BACKUPCUSTOMFILES_DESC", "Specify the folders you would like to be backup using custom settings. Each path on a new line.");
\define("_MI_XOOPSSECURE_AUTOBACKUP_TITLE", "Auto backup");
\define("_MI_XOOPSSECURE_AUTOBACKUP_DESC", "Do an auto-backup after each FULL scan (including auto scan if set)");
\define("_MI_XOOPSSECURE_AUTOBACKUPDELETE_TITLE", "Auto delete");
\define("_MI_XOOPSSECURE_AUTOBACKUPDELETE_DESC", "Automatically delete backup files after these days to prevent using too much disc space.");
\define("_MI_XOOPSSECURE_AUTOBACKUPINTERVAL_TITLE", "Auto backup interval");
\define("_MI_XOOPSSECURE_AUTOBACKUPINTERVAL_DESC", "Do not do auto backup if last backup is before this interval (in days)");

// Check backup files
\define("_MI_XOOPSSECURE_CHECKUPDATEDREPOS_TITLE", "Check these repos on github");
\define("_MI_XOOPSSECURE_CHECKUPDATEDREPOS_DESC", "Checks without oAuth2 is limited to 60 per hour. Delimit as default with enter.");

// Cron scan
\define("_MI_XOOPSSECURE_CRONSELECTTYPE_TITLE", "Run auto scan");
\define("_MI_XOOPSSECURE_CRONSELECTTYPE_DESC", "When logged in as Admin run a scan for changed file and send to admin email the result");
\define("_MI_XOOPSSECURE_CRONINTERVAL_TITLE", "When to do cron scan");
\define("_MI_XOOPSSECURE_CRONINTERVAL_DESC", "If auto scan is set to 'yes' run this scan after this interval (hours) default is 24");
// ---------------- End ----------------
