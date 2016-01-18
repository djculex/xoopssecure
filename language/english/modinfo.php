<?php
/**
 * ****************************************************************************
 * marquee - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Michael Albertsen (michael@culex.dk)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         xoopsSecure
 * @author 			Culex (http://culex.dk)
 *
 * Version : $Id:
 * ****************************************************************************
 */

// Defaults
define("_MI_XOOPSSECURE_NAME","XoopSecure");
define("_MI_XOOPSSECURE_DESC","Scan you files for malware and other security related issues");

define("_MI_XOOPSSECURE_GENERALCONFIG_TITLE", "<br><h4>General Configs</h4><br>");

define("_MI_XOOPSSECURE_DEVELOPERCONFIGS_TITLE", "<h4>Developer scan configs</h4>");

// Developer configs
define ("_MI_XOOPSSECURE_DEVELOPURLTOSCAN_TITLE", "Url to scan from");
define ("_MI_XOOPSSECURE_DEVELOPURLTOSCAN_DESC", "Enter relative url to file or parse a folder by entering dir ending with '/'. Base path is xoops install dir.<br>Best choice is to scan small folders or single files. <br><br>Too many files will cause the script to stall. In this case refresh page and do a file by file check!");

define ("_MI_XOOPSSECURE_CSSTYLE_TITLE", "Use this coding standard");
define ("_MI_XOOPSSECURE_CSSTYLE_DESC", "Choose the special config. There are various coding standards to choose from. All a little different. Standard is Xoops. All issues is not found but codesniffer profides a good starting point for keeping your code up to standard.");

define ("_MI_XOOPSSECURE_CFCHOOSE_PEAR", "PEAR");
define ("_MI_XOOPSSECURE_CFCHOOSE_XOOPS", "Xoops");
define ("_MI_XOOPSSECURE_CFCHOOSE_ZEND", "Zend");

// Full scan
define("_MI_XOOPSSECURE_FULLSCANCONFIGS_TITLE", "<br><h4>Full scan specifics</h4><br>");
define("_MI_XOOPSSECURE_FULLSCANFUNCTIONSEARCH_TITLE", "Include 'functions' in full scan");
define("_MI_XOOPSSECURE_FULLSCANFUNCTIONSEARCH_DESC", "These functions will be searched in 'full scan'. They could be misused but in most cases harmless.");

define("_MI_XOOPSSECURE_FULLSCANBADBOYSSEARCH_TITLE", "These 'badboy' strings to search for in full scan.");
define("_MI_XOOPSSECURE_FULLSCANBADBOYSSEARCH_DESC", "These strings / keywords 'could' be used in hacker signatures");

//Menu
define("MI_XOOPSSECURE_MENU_SCAN","Scan");
define("MI_XOOPSSECURE_MENU_SHOWLOG","Scan / system logs");
define("MI_XOOPSSECURE_MENU_CONFIG","Additional config");

//Configs
define("_MI_XOOPSSECURE_DATEFORMAT","Dateformat");
define("_MI_XOOPSSECURE_DATEFORMAT_DESC", "Enter dateformat you want used. Default (m-d-Y H:i:s)");

define("_MI_XOOPSSECURE_EXTENSIONS", "Extensions to search in quick scan");
define("_MI_XOOPSSECURE_EXTENSIONS_DESC", "Which extensions to scan. Seperate with | <br> Default is <span style='color:#444'>php|php3|php4|php5|phps|html|htm|htaccess|gif|js|css</span>");

define("_MI_XOOPSSECURE_URLTOSCAN", "Path to scan");
define ("_MI_XOOPSSECURE_URLTOSCAN_DESC", "This path is being scanned for security issues. Must start with XOOPS_ROOT_PATH (no '/' in end)");

define("_MI_XOOPSSECURE_AUTOINDEXFILES", "Create index files if missing ?");

define("_MI_XOOPSSECURE_AUTOINDEXFILES_DESC", "Choose 'yes' if you want scanner to automaticly create index files if missing in folders. <br> The proper way of disabeling directory listing in a web is to 'disable directory indexing' in .htaccess <br> but just to be sure (or if you dont have access to this feature) an html index file with a browser go-back script in folders <br> that do not have 'index.php or index.html etc' is sufficient and standard in Xoops.<br><br><h5>** WARNING **</h5> Choose this feature with caution.. Created index.html AND .htaccess files will have to be removed manually if you later want to undo **");

define("_MI_XOOPSSECURE_AUTOINDEXFILESNO_DESC", "Choose 'yes' if you want scanner to automaticly create index files if missing in folders. <br> A html index file with a browser go-back script will be created in all folders that do not have 'index.php or index.html etc'.<br><br><h5> [server messgage] </h5> -- It's seems your server does not allow .httaccess files so only option is html file.<br><br><h5>** WARNING **</h5> Choose this feature with caution.. Created index.html files will have to be removed manually if you later want to undo **");

define ("_MI_XOOPSSECURE_AUTOINDEXFILESSELECT","Type of index file");
define ("_MI_XOOPSSECURE_AUTOINDEXFILESSELECT_DESC","If your server permits you'll see 3 options. .httaccess, html and both. If your server doesn't permits only html version is available.");

define ('_MI_XOOPSSECURE_AUTOINDEXFILESSELECT_HTTACCESSFILE', '.htaccess');
define ('_MI_XOOPSSECURE_AUTOINDEXFILESSELECT_HTMLFILE', 'index.html');
define ('_MI_XOOPSSECURE_AUTOINDEXFILESSELECT_BOTH', 'Both');

define("_MI_XOOPSSECURE_CONTENTOFHTMLINDEX", "Content of your html index files");
define("_MI_XOOPSSECURE_CONTENTOFHTMLINDEX_DESC", "Type here the content you want in your index.html files. Default is a javascript go-back standard Xoops file.");

define("_MI_XOOPSSECURE_CONTENTOFHTACCESSINDEX", "Content of your .htaccess files.");
define("_MI_XOOPSSECURE_CONTENTOFHTACCESSINDEX_DESC", "Type here or uncomment the lines you want or not. Standard is setting to prevent direcotry listing same as the standard html file.");

define("_MI_XOOPSSECURE_AUTOFILEPERM","Auto set file permissions?");
define("_MI_XOOPSSECURE_AUTOFILEPERM_DESC", "If set to 'yes' the scan will set chmod 0644 to all files and chmod 0755 to all folders except (uploads/, cache/, templates_c/, mainfile.php, xoops_data/, xoops_data/configs/, xoops_data/caches/, xoops_data/caches/xoops_cache/, xoops_data/caches/smarty_cache/ and xoops_data/caches/smarty_compile/ & mainfile.php)<br><br><h5>** WARNING **</h5>Should you want to 'undo' or set different chmod to file or dir later, this will have to be done manually!!  **");

define("_MI_XOOPSSECURE_AUTOFILEPERMWIN_DESC","Since you're on a local or windows server. The chmod will have to be done manually. Go to folder view 'right click folder -> preferences -> security and mark 'read only permission'.");

define ("_MI_XOOPSSECURE_LOGPHPMYSQLISSUES", "Log php & mysql relevant settings");
define ("_MI_XOOPSSECURE_LOGPHPMYSQLISSUES_DESC", "Enabeling this will log also php/mysql settings relevant for your version. Ie security settings that are missing or actual bugs in your php version.");

define ("_MI_XOOPSSECURE_IGNORELANGFILE", "Ignore xoopsSecure's language files ? ");
define ("_MI_XOOPSSECURE_IGNORELANGFILE_DESC", "Pattern search in this file disabled. The search will return issues as this file contain translations of all available security issues.<br> Disabeling it will require a manual check. In fast scan it will be checked for changes in size / date though.");

define ("_MI_XOOPSSECURE_CRONSCANCONFIGS_TITLE", "<br><h4>Cron / direct link scan setup</h4><br>");
define ("_MI_XOOPSSECURE_CRONSCAN_INTERVAL_TITLE", "Enable feature every ?? hours");
define ("_MI_XOOPSSECURE_CRONSCAN_INTERVAL_DESC", "Set the amount of hours to pass before this script type is accessible");

define("_MI_XOOPSSECURE_CONFIG_ALWAYS", "Always");
define("_MI_XOOPSSECURE_CONFIG_HOURS", " hours");
define("_MI_XOOPSSECURE_CONFIG_DAY", " day");
define("_MI_XOOPSSECURE_CONFIG_DAYS", " days");
define("_MI_XOOPSSECURE_CONFIG_WEEKS", " weeks");
define("_MI_XOOPSSECURE_CONFIG_MONTH", " month");

// Backup settings

define("_MI_XOOPSSECURE_BACKUP_TITLE", "<br><h4>Backup settings</h4><br>");
define("_MI_XOOPSSECURE_BACKUPSELECTTYPE_TITLE", "Select type of backup");
define("_MI_XOOPSSECURE_BACKUPSELECTTYPE_DESC", "Type of backup to use. Both will include a complete MySql dump. <br>Minimum will include only the folders (/themes/, /uploads/, /xoops_data/, /xoops_lib/) and mainfile.php)");
define ("_MI_XOOPSSECURE_NONE", "None");
define ("_MI_XOOPSSECURE_MIN", "Minimum folders and files");
define("_MI_XOOPSSECURE_FULL", "Full");
define("_MI_XOOPSSECURE_CUSTOM", "Custom folders and files (see below for specification)");
define("_MI_XOOPSSECURE_BACKUPCUSTOMFILES_TITLE", "Backup custom files");
define("_MI_XOOPSSECURE_BACKUPCUSTOMFILES_DESC", "Specify the folders you would like to be backup using custom settings. Each path on a new line.");
