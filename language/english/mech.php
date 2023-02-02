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

/* SERVER SETTINGS */
\define("_MECH_XOOPSSECURE_SETTING_ON", "On");
\define("_MECH_XOOPSSECURE_SETTING_OFF", "OFF");
\define("_MECH_XOOPSSECURE_SETTINGS_CUSTOMDIR", "c:\customdir");
\define("_MECH_XOOPSSECURE_OVERALL_TITLE", "These settings are considered the most secure settings for php. These settings are found locally in the php.ini file");

\define("_MECH_XOOPSSECURE_ALLOWURLFOPEN", "Do not allow fopen wrappers to open remote URLs. Remote content cannot always be trusted; disabling these options ensures that fopen wrappers can load only local content.");
\define("_MECH_XOOPSSECURE_ALLOWURLINCLUDE", "You should not be able to include remote scripts using include.");
\define("_MECH_XOOPSSECURE_DYNAMICDL", "Disable loading of dynamic extensions.");
\define("_MECH_XOOPSSECURE_REGISTERGLOBALS", "For example, for the URL http://site.com/index.php?variable=value, the variable passes into your script with its value set to value when register_globals is 'On.' When register_globals is 'Off,' however, variables do not automatically pass into your script’s variable list. This makes it much more difficult for an attacker to inject code into your script.");
\define("_MECH_XOOPSSECURE_OPENBASEDIR", "Restrict where PHP processes can read and write on a file system.");
\define("_MECH_XOOPSSECURE_SAFEMODE", "Disable safe mode.");
\define(
    "_MECH_XOOPSSECURE_MAXEXECUTIONTIME",
    "Limit the maximum amount of time allowed to process inputs, as well as the maximum amount of time that a PHP script can run. " .
            "Here, both settings are set to a 30 second limit. This ensures that, in case a script became compromised, it would not read inputs or run for an extended period of time. " .
            "A well-coded script should not require more than 30 seconds to run."
);
\define(
    "_MECH_XOOPSSECURE_MAXINPUTTIME",
    "Maximum amount of time each script may spend parsing request data. " .
    "It's a good idea to limit this time on productions servers in order to eliminate unexpectedly long running scripts."
);
\define(
    "_MECH_XOOPSSECURE_MAXMEMORYLIMIT", 
    "To prevent poorly written scripts from consuming all of the available memory, " . 
    "this directive can be used to indicate a maximum amount of memory consumed by a script."
);
\define(
    "_MECH_XOOPSSECURE_POSTMAXSIZE", 
    "The maximum amount of data that can be sent to the server via a POST request.<br><br>" . 
        "This value MUST BE bigger than upload_max_filesize, since the same request " . 
        "will contain some more information (the title of the document, an operation code…).<br><br>" . 
        "So it's better to put a bigger value here. For example, if upload_max_filesize is 7M, then put 8M for post_max_size."
);
\define("_MECH_XOOPSSECURE_UPLOADMAXFILESIZE", "The maximum upload file size should be less than or equal to the maximum post size.");
\define("_MECH_XOOPSSECURE_MAXINPUTNESTINGLEVELS", "Maximum level of nesting of objects 32 is sufficent.");
\define("_MECH_XOOPSSECURE_DISPLAYERRORS", "The display_errors directive determines whether error messages should be sent to the browser. These messages frequently contain sensitive information about your web application environment and should always be disabled.");
\define("_MECH_XOOPSSECURE_LOGERRORS", "Always log errors");
\define("_MECH_XOOPSSECURE_ERRORLOG", "Should be set to the url location of the php error log.");
\define("_MECH_XOOPSSECURE_EXPOSEPHP", "Consider to turn this off, to not send the 'powered by X-X' header revealing you php version.");
\define("_MECH_XOOPSSECURE_DISABLEFUNCTIONS", "show_source, system, shell_exec, passthru, exec, phpinfo, popen, proc_open");
\define("_MECH_XOOPSSECURE_DISABLEFUNCTIONS_DESC", "Consider disabeling some or all of these functions, that you do not need in your scripts.");
\define("_MECH_XOOPSSECURE_CVETITLE_ISSUES", "Show Issues from cvedetail.com (only english language available)");

\define("_MECH_XOOPSSECURE_SERVER_STATUS", "STATUS");
\define("_MECH_XOOPSSECURE_SERVER_NAME", "Server navn : ");
\define("_MECH_XOOPSSECURE_SERVER_OS", "Server os : ");
\define("_MECH_XOOPSSECURE_SERVER_BUILTDATE", "OS built date : ");
\define("_MECH_XOOPSSECURE_SERVER_PROCARCH", "Processor arch. : ");
\define("_MECH_XOOPSSECURE_SERVER_NUMOFPROCES", "Number of processors : ");
\define("_MECH_XOOPSSECURE_SERVER_RUNNINGTIME", "Running time : ");
\define("_MECH_XOOPSSECURE_SERVER_SERVERUSE", "Server use : ");
\define("_MECH_XOOPSSECURE_SERVER_SERVERIP", "Server ip : ");

\define("_MECH_XOOPSSECURE_CLICK", "Click");
\define("_MECH_XOOPSSECURE_CVEISSUES", "Cve issues");

\define("_MECH_XOOPSSECURE_ISSUEISSUE", "Issue");
\define("_MECH_XOOPSSECURE_ISSUEYOURSETTING", "Your setting");
\define("_MECH_XOOPSSECURE_ISSUERECOMSET", "Recommended setting");
\define("_MECH_XOOPSSECURE_ISSUEMESSAGETYPE", "Message type");

\define("_MECH_XOOPSSECURE_PHIINI_NAME", "Setting : ");
\define("_MECH_XOOPSSECURE_PHIINI_CUR", "Current setting is : ");
\define("_MECH_XOOPSSECURE_PHIINI_REC", "Recommended setting is : ");
\define("_MECH_XOOPSSECURE_PHIINI_DESC", "About : ");

\define("_MECH_XOOPSSECURE_SOFTWARE_VERSION", "Version : ");
\define("_MECH_XOOPSSECURE_SOFTWARE_VERSIONRELEASE", "Version date : ");

\define("_MECH_XOOPSSECURE_PHIINI_WARNING", "Warning!");
\define("_MECH_XOOPSSECURE_PHIINI_NOTICE", "NOTICE");

\define("_MECH_XOOPSSECURE_SERVERSOFTWARE_PHP", "Your PHP Version: ");
\define("_MECH_XOOPSSECURE_SERVERSOFTWARE_MYSQL", "Your MySql Version: ");
\define("_MECH_XOOPSSECURE_SERVERSOFTWARE_APACHE", "Your Apache Version: ");
\define("_MECH_XOOPSSECURE_SERVERSOFTWARE_XOOPS", "Your Xoops Version: ");
