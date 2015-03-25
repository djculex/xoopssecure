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
 * @copyright       Hervé Thouzard (http://www.herve-thouzard.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         marquee
 * @author          Hervé Thouzard (http://www.herve-thouzard.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */

define ("_AM_XOOPSSECURE_DEFAULT_ROOTPATH", XOOPS_ROOT_PATH."/");
define ("_AM_XOOPSSECURE_CURRENTDIRSELECTED", "Current selected path is : ");
define ("_XOOPSSECURE_MENU_SCAN","Scan");

define ("_AM_XOOPSSECURE_QUICKSCAN_TITLE","Quick scan for changes");
define ("_AM_XOOPSSECURE_QUICKSCAN_DESC","Do a quick scan for changes to your installation. <br> This scan will check your files and find only the ones changed<br>since last full scan (ie. new files, moderated files etc.)");
define ("_AM_XOOPSSECURE_QUICKSCANSTARTBTN", "Press here to do a quick scan");

define ("_AM_XOOPSSECURE_DEVELOPERSCAN_TITLE","Scan installation for Coding / standards issues");
define ("_AM_XOOPSSECURE_DEVELOPERSCAN_DESC","Scan folder or file to check coding standards.<br>This test is not checking all standards but the general ones like (line lenght, fuction naming, placement of {} or deprecated versions.<br><br>This feature is using webCodeSniffer as a plugin.<br><br>** WARNING ** Scan folders with few files OR scan single files to not time-out the reading process.");
define ("_AM_XOOPSSECURE_DEVELOPERSCANSTARTBTN", "Press here to scan your code standards");
define ("_AM_XOOPSSECURE_DEVELOPERSCANCHOOSEPATH", "Click here to browse your server files or folders");
define ("_AM_XOOPSSECURE_DEVELOPERSCANRESULTTITLE", "Result for %s using %s standard.");

define ("_AM_XOOPSSECURE_FULLSCAN_TITLE","Full scan");
define ("_AM_XOOPSSECURE_FULLSCAN_DESC","!! Make sure your installtion is healthy before you use this.!! <br><br> This will do a complete snapshot of your installation and save it to the datase (dir date, size + file date, size and hash.)<br><br>This will enable the quick scan to be used and fast check if any files have been modified by unwanted guests.");
define ("_AM_XOOPSSECURE_FULLSCANSTARTBTN", "Start full scan");
define ("_AM_XOOPSSECURE_FULLSCANSTARTDEST", "Scan will recursively scan starting from : ");
define ("_AM_XOOPSSECURE_FULLSCANCUSTOMSCANCHANGEURL", "Change url for custom full scan");

define ("_AM_XOOPSSECURE_BACKUP_TITLE","Download backup of server files");
define ("_AM_XOOPSSECURE_BACKUP_DESC","Press this to get a current backup of server files including a current dump of database.<br><br>Be Aware!! this will take some time to finish (aprox. 2min on local server and more on live server for standard Xoops Installation.");
define ("_AM_XOOPSSECURE_BACKUPSTARTBTN", "Create new backup!");
define ("_AM_XOOPSSECURE_BACKUP_LINK_DESC", "Download from here");
define ("_AM_XOOPSSECURE_BACKUP_BACKUPREADY_TEXT", "You backup is ready. After download you'll be asked to delete from server. This is recommended");
define ("_AM_XOOPSSECURE_BACKUP_DELLINK", "Delete tmp backup from server");
define ("_AM_XOOPSSECURE_BACKUP_CONFIRM_DELETE", "When you have downloaded for security reasons delete the file from the server.");
define ("_AM_XOOPSSECURE_BACKUP_CONFIRM_DELETESURE", "You cannot recreate the backup, are you sure you downloaded last backup to pc ? ");
define ("_AM_XOOPSSECURE_BACKUP_CONFIRM_DELETE_YES", "Yes delete");
define ("_AM_XOOPSSECURE_BACKUP_CONFIRM_DELETE_NO", "No do NOT delete yet");
define ("_AM_XOOPSSECURE_SCAN_NOWPROCESSING", "Scanning : ");
define ("_AM_XOOPSSECURE_SCAN_SCANNINGMSG", "Getting fileinfo and if choosen creating backup... Please be patient. This could take a few minutes!");
define ("_AM_XOOPSSECURE_SCAN_SCANNINGMSGCHMOD", "Setting permissions to files and folders... Please be patient. This could take a few minutes!");
define ("_AM_XOOPSSECURE_SCAN_SCANNINGMSGIFC", "Creating index files where mission... Please be patient. This could take a few minutes!");
define ("_AM_XOOPSSECURE_SCAN_SCANNINGDEVELOP", "Creating scan result for your coding standard test... Please be patient. This could take a few minutes!");
define ("_AM_XOOPSSECURE_SCAN_READINGFILEFROMFILE", "File stack : ");
define ("_AM_XOOPSSECURE_SCAN_TIMEREMAININGSTAMP", "Remaining time < ");

define ("_AM_XOOPSSECURE_SCANFOUND", "found");
define ("_AM_XOOPSSECURE_SCANFILEERROR", "Could not check ");

define("_AM_XOOPSSECURE_LASTISSUEFOUND", "Last found issue : ");
define("_AM_XOOPSSECURE_SCANISSUECOUNTTOTAL", "Issues");
/* Titles */
define ("_AM_XOOPSSECURETITLEMALWARE", "Scanning for Malware");
define ("_AM_XOOPSSECURETITLEINDEXFILES", "Creating missing index files");
define ("_AM_XOOPSSECURETITLECHECKINDEXFILES", "Checking for folders missing index files and adding to log");
define ("_AM_XOOPSSECURETITLECHMOD", "Setting correct permissions on dirs and files");

/* Issue types */
define ("_AM_XOOPSSECURE_CHMOD", "Chmod file / dir");
define ("_AM_XOOPSSECURE_CHMOD_DESC", "Setting permissions on files and folders according to Xoops Standard security recommendations.");
define ("_AM_XOOPSSECURE_CHMODFILE", "File permissions set to 0644");
define ("_AM_XOOPSSECURE_CHMODSPECIALFILE", "Special file permissions set to 0444");
define ("_AM_XOOPSSECURE_CHMODDIR", "Dir permissions set to 0755");
define ("_AM_XOOPSSECURE_CHMODSPECIALDIR", "Special dir permissions set to 0777");

define("_AM_XOOPSSECURE_SCANHASHCHANGEDFILE", "File hash altered");
define ("_AM_XOOPSSECURE_SCANHASHCHANGEDFILE_DESC", "File unique md5 hash number is different from previous scan. This is indicating file content has changed since last scan. Most cases this is innocent but also best to check!");
define ("_AM_XOOPSSECURE_HASHCHEGEDVALUES", "Original hash is %s, Now it is %s. Old filesize %s, Now it is %s");

define("_AM_XOOPSSECURE_MISSINGANYINDEXFILE", "Missing html or .htaccess index file");
define("_AM_XOOPSSECURE_MISSINGANYINDEXFILE_DESC", "Theses files are nessesary to prevent directory listing. In other words your visitors will get access to files in folders without index files. index and .httaccess files preventing directory listening (only for linux servers and if Enabled .htaccess Server Overrides)");

define("_AM_XOOPSSECURE_MISSINGHTMLINDEXFILE", "Missing html index file");
define("_AM_XOOPSSECURE_MISSINGHTMLINDEXFILE_DESC", "Theses files are nessesary to prevent directory listing. In other words your visitors will get access to files in folders without index files. Other options are .httaccess files preventing directory listening (only for linux servers and if Enabled .htaccess Server Overrides)");

define("_AM_XOOPSSECURE_MISSINGHTTACCESS", "Missing .htaccess file");
define ("_AM_XOOPSSECURE_MISSINGHTTACCESS_DESC", "The .htaccess file is missing and since the configs has declared it should be there this script will create one with the specifications set in preferences.");

/* Templates */
define ("_AM_XOOPSSECURE_DELETEISSUE", "Delete this issue from the log");
define ("_AM_XOOPSSECURE_DELETEISSUE_DESC", "Delete this issue from the log. Any future scans will however refind this if still present in file content.");
define ("_AM_XOOPSSECURE_IGNOREISSUE", "Ad to ignore list");
define ("_AM_XOOPSSECURE_IGNOREISSUE_DESC", "This will ad to the ignore list so any future findings of this particular issue will be ignored");
define ("_AM_XOOPSSECURE_EMPTYIGNORELIST", "Empty ignore list");
define ("_AM_XOOPSSECURE_EMPTYIGNORELIST_DESC", "This will blank the ignore list of any saved issues.");

/* Config - ignore lists */
    // MENU ITEMS
define ("_AM_XOOPSSECURE_CONFIGMENU_AUTOMODE","Automation setup");
define ("_AM_XOOPSSECURE_CONFIGMENU_IGNOREITEMS","Ignore items");

define ("_AM_XOOPSSECURE_DRAGTOADD", "Drag file / dir to add to lists");
define ("_AM_XOOPSSECURE_DRAGTOIGNOREFILE_SCAN", "Scanner - Single file ignore list");
define ("_AM_XOOPSSECURE_DRAGTOIGNOREDIR_SCAN", "Scanner - Directory ignore list");
define ("_AM_XOOPSSECURE_DRAGTOIGNOREFILE_CHMOD", "These file will remain readable");
define ("_AM_XOOPSSECURE_DRAGTOIGNOREDIR_CHMOD", "These dirs will remain readable");
define ("_AM_XOOPSSECURE_ALREADYONLIST", "%s already on this list");
define ("_AM_XOOPSSECURE_DROPURLISSAMEASSTART", "%s is your start scan url.");
define ("_AM_XOOPSSECURE_DROPURLISPARTOFOTHER", "%s is subfolder to dir already added.");
define ("_AM_XOOPSSECURE_AUTOMATIONHEADER", "Currently this is the information needed for automating mallware scan");
define ("_AM_XOOPSSECURE_AUTOMATION_LINK_DESC", "Call this link in browser of cron to run a default scan for file-changes on your site.<br/>A report will be sent to admin.<br/>Setup is being done in preferences (allow it, when to do it, etc");
define ("_AM_XOOPSSECURE_AUTOMATION_LINK", "Url to call is : ");

/* PATTERNS */

define("_AM_XOOPSSECURE_SECISSUE01_TITLE", "PHP: preg_replace Eval");
define("_AM_XOOPSSECURE_SECISSUE01_DESC", "Detected preg_replace function that evaluates (executes) mathed code. This means if PHP code is passed it will be executed.<br>
    <a href = 'http://sucuri.net/malware/backdoor-phppreg_replaceeval'>Read more here</a>");
    
define("_AM_XOOPSSECURE_SECISSUE02_TITLE", "Backdoor: PHP:C99:045");  
define("_AM_XOOPSSECURE_SECISSUE02_DESC", "Detected the 'C99? backdoor' that allows attackers to manage (and reinfect) your site remotely. It is often used as part of a compromise to maintain access to the hacked sites.<br> <a href = 'http://sucuri.net/malware/backdoor-phpc99045'>Read more here</a>");

define("_AM_XOOPSSECURE_SECISSUE03_TITLE", "Backdoor: PHP:R57:01");
define("_AM_XOOPSSECURE_SECISSUE03_DESC", "Detected the 'R57? backdoor' that allows attackers to access, modify and reinfect your site. It is often hidden in the filesystem and hard to find without access to the server or logs. <br> <a href = 'http://sucuri.net/malware/backdoor-phpr5701'>Read more here</a>");

define("_AM_XOOPSSECURE_SECISSUE05_TITLE", "Backdoor: PHP:GENERIC:07");
define("_AM_XOOPSSECURE_SECISSUE05_DESC", "Detected a generic backdoor that allows attackers to upload files, delete files, access, modify and/or reinfect your site. It is often hidden in the filesystem and hard to find without access to the server or logs. It also includes uploadify scripts and similars that offer upload options without security. <a href = 'http://sucuri.net/malware/backdoor-phpgeneric07'> Read more here</a>");

define("_AM_XOOPSSECURE_SECISSUE07_TITLE","Backdoor: PHP:Filesman:02");
define("_AM_XOOPSSECURE_SECISSUE07_DESC", "Detected the 'Filesman' backdoor that allows attackers to access, modify and reinfect your site. It is often hidden in the filesystem and hard to find without access to the server or logs. <br><a href='http://sucuri.net/malware/backdoor-phpfilesman02'>Read more here</a>");

define("_AM_XOOPSSECURE_SECISSUE08_TITLE", "PHP:\input include");
define("_AM_XOOPSSECURE_SECISSUE08_DESC", "Detected the method of reading input through PHP protocol handler in include/require statements.");

define("_AM_XOOPSSECURE_SECISSUE09_TITLE", "data:;base64 include");
define("_AM_XOOPSSECURE_SECISSUE09_DESC", "Detected the method of executing base64 data in include.");

define("_AM_XOOPSSECURE_SECISSUE10_TITLE", ".HTACCESS RewriteCond-Referer");
define("_AM_XOOPSSECURE_SECISSUE10_DESC", "Your .htaccess file has a conditional redirection based on 'HTTP Referer'. This means it redirects according to site/url from where your visitors came to your site. Such technique has been used for unwanted redirections after coming from Google or other search engines, so check this directive carefully.");

define("_AM_XOOPSSECURE_SECISSUE11_TITLE", "Brute Force' words");
define("_AM_XOOPSSECURE_SECISSUE11_DESC", "Detected the 'Brute Force' words mentioned in code. <u>Sometimes it's a 'false positive'</u> because several developers like to mention it in they code, but it's worth double-checking if this file is untouche (eg. compare it with one in original extension package).");

define("_AM_XOOPSSECURE_SECISSUE15_TITLE", "PHP file desguised as GIF image");
define("_AM_XOOPSSECURE_SECISSUE15_DESC", "Detected a PHP file that was most probably uploaded as an image via webform that loosely only checks file headers.");

define("_AM_XOOPSSECURE_SECISSUE16_TITLE", "Probably malicious PHP script that 'calls home");
define("_AM_XOOPSSECURE_SECISSUE16_DESC", "Detected script variations often used to inform the attackers about found vulnerable website.");

define("_AM_XOOPSSECURE_SECISSUE17_TITLE", "PHP: multiple encoded, most probably obfuscated code found");
define("_AM_XOOPSSECURE_SECISSUE17_DESC", "This pattern could be used in highly encoded, malicious code hidden under a loop of code obfuscation function calls. In most cases the decoded hacker code goes through an eval call to execute it. This pattern is also often used for legitimate purposes, e.g. storing configuration information or serialised object data. Please inspect the file manually and compare it with the one in the original Xoops version or module to verify that this is not a false positive.");
    
define("_AM_XOOPSSECURE_SECISSUE18_TITLE", "IFRAME element");
define("_AM_XOOPSSECURE_SECISSUE18_DESC", "Found IFRAME element in code. It's mostly benevolent, but often used for bad stuff, so please check if it's a valid code.");

define("_AM_XOOPSSECURE_SECISSUE19_TITLE", "Reversed string 'assert'");
define("_AM_XOOPSSECURE_SECISSUE19_DESC", "Assert function name is being hidden behind strrev().");

define("_AM_XOOPSSECURE_SECISSUE20_TITLE", "Is the current DIR Writable?");
define("_AM_XOOPSSECURE_SECISSUE20_DESC", "This could be harmless, but used in some malware");

define("_AM_XOOPSSECURE_SECISSUE21_TITLE", "At least two characters in hexadecimal or octal notation");   
define("_AM_XOOPSSECURE_SECISSUE21_DESC", "Found at least two characters in hexadecimal or octal notation. It doesn\'t mean it is malicious, but it could be code hidding behind such notation.");

define("_AM_XOOPSSECURE_SECISSUE22_TITLE", "SourceCop encoded code");
define("_AM_XOOPSSECURE_SECISSUE22_DESC", "Found the SourceCop encoded code. It is often used for malicious code
hidding, so go and check the code with some online SourceCop decoders");

define("_AM_XOOPSSECURE_SECISSUE23_TITLE", "shell command execution from POST/GET variables");
define("_AM_XOOPSSECURE_SECISSUE23_DESC", "Found direct shell command execution getting variables from POST/GET,
which is highly dangerous security flaw or a part of malicious webrootkit");

define("_AM_XOOPSSECURE_SECISSUE24_TITLE", "PHP execution operator: backticks (``)");
define("_AM_XOOPSSECURE_SECISSUE24_DESC", "PHP execution operator found. Note that these are not single-quotes!
PHP will attempt to execute the contents of the backticks as a shell
command, which might indicate a part of a webrootkit");

define("_AM_XOOPSSECURE_SECISSUE25_TITLE", "Command Injection via system ();");
define("_AM_XOOPSSECURE_SECISSUE25_DESC", "The purpose of the command injection attack is to inject and execute commands specified by the attacker in the vulnerable application. In situation like this, the application, which executes unwanted system commands, is like a pseudo system shell, and the attacker may use it as any authorized system user. However, commands are executed with the same privileges and environment as the application has. Command injection attacks are possible in most cases because of lack of correct input data validation, which can be manipulated by the attacker (forms, cookies, HTTP headers etc.).");

define("_AM_XOOPSSECURE_SECISSUE26_TITLE", "MW:JS:INCLUDE:REBOTS");
define("_AM_XOOPSSECURE_SECISSUE26_DESC", " An malware javascript (maljs) include call was identified in the site. It is used to load malware from the 'rebots.php' file and attempt to infect anyone visiting the site. <br><br>This is a common malware (Jul/Aug/ 2012). Some variations include 'flashplayer.php' and a few other names.");

define("_AM_XOOPSSECURE_SECISSUE27_TITLE", "Shellbot: Backdoor / trojan");
define("_AM_XOOPSSECURE_SECISSUE27_DESC", "This allows hackers to get into and carry out dangerous actions in affected computers, such as capturing screenshots, stealing personal data, etc.");

/* SERVER SETTINGS */
define ("_AM_XOOPSSECURE_SETTING_ON", "On");
define ("_AM_XOOPSSECURE_SETTING_OFF", "OFF");
define ("_AM_XOOPSSECURE_SETTINGS_CUSTOMDIR", "c:\customdir");
define ("_AM_XOOPSSECURE_OVERALL_TITLE", "These settings are considered the most secure settings for php. These settings are found locally in the php.ini file");

define ("_AM_XOOPSSECURE_ALLOWURLFOPEN", "Remote files should not be accessable using fopen.");
define ("_AM_XOOPSSECURE_ALLOWURLINCLUDE", "You should not be able to include remote scripts using include.");
define ("_AM_XOOPSSECURE_DYNAMICDL", "Disable loading of dynamic extensions.");
define ("_AM_XOOPSSECURE_REGISTERGLOBALS", "For example, for the URL http://site.com/index.php?variable=value, the variable passes into your script with its value set to value when register_globals is 'On.' When register_globals is 'Off,' however, variables do not automatically pass into your script’s variable list. This makes it much more difficult for an attacker to inject code into your script.");
define ("_AM_XOOPSSECURE_OPENBASEDIR", "Restrict where PHP processes can read and write on a file system.");
define ("_AM_XOOPSSECURE_SAFEMODE","Disable safe mode.");
define ("_AM_XOOPSSECURE_MAXEXECUTIONTIME", "Limit script execution time.");
define ("_AM_XOOPSSECURE_UPLOADMAXFILESIZE", "The maximum upload file size should be less than or equal to the maximum post size.");
define ("_AM_XOOPSSECURE_MAXINPUTNESTINGLEVELS","Maximum level of nesting of objects 32 is sufficent.");
define ("_AM_XOOPSSECURE_DISPLAYERRORS","The display_errors directive determines whether error messages should be sent to the browser. These messages frequently contain sensitive information about your web application environment and should always be disabled.");
define ("_AM_XOOPSSECURE_LOGERRORS", "Always log errors");
define ("_AM_XOOPSSECURE_ERRORLOG", "Should be set to the url location of the php error log.");
define ("_AM_XOOPSSECURE_EXPOSEPHP", "Consider to turn this off, to not send the 'powered by X-X' header revealing you php version.");
define ("_AM_XOOPSSECURE_DISABLEFUNCTIONS", "show_source, system, shell_exec, passthru, exec, phpinfo, popen, proc_open");
define ("_AM_XOOPSSECURE_DISABLEFUNCTIONS_DESC", "Consider disabeling some or all of these functions, that you do not need in your scripts.");
define ("_AM_XOOPSSECURE_CVETITLE_ISSUES", "Show Issues from cvedetail.com (only english language available)");

define ("_AM_XOOPSSECURE_PHIINI_NAME", "Setting : ");
define ("_AM_XOOPSSECURE_PHIINI_CUR", "Current setting is : ");
define ("_AM_XOOPSSECURE_PHIINI_REC", "Recommended setting is : ");
define ("_AM_XOOPSSECURE_PHIINI_DESC", "About : ");

define ("_AM_XOOPSSECURE_SERVERSOFTWARE_PHP", "Your PHP Version: ");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_MYSQL", "Your MySql Version: ");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_APACHE", "Your Apache Version: ");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_XOOPS", "Your Xoops Version: ");

// Text showlog
define ("_AM_XOOPSSECURE_SHOWLOG_LATESTFULLSCAN", "Your latest full scan was %s ago");

define ("_AM_XOOPSSECURE_SHOWLOG_SHOWLOG_CLICK", "Press button");
define ("_AM_XOOPSSECURE_SHOWLOG_SHOWLOG_MALWARE_TITLE", "Mallware scan log");
define ("_AM_XOOPSSECURE_SHOWLOG_SHOWLOG_MALWARE_DESC", "This is the result of your latest scan(s). If you have done quick scans post a full scan posible issues with file hashe sizes etc will also be included in these results.");
define ("_AM_XOOPSSECURE_SHOWLOG_SHOWLOG_SOFTWARE_TITLE", "Software version log");
define ("_AM_XOOPSSECURE_SHOWLOG_SHOWLOG_SOFTWARE_DESC", "Shows posible problems / security issues with the software versions you're using in your server. Currently supporting Php, MySql, Apache and Xoops.");
define ("_AM_XOOPSSECURE_SHOWLOG_SHOWLOG_SERVER_TITLE", "Server security log");
define ("_AM_XOOPSSECURE_SHOWLOG_SHOWLOG_SERVER_DESC", "Displays possible holes and security blunders in your server configuration.");

define ("_AM_XOOPSSECURE_SERVERSOFTWARE_CVEID", "Cve-id : ");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_CWEID", "Cwe-id");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_SUMMARY", "Summary: ");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_CVSS_SCORE", "Severity score : ");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_EXPLOIT_COUNT", "Exploit counts : ");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_PUBDATE", "Publish date : ");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_UPDATEDATE", "Updated : ");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_URL", "Url : ");

/* LOG TITLES */
define ("_AM_XOOPSSECURE_PHPINI_SYSTEM_HEADER","Security issues : Php server settings");
define ("_AM_XOOPSSECURE_PHPINI_SYSTEM_DESC","These settings are found in ini.php if you're a local administrator. If you're on a hosted / shared server please ask your administrator to take a look at these.");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_HEADER", "Engine and system information");
define ("_AM_XOOPSSECURE_SERVERSOFTWARE_HEADER_DESC", "These are display only information. This will show the last 10 (if any) reported bugs about your Xoops version, Php version, Mysql version or apache version.");

define ("_AM_XOOPSSECURE_TITLE_MALWARE_FULL", "Latest full mallware-scan findings");
define ("_AM_XOOPSSECURE_TITLE_MALWARE_FULL_EMPTY", "No full scan has been done yet or database files has been cleared");
define ("_AM_XOOPSSECURE_TITLE_MALWARE_FULL_EMPTY_INSTRUCTIONS", "Please do a full scan to enable issue log and quick scan.");
define ("_AM_XOOPSSECURE_TITLE_MALWARE_FULL_CLEAR", "No issues found at this moment or issues has been cleared.");
define ("_AM_XOOPSSECURE_TITLE_MALWARE_FILEISSUES_CLEAR", "No issues found in files");
define ("_AM_XOOPSSECURE_TITLE_MALWARE_DIRISSUES_CLEAR", "No folder issues found");
define ("_AM_XOOPSSECURE_TITLE_MALWARE_QUICK", "Latest quick mallware-scan findings");
define ("_AM_XOOPSSECURE_QUICKSCAN_NOFILESINDB", "You need to complete a FULL SCAN before quick scan makes any sense. <br/><br/>After full scan the quick scan will be available");
define ("_AM_XOOPSSECURE_TITLE_MALWARE_INDEXFILES", "These folders have been found to be missing index files");
define ("_AM_XOOPSSECURE_SCANDATEBETWEEN", "Scan completed : ");


/* Code sniffer defines */
define ("_AM_XOOPSSECURE_CODESNIFFER_FILEWORD", "FILE: ");
define ("_AM_XOOPSSECURE_CODESNIFFER_FOUNDWORD", "FOUND ");
define ("_AM_XOOPSSECURE_CODESNIFFER_ERRORSWORD", " ERROR(S) ");

define ("_AM_XOOPSSECURE_CODESNIFFER_ANDWORD", "AND ");
define ("_AM_XOOPSSECURE_CODESNIFFER_WARNINGSWORD", " WARNING(S) ");
define ("_AM_XOOPSSECURE_CODESNIFFER_AFFECTINGWORD", "AFFECTING ");
define ("_AM_XOOPSSECURE_CODESNIFFER_LINESWORD", " LINE(S)");

define ("_AM_XOOPSSECURE_CODESNIFFER_DNFFIFAL", "Duplicate %s name '%s' found; first defined in %s on line %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_ESD", "Empty %s statement detected");
define ("_AM_XOOPSSECURE_CODESNIFFER_LII", "Line indented incorrectly; expected %s spaces, found %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_LEMC", "Line exceeds maximum limit of %s characters; contains %s characters");
define ("_AM_XOOPSSECURE_CODESNIFFER_LEC", "Line exceeds %s characters; contains %s characters");
define ("_AM_XOOPSSECURE_CODESNIFFER_LIIE","Line indented incorrectly; expected ");
define ("_AM_XOOPSSECURE_CODESNIFFER_AL", "at least ");
define ("_AM_XOOPSSECURE_CODESNIFFER_FOUNDSPACES", "%s spaces, found %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_TFLSTWL", "This FOR loop can be simplified to a WHILE loop");
define ("_AM_XOOPSSECURE_CODESNIFFER_AFCIFLTP", "Avoid function calls in a FOR loop test part");
define ("_AM_XOOPSSECURE_CODESNIFFER_LIJWIL", "Loop incrementor (%s) jumbling with inner loop");
define ("_AM_XOOPSSECURE_CODESNIFFER_AISTATF", "Avoid IF statements that are always true or false");
define ("_AM_XOOPSSECURE_CODESNIFFER_UFMIFC", "Unnecessary FINAL modifier in FINAL class");
define ("_AM_XOOPSSECURE_CODESNIFFER_TMPINU", "The method parameter %s is never used");
define ("_AM_XOOPSSECURE_CODESNIFFER_UMOD", "Useless method overriding detected");
define ("_AM_XOOPSSECURE_CODESNIFFER_CRTFT", "Comment refers to a FIXME task");
define ("_AM_XOOPSSECURE_CODESNIFFER_CRTTT", "Comment refers to a TODO task");
define ("_AM_XOOPSSECURE_CODESNIFFER_ICSNA", "Inline control structures are not allowed");
define ("_AM_XOOPSSECURE_CODESNIFFER_ICSAD", "Inline control structures are discouraged");
define ("_AM_XOOPSSECURE_CODESNIFFER_GJSLINTSSS", "gjslint says: (%s) %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_JSHINTS", "jshint says: ");
define ("_AM_XOOPSSECURE_CODESNIFFER_FCBOMCA", "File contains %s byte order mark, which may corrupt your application");
define ("_AM_XOOPSSECURE_CODESNIFFER_EOLCIEBF", "End of line character is invalid; expected '%s' but found '%s'");
define ("_AM_XOOPSSECURE_CODESNIFFER_EPHPSMBLBI", "Each PHP statement must be on a line by itself");
define ("_AM_XOOPSSECURE_CODESNIFFER_SPACE", " space");
define ("_AM_XOOPSSECURE_CODESNIFFER_SPACES", " spaces");
define ("_AM_XOOPSSECURE_CODESNIFFER_NLT", "a new line");
define ("_AM_XOOPSSECURE_CODESNIFFER_ESNACEF", "Equals sign not aligned correctly; expected %s but found %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_ESNAWSAEBF", "Equals sign not aligned with surrounding assignments; expected %s but found %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_ACSMNBFBAS", "A cast statement must not be followed by a space");
define ("_AM_XOOPSSECURE_CODESNIFFER_ACSMBFBASS", "A cast statement must be followed by a single space");
define ("_AM_XOOPSSECURE_CODESNIFFER_CTPBRCAP", "Call-time pass-by-reference calls are prohibited");
define ("_AM_XOOPSSECURE_CODESNIFFER_SFBC_FUNC", "Space found before comma in function call");
define ("_AM_XOOPSSECURE_CODESNIFFER_NSFAC_FUNC", "No space found after comma in function call");
define ("_AM_XOOPSSECURE_CODESNIFFER_ESACFC_FUNC", "Expected 1 space after comma in function call; %s found");
define ("_AM_XOOPSSECURE_CODESNIFFER_ESBEQ_FUNC", "Expected 1 space before = sign of default value");
define ("_AM_XOOPSSECURE_CODESNIFFER_ESAEQ_FUNC", "Expected 1 space after = sign of default value");
define ("_AM_XOOPSSECURE_CODESNIFFER_OBSBONL", "Opening brace should be on a new line");
define ("_AM_XOOPSSECURE_CODESNIFFER_OBSBONLAD", "Opening brace should be on the line after the declaration; found %s blank line(s)");
define ("_AM_XOOPSSECURE_CODESNIFFER_OBIIESFS", "Opening brace indented incorrectly; expected %s spaces, found %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_OBSBOSLAD", "Opening brace should be on the same line as the declaration");
define ("_AM_XOOPSSECURE_CODESNIFFER_ESACBF", "Expected 1 space after closing parenthesis; found %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_ESBOBBF", "Expected 1 space before opening brace; found %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_FCYCCEXAM", "Function's cyclomatic complexity (%s) exceeds allowed maximum of %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_FCYCCESCRF", "Function's cyclomatic complexity (%s) exceeds %s; consider refactoring the function");
define ("_AM_XOOPSSECURE_CODESNIFFER_FNESTLEXAMOS", "Function's nesting level (%s) exceeds allowed maximum of %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_FNESTLESCRFUNC", "Function's nesting level (%s) exceeds %s; consider refactoring the function");
define ("_AM_XOOPSSECURE_CODESNIFFER_METHNIIDOUBUND", "Method name '%s' is invalid; only PHP magic methods should be prefixed with a double underscore");
define ("_AM_XOOPSSECURE_CODESNIFFER_METHNAMENCCF", "%s method name '%s' is not in camel caps format");
define ("_AM_XOOPSSECURE_CODESNIFFER_METHNAMENICCF", "Method name '%s' is not in camel caps format");
define ("_AM_XOOPSSECURE_CODESNIFFER_FUNCNAMEIIDUND", "Function name '%s' is invalid; only PHP magic methods should be prefixed with a double underscore");
define ("_AM_XOOPSSECURE_CODESNIFFER_FUNCNAMENICCF", "Function name '%s' is not in camel caps format");
define ("_AM_XOOPSSECURE_CODESNIFFER_PHPFOURSCNALLOW", "PHP4 style constructors are not allowed; use '__construct()' instead");
define ("_AM_XOOPSSECURE_CODESNIFFER_PHPFOURPARCSTNA", "PHP4 style calls to parent constructors are not allowed; use 'parent::__construct()' instead");
define ("_AM_XOOPSSECURE_CODESNIFFER_CLASSCMBU", "Class constants must be uppercase; expected %s but found %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_CONSTMBU", "Constants must be uppercase; expected %s but found %s");
define ("_AM_XOOPSSECURE_CODESNIFFER_FUNCTISDEPRECATED", "Function %s() has been deprecated");
define ("_AM_XOOPSSECURE_CODESNIFFER_SHORTPHPUSED", "Short PHP opening tag used; expected '<?php' but found '%s'");
define ("_AM_XOOPSSECURE_CODESNIFFER_SHORTPHPOTWECHO", "Short PHP opening tag used with echo; expected '<?php echo %s ...' but found '%s %s ...'");
define ("_AM_XOOPSSECURE_CODESNIFFER_USEOFUNCIS", "The use of function %s() is ");
define ("_AM_XOOPSSECURE_CODESNIFFER_FORBIDDENWORD", "forbidden");
define ("_AM_XOOPSSECURE_CODESNIFFER_DISCOURAGEDWORD", "discouraged");

define ("_AM_XOOPSSECURE_CODESNIFFER_USESINSTEAD", "; use %s() instead");
define ("_AM_XOOPSSECURE_CODESNIFFER_TFNMBLESBFS", "TRUE, FALSE and NULL must be lowercase; expected '%s' but found '%s'");
define ("_AM_XOOPSSECURE_CODESNIFFER_SILENCINGEIF", "Silencing errors is forbidden");
define ("_AM_XOOPSSECURE_CODESNIFFER_SILENCINGEID", "Silencing errors is discouraged");
define ("_AM_XOOPSSECURE_CODESNIFFER_TFNMBUESBFS", "TRUE, FALSE and NULL must be uppercase; expected '%s' but found '%s'");
define ("_AM_XOOPSSECURE_CODESNIFFER_STRCONNRUSSI", "String concat is not required here; use a single string instead");
define ("_AM_XOOPSSECURE_CODESNIFFER_UNEXPSUBVPROP", "Unexpected Subversion property '%s' = '%s'");
define ("_AM_XOOPSSECURE_CODESNIFFER_MISSSUBVPROP", "Missing Subversion property '%s' = '%s'");
define ("_AM_XOOPSSECURE_CODESNIFFER_SUBVPROPDNM", "Subversion property '%s' = '%s' does not match '%s'");
define ("_AM_XOOPSSECURE_CODESNIFFER_ERROPFICNGSUBVPROP", "Error opening file; could not get Subversion properties");
define ("_AM_XOOPSSECURE_CODESNIFFER_SPACMBUTILTNA", "Spaces must be used to indent lines; tabs are not allowed");
define ("_AM_XOOPSSECURE_CODESNIFFER_FAILIZENDCODEAN", "Failed invoking ZendCodeAnalyzer, ");
define ("_AM_XOOPSSECURE_CODESNIFFER_FAILIZENDCODEANEXITCODE", "exitcode was ");//[$exitCode], 
define ("_AM_XOOPSSECURE_CODESNIFFER_FAILIZENDCODEANEXITCODERETVAL", " retval was ");//[$retval], 
define ("_AM_XOOPSSECURE_CODESNIFFER_FAILIZENDCODEANEXITCODEOUTPUT", " output was ");//[$msg]");
define ("_AM_XOOPSSECURE_CODESNIFFER_ACLOSTAGINPATENDFILE", "A closing tag is not permitted at the end of a PHP file");