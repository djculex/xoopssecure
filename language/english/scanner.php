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
 * Xoops XoopsSecure module for xoops
 *
 * @copyright 2021 XOOPS Project (https://xoops.org)
 * @license   GPL 2.0 or later
 * @package   XoopsSecure
 * @since     1.0
 * @min_xoops 2.5.11
 * @author    Culex - Email:culex@culex.dk - Website:https://www.culex.dk
 * @ignore Language defines
 */

require_once __DIR__ . '/common.php';

// ---------------- Scanner --------------------
\define('_SCAN_XOOPSSECURE_TITLE', 'Scanner');
\define('_SCAN_XOOPSSECURE_TITLE_DESC', 'Scan for issues and if needed fix');
\define('_SCAN_XOOPSSECURE_TITLE_DESC_TIP', 'Tip:');
\define('_SCAN_XOOPSSECURE_TITLE_DESC_TIP_DESC', 'Check for on-the-fly fixing on problems. The fixes are safe as no files are modified or deleted.');
\define('_SCAN_XOOPSSECURE_CHECKBOX_CIF', 'Auto-create index files');
\define('_SCAN_XOOPSSECURE_CHECKBOX_MM', 'Auto check for malware');
\define('_SCAN_XOOPSSECURE_CHECKBOX_SET_PERM', 'Set recomended permissions on folders/files');
\define('_SCAN_XOOPSSECURE_BUTTON_START', 'Start!');

\define('_SCAN_XOOPSSECURE_PERM_TITLE', 'Checking File-system permissions');
\define('_SCAN_XOOPSSECURE_IF_TITLE', 'Checking for missing index files');
\define('_SCAN_XOOPSSECURE_MALLWARE_TITLE', 'Scanning files for possible mallware');

\define('_SCAN_XOOPSSECURE_FIRSTTIMESCAN_TITLE', 'First time scan');
\define('_SCAN_XOOPSSECURE_FIRSTTIMESCAN_DESC', 'There are no previous scans recorded in the database. The amount of files in first scans is enormous, and thus the time to complete a full scan is in some cases a matter of hours. If this is a healty install, and you\'re planning to keep track of new and modified files in the future. It would be wise to set today as a start point and in the future scan based on this date/time.');
\define('_SCAN_XOOPSSECURE_FIRSTTIMESCAN_HEALTHY', 'This install is healty');
\define('_SCAN_XOOPSSECURE_FIRSTTIMESCAN_NOHEALTHY', 'This install needs a full scan');

\define('_SCAN_XOOPSSECURE_MALLWARE_MODAL_GETTINGFILESWAIT_TITLE', 'Reading files on web site');
\define('_SCAN_XOOPSSECURE_MALLWARE_MODAL_GETTINGFILESWAIT', 'Getting files as json array ... please wait a minute');
\define('_SCAN_XOOPSSECURE_MALLWARE_SIZEOFJSON', 'Size of files to scan : ');
\define('_SCAN_XOOPSSECURE_MALLWARE_MODAL_OK', 'Ok');

\define('_SCAN_XOOPSSECURE_MALWARE_SUSPECIOUSFILENAME_TITLE', 'Suspecious file name found');
\define('_SCAN_XOOPSSECURE_MALWARE_SUSPECIOUSFILENAME', 'Suspecious file name found. <br>File.: %s<br><br><strong>Details:</strong><br><pre>%s</pre>');
\define('_SCAN_XOOPSSECURE_MALWARE_ARRAYNOCOM', 'In file %s we found <strong>%s</strong><br>');
\define('_SCAN_XOOPSSECURE_MALWARE_ARRAYCOM', 'Pattern %s - %s<br>Found in file {%s}<br><br>Detail.: %s');
\define('_SCAN_XOOPSSECURE_MALWARE_ARRAYCOMEXPL', '<br>Line number.: %d<br><pre>%s</pre>');


\define('_SCAN_XOOPSSECURE_MALLWARE_DROP_FULL', 'Full scan');
\define('_SCAN_XOOPSSECURE_MALLWARE_DROP_P', 'Only permission scan');
\define('_SCAN_XOOPSSECURE_MALLWARE_DROP_I', 'Only index file scan');
\define('_SCAN_XOOPSSECURE_MALLWARE_DROP_M', 'Only mallware scan');

define("_SCAN_XOOPSSECURE_MALLWARE_SHORTTITLE_FULL", "Full scan");
define("_SCAN_XOOPSSECURE_MALLWARE_SHORTTITLE_PERM", "Perm. scan");
define("_SCAN_XOOPSSECURE_MALLWARE_SHORTTITLE_INDX", "IndexF scan");
define("_SCAN_XOOPSSECURE_MALLWARE_SHORTTITLE_MALLW", "Mallw. scan");
define("_SCAN_XOOPSSECURE_MALLWARE_SHORTTITLE_CODES", "CS scan");

\define('_SCAN_XOOPSSECURE_CS_TITLE', 'Check Coding standards');
\define('_SCAN_XOOPSSECURE_MALLWARE_DROP_CS', 'Only coding standard scan');

\define('_SCAN_XOOPSSECURE_MISSINGINDEXFILE_PAGETITLE', 'Index Files');
\define('_SCAN_XOOPSSECURE_MISSINGINDEXFILE_TITLE', 'Missing index file');
\define('_SCAN_XOOPSSECURE_MISSINGINDEXFILE', 'Missing index file in folder : %s');
\define('_SCAN_XOOPSSECURE_MISSINGINDEXFILE_FIXED', 'Missing index file in folder : %s - index file has now been created by Xoopssecure.');
\define('_SCAN_XOOPSSECURE_WRONFILEPERMISSION_PAGETITLE', 'Xoops Files permissions.');
\define('_SCAN_XOOPSSECURE_WRONFILEPERMISSION_TITLE', 'Wrong file Permission.');
\define('_SCAN_XOOPSSECURE_WRONFILEPERMISSION', '%s has file permission : %s, recommended setting is %s');
\define('_SCAN_XOOPSSECURE_WRONFILEPERMISSION_FIXED', '%s had file permission : %s, recommended setting is %s. Xoopssecure has fixed permissions to.: %s');
\define('_SCAN_XOOPSSECURE_RIGHTFILEPERMISSION', '%s has file permission : %s as is recommended');

\define('_SCAN_XOOPSSECURE_INLINEISSUESFOUND', 'issues');
\define('_SCAN_XOOPSSECURE_INLINEDATESCANNED', 'Scandate');
\define('_SCAN_XOOPSSECURE_INLINESCANTYPE', 'Scan type');
\define('_SCAN_XOOPSSECURE_INLINELINKTEXT', 'Complete log');

// Bad strings
\define('_SCAN_XOOPSSECURE_BFN_01', 'Probably an OpenFlashChart library demo file that has known input validation error (CVE-2009-4140)');
\define('_SCAN_XOOPSSECURE_BFN_02', 'Probably an R57 shell');
\define('_SCAN_XOOPSSECURE_BFN_03', 'Probably a C99 shell');
\define('_SCAN_XOOPSSECURE_BFN_04', 'Probably a C100 shell');
\define('_SCAN_XOOPSSECURE_BFN_05', 'PhpInfo() file? It is advisable to remove such file, as it could reveal too much info to potential attackers');
\define('_SCAN_XOOPSSECURE_BFN_06', 'PerlInfo() file? It is advisable to remove such file, as it could reveal too much info to potential attackers');

// Bad patterns
\define('_SCAN_XOOPSSECURE_PAT01_TITLE', 'PHP: preg_replace Eval');
\define('_SCAN_XOOPSSECURE_PAT01_DESC', 'Detected preg_replace function that evaluates (executes) matched code. This means if PHP code is passed it will be executed.');

\define('_SCAN_XOOPSSECURE_PAT02_TITLE', 'Backdoor: PHP:C99');
\define('_SCAN_XOOPSSECURE_PAT02_DESC', 'Detected the "C99 Shell"? Backdoor that allows attackers to manage (and reinfect) your website remotely. It is often used as part of a compromise to maintain access to the hacked sites.');

\define('_SCAN_XOOPSSECURE_PAT03_TITLE', 'Backdoor: PHP:R57');
\define('_SCAN_XOOPSSECURE_PAT03_DESC', 'Detected the "R57 Shell"? Backdoor that allows attackers to access, modify and reinfect your site. It is often hidden in the filesystem and hard to find without access to the server or logs.');

\define('_SCAN_XOOPSSECURE_PAT04_TITLE', 'Backdoor: PHP:GENERIC');
\define('_SCAN_XOOPSSECURE_PAT04_DESC', 'Detected a generic backdoor that allows attackers to upload files, delete files, access, modify and/or reinfect your site. It is often hidden in the filesystem and hard to find without access to the server or logs. It also includes uploadify scripts and similars that offer upload options without security.');

\define('_SCAN_XOOPSSECURE_PAT05_TITLE', 'Backdoor: PHP:Filesman:02');
\define('_SCAN_XOOPSSECURE_PAT05_DESC', 'Detected the “Filesman Shell”? Backdoor that allows attackers to access, modify and reinfect your site. It is often hidden in the filesystem and hard to find without access to the server or logs.');

\define('_SCAN_XOOPSSECURE_PAT06_TITLE', 'PHP:\input include');
\define('_SCAN_XOOPSSECURE_PAT06_DESC', 'Detected the method of reading input through PHP protocol handler in include/require statements.');

\define('_SCAN_XOOPSSECURE_PAT07_TITLE', 'data:;base64 include');
\define('_SCAN_XOOPSSECURE_PAT07_DESC', 'Detected the method of executing base64 data in include.');

\define('_SCAN_XOOPSSECURE_PAT08_TITLE', '.HTACCESS RewriteCond-Referer');
\define('_SCAN_XOOPSSECURE_PAT08_DESC', 'Your .htaccess file has a conditional redirection based on "HTTP Referer". This means it redirects according to site/url from where your visitors came to your site. Such technique has been used for unwanted redirections after coming from Google or other search engines, so check this directive carefully.');

\define('_SCAN_XOOPSSECURE_PAT09_TITLE', 'Fake jQuery Malware');
\define('_SCAN_XOOPSSECURE_PAT09_DESC', 'This file is infected with the Fake jQuery Malware. Removing the malware is not enough. Make sure your CMS and all its third-party components are up-to-date. All unused stuff should be ruthlessly deleted from server. Some of the compromised websites had malicious WordPress admin users with names like: backup, dpr19, loginfelix. Some of them had been created during past attacks though.');

\define('_SCAN_XOOPSSECURE_PAT10_TITLE', 'PHP file desguised as GIF image');
\define('_SCAN_XOOPSSECURE_PAT10_DESC', 'Detected a PHP file that was most probably uploaded as an image via webform that loosely only checks file headers.');

\define('_SCAN_XOOPSSECURE_PAT11_TITLE', 'Probably malicious PHP script that "calls home"');
\define('_SCAN_XOOPSSECURE_PAT11_DESC', 'Detected script variations often used to inform the attackers about found vulnerable website.');

\define('_SCAN_XOOPSSECURE_PAT12_TITLE', 'Multiple encoded, most probably obfuscated code found');
\define('_SCAN_XOOPSSECURE_PAT12_DESC', 'This pattern could be used in highly encoded, malicious code hidden under a loop of code obfuscation function calls. In most cases the decoded hacker code goes through an eval call to execute it. This pattern is also often used for legitimate purposes, e.g. storing configuration information or serialised object data. ');

\define('_SCAN_XOOPSSECURE_PAT13_TITLE', 'IFRAME Element');
\define('_SCAN_XOOPSSECURE_PAT13_DESC', 'Found IFRAME element in code. It\'s mostly benevolent, but often used for bad stuff, so please check if it\'s a valid code.');

\define('_SCAN_XOOPSSECURE_PAT14_TITLE', 'Reversed string "assert"');
\define('_SCAN_XOOPSSECURE_PAT14_DESC', 'Assert function name is being hidden behind strrev().');

\define('_SCAN_XOOPSSECURE_PAT15_TITLE', 'Is the current DIR Writable?');
\define('_SCAN_XOOPSSECURE_PAT15_DESC', 'This could be harmless, but used in some malware');

\define('_SCAN_XOOPSSECURE_PAT16_TITLE', 'At least two characters in hexadecimal or octal notation');
\define('_SCAN_XOOPSSECURE_PAT16_DESC', 'Found at least two characters in hexadecimal or octal notation. It doesn\'t mean it is malicious, but it could be code hidding behind such notation.'); // Link for more description

\define('_SCAN_XOOPSSECURE_PAT17_TITLE', 'SourceCop encoded code');
\define('_SCAN_XOOPSSECURE_PAT17_DESC', 'Found the SourceCop encoded code. It is often used for malicious code hidding, so go and check the code with some online SourceCop decoders');

\define('_SCAN_XOOPSSECURE_PAT18_TITLE', 'Shell command execution from POST/GET variables');
\define('_SCAN_XOOPSSECURE_PAT18_DESC', 'Found direct shell command execution getting variables from POST/GET, which is highly dangerous security flaw or a part of malicious webrootkit');

\define('_SCAN_XOOPSSECURE_PAT19_TITLE', 'Opening socket to localhost');
\define('_SCAN_XOOPSSECURE_PAT19_DESC', 'Found code opening socket to localhost, it\'s worth investigating more');

\define('_SCAN_XOOPSSECURE_PAT20_TITLE', 'Opening socket to known SMTP ports, possible SPAM script');
\define('_SCAN_XOOPSSECURE_PAT20_DESC', 'Found opening socket to known SMTP ports, possible SPAM script');

\define('_SCAN_XOOPSSECURE_PAT21_TITLE', 'Reading streams or superglobal variables with fopen wrappers present');
\define('_SCAN_XOOPSSECURE_PAT21_DESC', 'Found functions reading data from streams/wrappers - please analyze the code');

\define('_SCAN_XOOPSSECURE_PAT22_TITLE', 'Callback function comming from REQUEST/POST/GET variable possible');
\define('_SCAN_XOOPSSECURE_PAT22_DESC', 'Found possible local execution enabling-script receiving data from POST or GET requests');

\define('_SCAN_XOOPSSECURE_PAT23_TITLE', 'Remote Include');
\define('_SCAN_XOOPSSECURE_PAT23_DESC', 'Include or require which includes a remote file. It should be malicious, and vulnerable as well.');

\define('_SCAN_XOOPSSECURE_PAT24_TITLE', 'Hex Encoded String');
\define('_SCAN_XOOPSSECURE_PAT24_DESC', 'Code which is hex encoded. It can be legit, but not a usual thing. Malicious users can hide their functions in hex encoded expressions.');

/* ------------ Error messages ------------------ */
\define('_SCAN_XOOPSSECURE_ERRORS_TITLE', 'Errors');
\define('_SCAN_XOOPSSECURE_ERROR_COULDNOTREADFILE_TITLE', 'Could not read file \'%s\'');
\define('_SCAN_XOOPSSECURE_ERROR_COULDNOTREADFILE_DESC', 
    'The file <strong>%s</strong> could not be read. ' . 
    '<br>Sometimes the server adds strange permissions after copying to folders.' . 
    '<br>Try setting permissions manually.' . 
    '<br><br>If this is not working deleting->new copy to folder sometimes help. In my experience this is often the case with empty index.php files,' .
    '<br>Or it could be your host has locked your files OR you have an .htaccess file denying access to folders/files.'
    );