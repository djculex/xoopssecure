<?php
namespace XoopsModules\Xoopssecure;

use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use XoopsModules\Xoopssecure\Db;

@set_time_limit(999999);

/**
 * Mechanics class
 *
 * Tools for testing various server or software settings
 *
 * @author    Michael Albertsen <culex@culex.dk>
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class Mech
{
    public $helper;
    public array $versions;

    // Constructor
    public function __construct()
    {
        $this->helper = Helper::getInstance();
    }

    /** 
     * remove html from phpinfo keeping only array
     *
     * @return array $info_arr
     */
    public function phpInfoArray()
    {
        ob_start();
        phpinfo();
        $info_arr = array();
        $info_lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));
        $cat = "General";
        foreach ($info_lines as $line) {
            // new cat?
            preg_match("~<h2>(.*)</h2>~", $line, $title) ? $cat = $title[1] : null;
            if (preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
                $info_arr[$cat][trim($val[1])] = trim($val[2]);
            } elseif (preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
                $info_arr[$cat][trim($val[1])] = array("local" => trim($val[2]), "master" => trim($val[3]));
            }
        }
        return $info_arr;
    }

    /** 
     * Convert MySQL uptime value into string (x days, x hours, etc.)
     * 
     * @param  int $uptime The uptime value.
     * @return string $uptime_string The uptime string.
     */
    public function mysqlUptimeToString($seconds)
    {
        $uptime_seconds = $seconds % 60;
        $uptime_minutes = (int) (($seconds % 3600) / 60);
        $uptime_hours = (int) (($seconds % 86400) / 3600);
        $uptime_days = (int) ($seconds / 86400);

        if ($uptime_days > 0) {
            $uptime_string = sprintf(_DAYS, $uptime_days) . ", " . sprintf(_HOURS, $uptime_hours) . ", " . sprintf(_MINUTES, $uptime_minutes) . ", " . sprintf(_SECONDS, $uptime_seconds);
        } elseif ($uptime_hours > 0) {
            $uptime_string = sprintf(_HOURS, $uptime_hours) . ", " . sprintf(_MINUTES, $uptime_minutes) . ", " . sprintf(_SECONDS, $uptime_seconds);
        } elseif ($uptime_minutes > 0) {
            $uptime_string = sprintf(_MINUTES, $uptime_minutes) . ", " . sprintf(_SECONDS, $uptime_seconds);
        } else {
            $uptime_string = sprintf(_SECONDS, $uptime_seconds);
        }
        return $uptime_string;
    }

    /** 
     * Testing various php settings on server.
     *
     * @return array $resp with values on vulnerable php ini settings
     */
    public function testServer()
    {
        $init = $this->phpInfoArray();
        $dat = new db();
        $resp = array();
        // Software installed
        $phpversion = $init['Core']['PHP Version'];
        $mysqlversion_temp = explode("mysqlnd ", $init['mysqli']['Client API library version']);
        $mysqlversion = preg_replace('/[^0-9.]+/', '', $mysqlversion_temp[1]);

        if (preg_match('|Apache\/(\d+)\.(\d+)\.(\d+)|', $_SERVER['SERVER_SOFTWARE'], $version)) {
            $apacheversionnum =  $version[1] . '.' . $version[2] . '.' . $version[3];
        }

        // BASIC SERVER SETTINGS / NAMES
        $resp['status']['pcname'] = (isset($init["Environment"]["COMPUTERNAME"])) ? $init["Environment"]["COMPUTERNAME"] : $_SERVER['SERVER_NAME'];
        $resp['status']['os'] = self::getOS();
        $resp['status']['os_builtdate'] = (isset($init["General"]["Build Date"])) ? date('d-m-Y', strtotime($init["General"]["Build Date"])) : "??-??-????";
        $resp['status']['numberofprocessors'] = (isset($init["Environment"]["NUMBER_OF_PROCESSORS"])) ? $init["Environment"]["NUMBER_OF_PROCESSORS"] : "?";
        $resp['status']['processorarchetecture'] = (isset($init["Environment"]["PROCESSOR_ARCHITECTURE"])) ? $init["Environment"]["PROCESSOR_ARCHITECTURE"] : "?";
        $resp['status']['serveruptime'] = self::mysqlUptimeToString($dat->serverUptime());
        $resp['status']['serveruse'] = (self::isLocalhost($whitelist = ['127.0.0.1', '::1']) === true ) ? 'localhost' : 'live';
        $resp['status']['serveripadress'] = ($resp['status']['serveruse'] == 'localhost') ? $init["Apache Environment"]["SERVER_ADDR"] : $_SERVER['REMOTE_ADDR'];


        // core
        $allowurlfopen = $init['Core']['allow_url_fopen']['local']; // off
        $allowurlinclude = $init['Core']['allow_url_include']['local']; // off
        $registerglobals = ($phpversion < '5.3.0') ? $init['Core']['register_globals']['local'] : 'Off'; // off
        $openbasedir = $init['Core']['open_basedir']['local']; // "c:\inetpub\"
        $safemode = ($phpversion < '5.3.0') ? $init['Core']['safe_mode']['local'] : 'Off'; // off
        $safemodegid = ($phpversion < '5.3.0') ? $init['Core']['safe_mode_gid']['local'] : 'Off'; // off
        $maxexecutiontime = $init['Core']['max_execution_time']['local']; // < 30
        $maxinputtime = $init['Core']['max_input_time']['local']; // < 60
        $memorylimit = self::convertToBytes($init['Core']['memory_limit']['local']);// < 16M
        $uploadmaxfilesize = $init['Core']['upload_max_filesize']['local'];// <2M
        $postmaxsize = $init['Core']['post_max_size']['local']; // < 8M
        $maxinputnestinglevels = $init['Core']['max_input_nesting_level']['local'];// <32
        $displayerrors = $init['Core']['display_errors']['local']; // Off
        $logerrors = $init['Core']['log_errors']['local']; // On
        $errorlog = $init['Core']['error_log']['local']; // "C:\path\of\your\choice"
        $exposephp = $init['Core']['expose_php']['local']; //Off
        $enabledl = $init['Core']['enable_dl']['local']; //Off
        $disablefunc = $init['Core']['disable_functions']['local']; //show_source, system, shell_exec, passthru, exec, phpinfo, popen, proc_open
        $curlenabled = $init['curl']['cURL support'];

        $i = 0;
        if ($allowurlfopen == 'On') {
            $resp['phpini'][$i]['name'] = "Allow_url_fopen";
            $resp['phpini'][$i]['current'] = _MECH_XOOPSSECURE_SETTING_ON;
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_SETTING_OFF;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_ALLOWURLFOPEN;
            $resp['phpini'][$i]['ref'] = "https://www.developer.com/design/top-php-security-tips/";
            $resp['phpini'][$i]['errortype'] = self::errorButton("warning", $resp['phpini'][$i]['ref']);
        }

        if ($allowurlinclude == 'On') {
            $i++;
            $resp['phpini'][$i]['name'] = "Allow_url_include";
            $resp['phpini'][$i]['current'] = _MECH_XOOPSSECURE_SETTING_ON;
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_SETTING_OFF;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_ALLOWURLINCLUDE;
            $resp['phpini'][$i]['ref'] = "https://www.developer.com/design/top-php-security-tips/";
            $resp['phpini'][$i]['errortype'] = self::errorButton("warning", $resp['phpini'][$i]['ref']);
        }

        if ($enabledl == 'On') {
            $i++;
            $resp['phpini'][$i]['name'] = "enable_dl";
            $resp['phpini'][$i]['current'] = _MECH_XOOPSSECURE_SETTING_ON;
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_SETTING_OFF;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_DYNAMICDL;
            $resp['phpini'][$i]['ref'] = "https://www.php.net/manual/en/ini.core.php";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($registerglobals == 'On' && $phpversion < '5.3.0') {
            $i++;
            $resp['phpini'][$i]['name'] = "Register_globals";
            $resp['phpini'][$i]['current'] = _MECH_XOOPSSECURE_SETTING_ON;
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_SETTING_OFF;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_REGISTERGLOBALS;
            $resp['phpini'][$i]['ref'] = "https://www.hostgator.com/help/article/register-globals-and-other-php-settings";
            $resp['phpini'][$i]['errortype'] = self::errorButton("warning", $resp['phpini'][$i]['ref']);
        }

        if ($openbasedir == '' || $openbasedir == 'no value') {
            $i++;
            $resp['phpini'][$i]['name'] = "open_basedir";
            $resp['phpini'][$i]['current'] = '';
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_SETTINGS_CUSTOMDIR;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_OPENBASEDIR;
            $resp['phpini'][$i]['ref'] = "https://www.php.net/manual/en/ini.core.php";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($safemode == "On" && $phpversion < '5.3.0') {
            $i++;
            $resp['phpini'][$i]['name'] = "safe_mode";
            $resp['phpini'][$i]['current'] = _MECH_XOOPSSECURE_SETTING_ON;
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_SETTING_OFF;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_SAFEMODE;
            $resp['phpini'][$i]['ref'] = "http://php.adamharvey.name/manual/en/ini.sect.safe-mode.php";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($safemodegid == "On") {
            $i++;
            $resp['phpini'][$i]['name'] = "safe_mode_gid";
            $resp['phpini'][$i]['current'] = _MECH_XOOPSSECURE_SETTING_ON;
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_SETTING_OFF;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_SAFEMODE;
            $resp['phpini'][$i]['ref'] = "http://www.tutorialsscripts.com/php-ini-tutorials/safe_mode_gid.php";
            $resp['phpini'][$i]['errortype'] = self::errorButton("warning", $resp['phpini'][$i]['ref']);
        }

        if ($maxexecutiontime > 30) {
            $i++;
            $resp['phpini'][$i]['name'] = "max_execution_time";
            $resp['phpini'][$i]['current'] = $maxexecutiontime;
            $resp['phpini'][$i]['recommended'] = "< 30";
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_MAXEXECUTIONTIME;
            $resp['phpini'][$i]['ref'] = "https://www.developer.com/design/top-php-security-tips/";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($maxinputtime > 60) {
            $i++;
            $resp['phpini'][$i]['name'] = "max_input_time";
            $resp['phpini'][$i]['current'] = $maxinputtime;
            $resp['phpini'][$i]['recommended'] = "< 60";
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_MAXINPUTTIME;
            $resp['phpini'][$i]['ref'] = "https://www.developer.com/design/top-php-security-tips/";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($memorylimit > self::convertToBytes('40M')) {
            $i++;
            $resp['phpini'][$i]['name'] = "memory_limit";
            $resp['phpini'][$i]['current'] = $memorylimit;
            $resp['phpini'][$i]['recommended'] = "< " . self::convertToBytes('40M');
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_MAXMEMORYLIMIT;
            $resp['phpini'][$i]['ref'] = "https://www.developer.com/design/top-php-security-tips/";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($uploadmaxfilesize > self::convertToBytes('2M')) {
            $i++;
            $resp['phpini'][$i]['name'] = "max_file_uploads";
            $resp['phpini'][$i]['current'] = $uploadmaxfilesize;
            $resp['phpini'][$i]['recommended'] = "< " . self::convertToBytes('2M');
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_UPLOADMAXFILESIZE;
            $resp['phpini'][$i]['ref'] = "https://learn.microsoft.com/en-us/iis/application-frameworks/install-and-configure-php-on-iis/secure-php-with-configuration-settings";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($postmaxsize > self::convertToBytes('8M')) {
            $i++;
            $resp['phpini'][$i]['name'] = "post_max_size";
            $resp['phpini'][$i]['current'] = $postmaxsize;
            $resp['phpini'][$i]['recommended'] = "< " . self::convertToBytes('8M');
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_POSTMAXSIZE;
            $resp['phpini'][$i]['ref'] = "https://learn.microsoft.com/en-us/iis/application-frameworks/install-and-configure-php-on-iis/secure-php-with-configuration-settings";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($maxinputnestinglevels > 64) {
            $i++;
            $resp['phpini'][$i]['name'] = "max_input_nesting_levels";
            $resp['phpini'][$i]['current'] = $maxinputnestinglevels;
            $resp['phpini'][$i]['recommended'] = "< 64";
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_MAXINPUTNESTINGLEVELS;
            $resp['phpini'][$i]['ref'] = "https://learn.microsoft.com/en-us/iis/application-frameworks/install-and-configure-php-on-iis/secure-php-with-configuration-settings";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($displayerrors == 'On') {
            $i++;
            $resp['phpini'][$i]['name'] = "display_errors";
            $resp['phpini'][$i]['current'] = _MECH_XOOPSSECURE_SETTING_ON;
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_SETTING_OFF;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_DISPLAYERRORS;
            $resp['phpini'][$i]['ref'] = "https://www.developer.com/design/top-php-security-tips/";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($logerrors == 'Off') {
            $i++;
            $resp['phpini'][$i]['name'] = "log_errors";
            $resp['phpini'][$i]['current'] = _MECH_XOOPSSECURE_SETTING_OFF;
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_SETTING_ON;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_LOGERRORS;
            $resp['phpini'][$i]['ref'] = "https://www.developer.com/design/top-php-security-tips/";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($errorlog == '' || $errorlog == 'no value') {
            $i++;
            $resp['phpini'][$i]['name'] = "error_log";
            $resp['phpini'][$i]['current'] = '';
            $resp['phpini'][$i]['recommended'] = "C:\path\of\your\choice";
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_ERRORLOG;
            $resp['phpini'][$i]['ref'] = "https://www.developer.com/design/top-php-security-tips/";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }

        if ($exposephp == 'On') {
            $i++;
            $resp['phpini'][$i]['name'] = "expose_php";
            $resp['phpini'][$i]['current'] = _MECH_XOOPSSECURE_SETTING_ON;
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_SETTING_OFF;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_EXPOSEPHP;
            $resp['phpini'][$i]['ref'] = "https://www.developer.com/design/top-php-security-tips/";
            $resp['phpini'][$i]['errortype'] = self::errorButton("warning", $resp['phpini'][$i]['ref']);
        }

        if ($disablefunc == '' || $disablefunc == 'no value') {
            $i++;
            $resp['phpini'][$i]['name'] = "disable_functions";
            $resp['phpini'][$i]['current'] = '';
            $resp['phpini'][$i]['recommended'] = _MECH_XOOPSSECURE_DISABLEFUNCTIONS;
            $resp['phpini'][$i]['description'] = _MECH_XOOPSSECURE_DISABLEFUNCTIONS_DESC;
            $resp['phpini'][$i]['ref'] = "https://www.developer.com/design/top-php-security-tips/";
            $resp['phpini'][$i]['errortype'] = self::errorButton("notice", $resp['phpini'][$i]['ref']);
        }
        return $resp;
    }
    
    /**
     * Is server localhost or live
     * @param array $whitelist ips to look for
     * @return bool
    */
    function isLocalhost($whitelist = ['127.0.0.1', '::1']) 
    {
        return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
    }

    /** 
     * Return styled link based on text and link
     *
     * @param $string the text of error notice
     * @param $ref the url to see more info_arr
     * @return string $string of styled link
     */
    public function errorButton($string, $ref)
    {
        switch ($string) {
            case 'warning':
                return "<a href='{$ref}' target='_BLANK'><span class='xoopssecure_phpini_pill badge badge-pill badge-danger'>" . _MECH_XOOPSSECURE_PHIINI_WARNING . "</span></a>";
            break;
            case 'notice':
                return "<a href='{$ref}' target='_BLANK'><span class='xoopssecure_phpini_pill badge badge-pill badge-warning'>" . _MECH_XOOPSSECURE_PHIINI_NOTICE . "</span></a>";
            break;
        }
    }

    /** 
     * Flatten array
     *
     * @param array $arry
     * @return array $return
    */
    public function arrayFlatten($array)
    {
        $return = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $return = array_merge($return, self::arrayFlatten($value));
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    /** 
     * Get operating system from server
     *
     * @return string $os_platform from http_user_agent
     */
    public function getOS()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform =   "";
        $os_array =   array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );
        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }
        return $os_platform;
    }

    /** 
     * convert string to bytes
     *
     * @param $n the string value
     * @return int $n bytes
     */
    public function convertToBytes($n)
    {
        // If n is -1 then there is no limit
        if ($n == -1) {
            return PHP_INT_MAX;
        }
        switch (substr($n, -1)) {
            case "B":
                return substr($n, 0, -1);
            case "K":
                return substr($n, 0, -1) * 1024;
            case "M":
                return substr($n, 0, -1) * 1024 * 1024;
            case "G":
                return substr($n, 0, -1) * 1024 * 1024 * 1024;
        }

        return $n;
    }

    /** 
     * Get array of information from the system (server)
     *
     * @return array $resp
     */
    public function systemArray()
    {
        $resp = array();
        $databasetype = $this->getServerValues('dbtype');
        $databaseversion = $this->getServerValues('mysql_version');
        if (preg_match('|Apache\/(\d+)\.(\d+)\.(\d+)|', $_SERVER['SERVER_SOFTWARE'], $version)) {
            $apacheversionnum =  $version[1] . '.' . $version[2] . '.' . $version[3];
        } else {
            $apacheversionnum = null;
        }
        $resp['php']['version'] = phpversion();
        $resp['php']['type']    = "php";
        $resp['php']['icon']    = $this->getIcons(strtoupper($resp['php']['type']));
        $resp['php']['release'] = $this->getServerValues('phprelease');
        $resp['php']['vulner']  = $this->getVul('php', $resp['php']['version'], 8);
        if ($databasetype == 'mysql') {
                $resp['mysql']['version']   = $databaseversion;
                $resp['mysql']['type']      = $databasetype;
                $resp['mysql']['icon']        = $this->getIcons(strtoupper($databasetype));
                $resp['mysql']['release']   = $this->mysqlVersion($databaseversion);
                $resp['mysql']['vulner']    = $this->getVul('mysql', $resp['mysql']['version'], 8);
        }
        if ($databasetype == 'MariaDB') {
            $resp['mysql']['version']   = $databaseversion;
            $resp['mysql']['type']      = $databasetype;
            $resp['mysql']['icon']      = $this->getIcons(strtoupper($databasetype));
            $resp['mysql']['release']   = $this->mariadbVersion($databaseversion);
            $resp['mysql']['vulner']    = $this->getVul('mysql', $resp['mysql']['version'], 8);
        }
        $resp['apache']['version'] = $apacheversionnum;
        $resp['apache']['type']     = 'apache';
        $resp['apache']['icon']     = $this->getIcons(strtoupper($resp['apache']['type']));
        $resp['apache']['release']  = $this->apacheVersion($apacheversionnum);
        $resp['apache']['vulner']   = $this->getVul('apache', $apacheversionnum, 6);
        $resp['xoops']['version']   = preg_replace('/[^0-9.]+/', '', substr(XOOPS_VERSION, 6, strlen(XOOPS_VERSION) - 6));
        $resp['xoops']['type']      = 'xoops';
        $resp['xoops']['icon']      = $this->getIcons(strtoupper($resp['xoops']['type']));
        $resp['xoops']['release']   = self::xoopsVersion($resp['xoops']['version']);
        $resp['xoops']['vulner']    = $this->getVul('xoops', $resp['xoops']['version'], 4);

        return $resp;
    }

    /**
     * check server for apache module
     *
     * $param string $val the name of apache module to look for
     * @return array $trn the array of apache module where
     *  exists = true/false and value = apache module setting
     */
    public function getApacheModules($val)
    {
        $apachemod = apache_get_modules();
        $rtn = array();
        $rtn['exists'] = false;
        $trn['value'] = '';
        if (in_array($val, $apachemod)) {
            $rtn['exists'] = true;
            $trn['value'] = array_search($val, $apachemod);
        }

        return $trn;
    }

    /**
     * get various server values used in system info tab
     *
     * @param string $val the name of the value to return setting for
     * @return array $value containing php, mysql, apache value
     */
    public function getServerValues($val)
    {
        switch ($val) {
            case 'os':
                return PHP_OS;
                break;
            case 'phpversion':
                return PHP_VERSION;
                break;
            case 'phprelease':
                $search = $this->phpInfoArray();
                $value = $search["General"]['Build Date'];
                $result = date('d-m-Y', strtotime($value));
                return $result;
                break;
            case 'mysqlversion':
                return self::getApacheModules($val = '', $version = false);
                break;
            case 'mod_rewrite':
                $search = self::getApacheModules($val = 'mod_rewrite');

                return $search['value'];
                break;
            case 'mysql_version':
                $dat = new db();
                $search = $dat->getMysqlVersion();
                return $search['ver'];
                break;
            case 'dbtype':
                $dat = new db();
                $search = $dat->getMysqlVersion();
                return $search['type'];
                break;
            case 'allow_urlfopen':
                $search = $this->parsePHPModules($name = INFO_ALL);
                $value = $search['Core']['allow_url_fopen'];

                return $value;
        }
    }

    /**
     * Get cvedetail json related issues to software and version number
     *
     * @param  string $software which software to retrieve value from
     * @param  intval $ver      version number of software
     * @param  intval $severe   value from 0-10 describing how many security issues to return
     * @return json   $result   Potential issues
     */

    public function getVul($software, $ver, $severe)
    {
        $result = array();
        switch ($software) {
            case 'php':
                $vendor_id = 74;
                $product_id = 128;
                $version_id = $this->getNumCVE($software, $ver);

                break;
            case 'apache':
                $vendor_id = 45;
                $product_id = 66;
                $version_id = $this->getNumCVE($software, $ver);
                break;
            case 'mysql':
                $vendor_id = 185;
                $product_id = 316;
                $version_id = $this->getNumCVE($software, $ver);
                break;
            case 'xoops':
                $vendor_id = 1081;
                $product_id = 1876;
                $version_id = $this->getNumCVE($software, $ver);
                break;
        }

        $postURL = "http://www.cvedetails.com/json-feed.php?" .
        "numrows=10&" .
        "cvssscoremin=" . $severe . "&" .
        "vendor_id=" . $vendor_id .
        "&product_id=" . $product_id .
        "&version_id=" . $version_id;
        if ($software > 0 && $ver > 0 && $version_id > 0) {
            $result = file_get_contents($postURL);
            return json_decode($result, true);
        } else {
            return array();
        }
    }

    /** 
     * Get image icon according to type
     * For instance mysql, php, MariaDB, Apache icons
     *
     * @param type string is the type to look for
     * @return string url for image
     */
    public function getIcons($type)
    {
        switch ($type) {
            case 'PHP':
                return XOOPS_URL . "/modules/xoopssecure/assets/images/phpicon.png";
            break;
            case 'APACHE':
                return XOOPS_URL . "/modules/xoopssecure/assets/images/apacheicon.png";
            break;
            case 'MYSQL':
                return XOOPS_URL . "/modules/xoopssecure/assets/images/mysqlicon.png";
            break;
            case 'MARIADB':
                return XOOPS_URL . "/modules/xoopssecure/assets/images/mariadbicon.png";
            break;
            case 'XOOPS':
                return XOOPS_URL . "/modules/xoopssecure/assets/images/xoopsicon.png";
            break;
        }
    }
    
    /**
     * get value from software and version number corresponding the cvedetail.com value
     *
     * @param  string $soft
     * @param  int    $ver
     * @return int    $resp
     */
    public function getNumCVE($soft, $ver)
    {
        $resp = array(
            'xoops' => array(
                '2.5.10'  => 353048,
                '2.5.8.1' => 210697,
                '2.5.8' => 219455,
                '2.5.7.3' => 210698,
                '2.5.7.2' => 210699,
                '2.5.7' => 175774,
                '2.5.6' => 175954,
                '2.5.4' => 171285,
                '2.5.3' => 171284,
                '2.5.2' => 171283,
                '2.5.1.a' => 117067,
                '2.5.1' => 117068,
                '2.5.0' => 114352,
                '2.4.5' => 117069,
                '2.4.4' => 117070,
                '2.4.3' => 117071,
                '2.4.2' => 117072,
                '2.4.1' => 117073,
                '2.3.3' => 80011,
                '2.3.3b' => 117074,
                '2.3.2a' => 70664,
                '2.3.2b' => 90905,
                '2.3.1' => 70663,
                '2.3.0' => 90903
            ),

            'apache' => array(
                '3.1' =>  2590,
                '2.4.4' =>  148839,
                '2.4.3' =>  142325,
                '2.4.2' =>  132629,
                '2.4.1' =>  124858,
                '2.4.0' =>  124859,
                '2.3.16' =>  124860,
                '2.3.15' =>  124861,
                '2.3.14' =>  124862,
                '2.3.13' =>  124863,
                '2.3.12' =>  124864,
                '2.3.11' =>  124865,
                '2.3.10' =>  124866,
                '2.3.9' =>  124867,
                '2.3.8' =>  124868,
                '2.3.7' =>  124869,
                '2.3.6' =>  101781,
                '2.3.5' =>  87516,
                '2.3.4' =>  87517,
                '2.3.3' =>  87518,
                '2.3.2' =>  78741,
                '2.3.1' =>  78742,
                '2.3.0' =>  47057,
                '2.2.25' =>  148838,
                '2.2.24' =>  146617,
                '2.2.23' =>  142324,
                '2.2.22' =>  142323,
                '2.2.21' => 115228,
                '2.2.20' => 114060,
                '2.2.19' => 112828,
                '2.2.18' => 109916,
                '2.2.17' => 109443,
                '2.2.16' => 109442,
                '2.2.15' => 93077,
                '2.2.14' => 87506,
                '2.2.13' => 80726,
                '2.2.12' => 82377,
                '2.2.11' => 77224,
                '2.2.10' => 77223,
                '2.2.9' => 77222,
                '2.2.8' => 77221,
                '2.2.7' => 77220,
                '2.2.6' => 50156,
                '2.2.5' => 47561,
                '2.2.4' => 40008,
                '2.2.3' => 35945,
                '2.2.3' => 40007,
                '2.2.2' => 47560,
                '2.2.1' => 35677,
                '2.2.0' => 40006,
                '2.1.9' => 78740,
                '2.1.8' => 47559,
                '2.1.7' => 47558,
                '2.1.6' => 25521,
                '2.1.5' => 25520,
                '2.1.4' => 25519,
                '2.1.3' => 25518,
                '2.1.2' => 25517,
                '2.1.1' => 25516,
                '2.1'   => 25515,
                '2.0.64' => 112827,
                '2.0.63' => 80725,
                '2.0.61' => 47557,
                '2.0.60' => 47556,
                '2.0.59' => 45532,
                '2.0.58' => 35682,
                '2.0.57' => 35675,
                '2.0.56' => 35681,
                '2.0.55' => 28138,
                '2.0.54' => 24084,
                '2.0.53' => 24083,
                '2.0.52' => 1594443,
                '2.0.50' => 15612,
                '2.0.49' => 13844,
                '2.0.48' => 11963,
                '2.0.47' => 11962,
                '2.0.46' => 11435,
                '2.0.45' => 10692,
                '2.0.44' => 10691,
                '2.0.43' => 10312,
                '2.0.42' => 8045,
                '2.0.41' => 7532,
                '2.0.40' => 7531,
                '2.0.39' => 7349,
                '2.0.38' => 7348,
                '2.0.37' => 7347,
                '2.0.36' => 6826,
                '2.0.35' => 6336,
                '2.0.34' => 117129,
                '2.0.33' => 117130,
                '2.0.32' => 6335,
                '2.0.31' => 117131,
                '2.0.30' => 117132,
                '2.0.29' => 117133,
                '2.0.28' => 6334,
                '2.0.27' => 117134,
                '2.0.26' => 117135,
                '2.0.25' => 117136,
                '2.0.24' => 117137,
                '2.0.23' => 117138,
                '2.0.22' => 117139,
                '2.0.21' => 117140,
                '2.0.20' => 117141,
                '2.0.19' => 117142,
                '2.0.18' => 117143,
                '2.0.17' => 117144,
                '2.0.16' => 117145,
                '2.0.15' => 117146,
                '2.0.14' => 117147,
                '2.0.13' => 117148,
                '2.0.12' => 117149,
                '2.0.11' => 117150,
                '2.0.9' => 10693,
                '2.0' => 6333
            ),

            'mysql' => array(
                '8.0.16' => 309717,
                '8.0.15' => 281384,
                '8.0.14' => 297568,
                '8.0.13' => 271750,
                '8.0.12' => 259463,
                '8.0.11' => 249859,
                '8.0.10' => 290191,
                '8.0.5' => 290190,
                '8.0.4' => 265025,
                '8.0.3' => 265024,
                '8.0.2' => 265023,
                '8.0.1' => 265022,
                '8.0.0' => 265021,
                '8.0' => 224890,
                '5.7.26' => 309952,
                '5.7.25' => 281383,
                '5.7.24' => 271749,
                '5.7.23' => 271748,
                '5.7.22' => 248874,
                '5.7.21' => 242149,
                '5.7.20' => 234925,
                '5.7.19' => 226159,
                '5.7.18' => 221275,
                '5.7.17' => 212887,
                '5.7.16' => 207181,
                '5.7.15' => 202139,
                '5.7.14' => 203239,
                '5.7.13' => 203240,
                '5.7.12' => 200105,
                '5.7.11' => 193516,
                '5.7.10' => 193518,
                '5.7.9' => 190288,
                '5.7.8' => 226158
            ),

            'php' => array(
                '8.1.0' => 5580,
                '8.0.0' => 637765,
                '7.3.6' => 321741,
                '7.3.5' => 300939,
                '7.3.4' => 284698,
                '7.3.3' => 297774,
                '7.3.2' => 297772,
                '7.3.1' => 297770,
                '7.3.0' => 297766,
                '7.2.19' => 300924,
                '7.2.18' => 300517,
                '7.2.17' => 284697,
                '7.2.16' => 297757,
                '7.2.15' => 297756,
                '7.2.14' => 280817,
                '7.2.14' => 297754,
                '7.2.13' => 280816,
                '7.2.13' => 297752,
                '7.2.12' => 280815,
                '7.2.12' => 297750,
                '7.2.11' => 280814,
                '7.2.11' => 297748,
                '7.2.10' => 269705,
                '7.2.9' => 263746,
                '7.2.8' => 263745,
                '7.2.7' => 257430,
                '7.2.6' => 257429,
                '7.2.5' => 257428,
                '7.2.4' => 257427,
                '7.2.3' => 257426,
                '7.2.2' => 238762,
                '7.2.1' => 239789,
                '7.2.0' => 235668,
                '7.1.30' => 300923,
                '7.1.29' => 300516,
                '7.1.28' => 284696,
                '7.1.27' => 297743,
                '7.1.26' => 297742,
                '7.1.25' => 281277,
                '7.1.24' => 281209,
                '7.1.23' => 281208,
                '7.1.22' => 268982,
                '7.1.21' => 263744,
                '7.1.20' => 263743,
                '7.1.19' => 257425,
                '7.1.18' => 257424,
                '7.1.17' => 257423,
                '7.1.16' => 257422,
                '7.1.15' => 257421,
                '7.1.14' => 257420,
                '7.1.13' => 257419,
                '7.1.12' => 238698,
                '7.1.11' => 238697,
                '7.1.10' => 238622,
                '7.1.9' => 238621,
                '7.1.8' => 221782,
                '7.1.7' => 217635,
                '7.1.6' => 217457,
                '7.1.5' => 214225,
                '7.1.4' => 212841,
                '7.1.3' => 210593,
                '7.1.2' => 211454,
                '7.1.1' => 217456,
                '7.1.0' => 206539
            )
        );
        return (isset($resp[$soft][$ver])) ? $resp[$soft][$ver] : "";
    }

    /**
     * Return CVE and release date value matching Xoops Version installed
     *
     * @param string $ver the version number of installed Xoops
     * @return array
     */
    public function xoopsVersion($ver)
    {
            $version = array(
                    '2.5.112' => '1659477600',
                    '2.5.111' => '1581634800',
                    '2.5.10' => '1556488800',
                    '2.5.9' => '1501538400',
                    '2.5.8' => '1464300000',
                    '2.5.7.2' => '1451689200',
                    '2.5.7.1' => '1403215200',
                    '2.5.7' => '1402696800',
                    '2.5.6' => '1366495200',
                    '2.5.5' => '1334440800',
                    '2.5.4' => '1321743600',
                    '2.5.2' => '1317247200',
                    '2.5.1' => '1300834800',
                    '2.5.0' => '1287784800',
                    '2.4.5' => '1278885600',
                    '2.4.4' => '1263596400',
                    '2.4.3' => '1261782000',
                    '2.4.2' => '1259362800',
                    '2.4.1' => '1256598000',
                    '2.4.0' => '1256248800',
                    '2.3.3' => '1233529200',
                    '2.3.1' => '1223676000',
                    '2.3.0' => '1221861600'
            );
            $resp = date('d-m-Y', (array_key_exists($ver,$version)) ? $version[$ver] : null);
            return ($resp != '') ? $resp : null;
    }

   /**
    * Get release date from MySql version
    *
    * @param  string $ver  the version number
    * @return string $resp unix date of release
    */
    public function mysqlVersion($ver)
    {
        $version = array(
            '8.0.31' => '1665439200',
            '8.0.30' => '1657058400',
            '8.0.29' => '1650924000',
            '8.0.28' => '1642460400',
            '8.0.27' => '1634594400',
            '8.0.26' => '1626732000',
            '8.0.25' => '1620684000',
            '8.0.24' => '1618869600',
            '8.0.23' => '1610924400',
            '8.0.20' => '1587852000',
            '8.0.19' => '1575846000',
            '8.0.16' => '1556143200',
            '8.0.15' => '1548975600',
            '8.0.14' => '1548025200',
            '8.0.13' => '1540159200',
            '8.0.12' => '1532642400',
            '8.0.11' => '1524088800',
            '8.0 RC1' => '1506290400',
            '5.7.32' => '1603058400',
            '5.7.30' => '1587852000',
            '5.7.29' => '1576623600',
            '5.7.27' => '1563746400',
            '5.7.26' => '1556143200',
            '5.7.25' => '1548025200',
            '5.7.24' => '1540159200',
            '5.7.23' => '1532642400',
            '5.7.22' => '1524088800',
            '5.7.21' => '1515970800',
            '5.7.20' => '1508104800',
            '5.7.19' => '1500242400',
            '5.7.18' => '1491775200',
            '5.7.17' => '1481497200',
            '5.7.16' => '1476223200',
            '5.7.15' => '1473112800',
            '5.7.14' => '1469743200',
            '5.7.11' => '1454626800',
            '5.7.10' => '1449442800',
            '5.7' => '1445205600',
            '5.6.29' => '1454626800',
            '5.6.28' => '1449442800',
            '5.6.27' => '1443564000'
        );
            $resp = date('d-m-Y', (array_key_exists($ver,$version)) ? $version[$ver] : null);
            return ($resp != '') ? $resp : null;
    }

   /**
    * Get release date from MariaDB version
    *
    * @param string  $ver   the version number
    * @return string $resp  unix date of release
    */
    public function mariadbVersion($ver)
    {
        $version = array (
            '10.9.04' => '1667775600',
            '10.9.03' => '1663538400',
            '10.9.02' => '1661119200',
            '10.8.06' => '1667775600',
            '10.8.05' => '1663538400',
            '10.8.04' => '1660514400',
            '10.8.03' => '1652997600',
            '10.8.02' => '1644620400',
            '10.8.01' => '1644361200',
            '10.8.00' => '1640041200',
            '10.7.07' => '1667775600',
            '10.7.06' => '1663538400',
            '10.7.05' => '1660514400',
            '10.7.04' => '1652997600',
            '10.7.03' => '1644620400',
            '10.7.02' => '1644361200',
            '10.6.11' => '1667775600',
            '10.6.10' => '1663538400',
            '10.6.09' => '1660514400',
            '10.6.08' => '1652997600',
            '10.6.07' => '1644620400',
            '10.6.06' => '1644361200',
            '10.6.05' => '1636326000',
            '10.6.04' => '1628200800',
            '10.6.03' => '1625522400',
            '10.6.02' => '1623967200',
            '10.5.18' => '1667775600',
            '10.5.17' => '1660514400',
            '10.5.16' => '1652997600',
            '10.5.15' => '1644620400',
            '10.5.14' => '1644361200',
            '10.5.13' => '1636326000',
            '10.5.12' => '1628200800',
            '10.5.11' => '1624399200',
            '10.5.10' => '1620338400',
            '10.5.09' => '1613948400',
            '10.5.08' => '1605049200',
            '10.5.07' => '1604358000',
            '10.5.06' => '1602021600',
            '10.5.05' => '1597010400',
            '10.5.04' => '1592949600',
            '10.4.27' => '1667775600',
            '10.4.26' => '1660514400',
            '10.4.25' => '1652997600',
            '10.4.24' => '1644620400',
            '10.4.23' => '1644361200',
            '10.4.22' => '1636326000',
            '10.4.21' => '1628200800',
            '10.4.20' => '1624399200',
            '10.4.19' => '1620338400',
            '10.4.18' => '1613948400',
            '10.4.17' => '1605049200',
            '10.4.16' => '1604358000',
            '10.4.15' => '1602021600',
            '10.4.14' => '1597010400',
            '10.4.13' => '1589234400',
            '10.4.12' => '1580166000',
            '10.4.11' => '1576018800',
            '10.4.10' => '1573167600',
            '10.4.09' => '1572908400',
            '10.4.08' => '1568152800',
            '10.4.07' => '1564524000',
            '10.4.06' => '1560808800',
            '10.3.37' => '1667775600',
            '10.3.36' => '1660514400',
            '10.3.35' => '1652997600',
            '10.3.34' => '1644620400',
            '10.3.33' => '1644361200',
            '10.3.32' => '1636326000',
            '10.3.31' => '1628200800',
            '10.3.30' => '1624399200',
            '10.3.29' => '1620338400',
            '10.3.28' => '1613948400',
            '10.3.27' => '1605049200',
            '10.3.26' => '1604358000',
            '10.3.25' => '1602021600',
            '10.3.24' => '1597010400',
            '10.3.23' => '1589234400',
            '10.3.22' => '1580166000',
            '10.3.21' => '1576018800',
            '10.3.20' => '1573167600',
            '10.3.19' => '1572908400',
            '10.3.18' => '1568152800',
            '10.3.17' => '1564524000',
            '10.3.16' => '1560722400',
            '10.3.15' => '1557784800',
            '10.3.14' => '1554156000',
            '10.3.13' => '1550703600',
            '10.3.12' => '1546815600',
            '10.3.11' => '1542668400',
            '10.3.10' => '1538604000',
            '10.3.09' => '1534284000',
            '10.3.08' => '1530482400',
            '10.3.07' => '1527199200',
            '10.2.44' => '1652997600',
            '10.2.43' => '1644620400',
            '10.2.42' => '1644361200',
            '10.2.41' => '1636326000',
            '10.2.40' => '1628200800',
            '10.2.39' => '1624399200',
            '10.2.38' => '1620338400',
            '10.2.37' => '1613948400',
            '10.2.36' => '1605049200',
            '10.2.35' => '1604358000',
            '10.2.34' => '1602021600',
            '10.2.33' => '1597010400',
            '10.2.32' => '1589234400',
            '10.2.31' => '1580166000',
            '10.2.30' => '1576018800',
            '10.2.29' => '1573167600',
            '10.2.28' => '1572908400',
            '10.2.27' => '1568152800',
            '10.2.26' => '1564524000',
            '10.2.25' => '1560722400',
            '10.2.24' => '1557352800',
            '10.2.23' => '1553468400',
            '10.2.22' => '1549839600',
            '10.2.21' => '1546383600',
            '10.2.20' => '1545606000',
            '10.2.19' => '1542063600',
            '10.2.18' => '1537826400',
            '10.2.17' => '1534197600',
            '10.2.16' => '1529964000',
            '10.2.15' => '1526508000',
            '10.2.14' => '1522101600',
            '10.2.13' => '1518476400',
            '10.2.12' => '1515020400',
            '10.2.11' => '1511823600',
            '10.2.10' => '1509404400',
            '10.2.09' => '1506463200',
            '10.2.08' => '1503007200',
            '10.2.07' => '1499810400',
            '10.2.06' => '1495490400',
            '10.10.02' => '1668639600',
            '10.1.48' => '1604358000',
            '10.1.47' => '1602021600',
            '10.1.46' => '1597010400',
            '10.1.45' => '1589234400',
            '10.1.44' => '1580166000',
            '10.1.43' => '1573167600',
            '10.1.42' => '1572908400',
            '10.1.41' => '1564524000',
            '10.1.40' => '1557266400',
            '10.1.39' => '1556748000',
            '10.1.38' => '1549407600',
            '10.1.37' => '1541113200',
            '10.1.36' => '1536357600',
            '10.1.35' => '1533592800',
            '10.1.34' => '1529272800',
            '10.1.33' => '1525816800',
            '10.1.32' => '1522101600',
            '10.1.31' => '1517871600',
            '10.1.30' => '1513897200',
            '10.1.29' => '1510614000',
            '10.1.28' => '1506549600',
            '10.1.27' => '1506290400',
            '10.1.26' => '1502316000',
            '10.1.25' => '1499119200',
            '10.1.24' => '1496181600',
            '10.1.23' => '1493762400',
            '10.1.22' => '1489446000',
            '10.1.21' => '1484694000',
            '10.1.20' => '1481756400',
            '10.1.19' => '1478473200',
            '10.1.18' => '1475186400',
            '10.1.17' => '1472508000',
            '10.1.16' => '1468792800',
            '10.1.14' => '1462831200',
            '10.1.13' => '1458860400',
            '10.1.12' => '1456354800',
            '10.1.11' => '1454022000',
            '10.1.10' => '1450911600',
            '10.1.09' => '1448233200',
            '10.1.08' => '1445032800',
        );
        $resp = date('d-m-Y', (array_key_exists($ver,$version)) ? $version[$ver] : null);
            return ($resp != '') ? $resp : null;
    }

    /**
     * Get release date from apache version
     *
     * @param  string $ver   the version number
     * @return string $resp  unix date of release
     */
    public function apacheVersion($ver)
    {
        $version = array (
               '2.5.0-alpha' => '1510095600',
               '2.4.9' => '1394665200',
               '2.4.8' => '1394492400',
               '2.4.7' => '1384815600',
               '2.4.6' => '1373839200',
               '2.4.54' => '1654639200',
               '2.4.53' => '1647212400',
               '2.4.52' => '1639954800',
               '2.4.51' => '1633557600',
               '2.4.50' => '1633298400',
               '2.4.5' => '1373493600',
               '2.4.49' => '1631656800',
               '2.4.48' => '1621893600',
               '2.4.46' => '1596578400',
               '2.4.43' => '1585177200',
               '2.4.41' => '1565301600',
               '2.4.40' => '1564696800',
               '2.4.4' => '1361142000',
               '2.4.39' => '1553641200',
               '2.4.38' => '1547679600',
               '2.4.37' => '1539813600',
               '2.4.36' => '1539122400',
               '2.4.35' => '1537221600',
               '2.4.34' => '1531692000',
               '2.4.33' => '1521327600',
               '2.4.32' => '1520636400',
               '2.4.31' => '1520031600',
               '2.4.30' => '1518994800',
               '2.4.3' => '1345154400',
               '2.4.29' => '1508709600',
               '2.4.28' => '1507154400',
               '2.4.27' => '1499724000',
               '2.4.26' => '1497823200',
               '2.4.25' => '1481842800',
               '2.4.24' => '1481842800',
               '2.4.23' => '1467237600',
               '2.4.22' => '1466373600',
               '2.4.21' => '1466028000',
               '2.4.20' => '1459720800',
               '2.4.2' => '1333576800',
               '2.4.19' => '1458514800',
               '2.4.18' => '1449529200',
               '2.4.17' => '1444341600',
               '2.4.16' => '1436479200',
               '2.4.15' => '1434664800',
               '2.4.14' => '1433973600',
               '2.4.13' => '1433368800',
               '2.4.12' => '1421881200',
               '2.4.11' => '1421276400',
               '2.4.10' => '1405375200',
               '2.4.1' => '1329087600',
               '2.4.0' => '1326668400',
               '2.3.9' => '1290466800',
               '2.3.8' => '1282600800',
               '2.3.7' => '1282168800',
               '2.3.6' => '1276207200',
               '2.3.5' => '1264028400',
               '2.3.4' => '1259103600',
               '2.3.3' => '1257894000',
               '2.3.2' => '1237762800',
               '2.3.16' => '1323903600',
               '2.3.15' => '1320706800',
               '2.3.14' => '1312149600',
               '2.3.13' => '1309212000',
               '2.3.12' => '1305064800',
               '2.3.11' => '1298934000',
               '2.3.10' => '1292194800',
               '2.3.1' => '1230937200',
               '2.3.0' => '1228604400',
               '2.2.9' => '1213048800',
               '2.2.8' => '1199919600',
               '2.2.7' => '1199401200',
               '2.2.6' => '1188856800',
               '2.2.5' => '1186696800',
               '2.2.4' => '1168038000',
               '2.2.34' => '1499292000',
               '2.2.33' => '1498168800',
               '2.2.32' => '1483916400',
               '2.2.31' => '1436911200',
               '2.2.30' => '1436565600',
               '2.2.3' => '1153951200',
               '2.2.29' => '1408658400',
               '2.2.28' => '1408658400',
               '2.2.27' => '1394665200',
               '2.2.26' => '1384297200',
               '2.2.25' => '1481842800',
               '2.2.24' => '1361142000',
               '2.2.23' => '1345500000',
               '2.2.22' => '1327446000',
               '2.2.21' => '1315519200',
               '2.2.20' => '1314655200',
               '2.2.2' => '1145656800',
               '2.2.19' => '1305842400',
               '2.2.18' => '1304805600',
               '2.2.17' => '1287007200',
               '2.2.16' => '1279663200',
               '2.2.15' => '1267484400',
               '2.2.14' => '1253656800',
               '2.2.13' => '1249509600',
               '2.2.12' => '1248040800',
               '2.2.11' => '1228518000',
               '2.2.10' => '1223330400',
               '2.2.1' => '1143842400',
               '2.2.0' => '1133218800',
               '2.1.9' => '1130623200',
               '2.1.8' => '1127599200',
               '2.1.7' => '1124488800',
               '2.1.6' => '1119564000',
               '2.1.5' => '1118959200',
               '2.1.4' => '1110927600',
               '2.1.3' => '1109113200',
               '2.1.2' => '1102546800',
               '2.1.10' => '1132441200',
               '2.1.1' => '1100905200',
               '2.0.65' => '1372370400',
               '2.0.64' => '1287007200',
               '2.0.63' => '1230246000',
               '2.0.0' => '1018044000',
               '1.3.0' => '897084000',
               '1.2.0' => '865461600',
               '1.1.0' => '836517600',
               '1.0.0' => '817772400',
               '0.8.0' => '807832800',
               '0.6.2' => '798933600',
               );
        $resp = date('d-m-Y', (array_key_exists($ver,$version)) ? $version[$ver] : null);
        return ($resp != '') ? $resp : null;
    }

    /** 
     * parse php modules from phpinfo
     *
     * @param $name Which part of info to get. Possible names are =
     *  INFO_GENERAL        The configuration line, php.ini location, build date, Web Server, System and more.
     *  INFO_CREDITS        PHP Credits. See also phpcredits().
     *  INFO_CONFIGURATION    Current Local and Master values for PHP directives. See also ini_get().
     *  INFO_MODULES        Loaded modules and their respective settings. See also get_loaded_extensions().
     *  INFO_ENVIRONMENT    Environment Variable information that's also available in $_ENV.
     *  INFO_VARIABLES        Shows all predefined variables from EGPCS (Environment, GET, POST, Cookie, Server).
     *  INFO_LICENSE        PHP License information. See also the license FAQ.
     *  INFO_ALL            Show all of above
     *
     * @return array $config
     */
    public function parsePHPModules($name)
    {
        ob_start();
        phpinfo($name);
        $s = ob_get_contents();
        ob_end_clean();

        $s = strip_tags($s, '<h2><th><td>');
        $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', "<info>\\1</info>", $s);
        $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', "<info>\\1</info>", $s);
        $vTmp = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
        $vModules = array();
        for ($i = 1, $iMax = count($vTmp); $i < $iMax; $i++) {
            if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $vTmp[$i], $vMat)) {
                $vName = trim($vMat[1]);
                $vTmp2 = explode("\n", $vTmp[$i + 1]);
                foreach ($vTmp2 as $vOne) {
                    $vPat = '<info>([^<]+)<\/info>';
                    $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                    $vPat2 = "/$vPat\s*$vPat/";
                    if (preg_match($vPat3, $vOne, $vMat)) { // 3cols
                        $vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
                    } elseif (preg_match($vPat2, $vOne, $vMat)) { // 2cols
                        $vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
                    }
                }
            }
        }

        return $vModules;
    }
}
