<?php
/**
 * ****************************************************************************
 * XoopsSecure - MODULE FOR XOOPS
 * Copyright (c) Michael Albertsen (http://www.culex.dk)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Michael Albertsen (culex)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         XoopSecure
 * @author 			Michael Albertsen (http://www.culex.dk)
 * @version		    $Id $
 * ****************************************************************************
 */

if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
set_time_limit(999999);

/**
 * Class xoopsSecure_Mech
 */
class xoopsSecure_Mech
{
    //var $userdatetype;

    /**
     *
     */
    public function __construct()
    {
        //$this->userdatetype = xoopssecure_GetModuleOption('dateformat');
    }

    /**
     * @return array
     */
    public function phpinfo_array()
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

    /*
     * Testing various php settings on server.
     * @return array $resp with values on vulnerable php ini settings
     */
    /**
     * @return array
     */
    public function testServer()
    {
        $init = $this->phpinfo_array();
        $resp = array();
        // Software installed
        $phpversion = $init['Core']['PHP Version'];
        $mysqlversion_temp = explode("-", $init['mysql']['Client API version']);
        $mysqlversion = preg_replace('/[^0-9.]+/', '', $mysqlversion_temp[0]);
        
        if (preg_match('|Apache\/(\d+)\.(\d+)\.(\d+)|', $_SERVER['SERVER_SOFTWARE'], $version)) {
            $apacheversionnum =  $version[1].'.'.$version[2].'.'.$version[3];
        }
               
        // core
        $allowurlfopen = $init['Core']['allow_url_fopen']['local']; // off
        $allowurlinclude = $init['Core']['allow_url_include']['local']; // off
        $registerglobals = ($phpversion < '5.3.0') ? $init['Core']['register_globals']['local'] : 'Off'; // off
        $openbasedir = $init['Core']['open_basedir']['local']; // "c:\inetpub\"
        $safemode = ($phpversion < '5.3.0') ? $init['Core']['safe_mode']['local'] : 'Off'; // off
        $safemodegid = ($phpversion < '5.3.0') ? $init['Core']['safe_mode_gid']['local'] : 'Off'; // off
        $maxexecutiontime = $init['Core']['max_execution_time']['local']; // < 30
        $maxinputtime = $init['Core']['max_input_time']['local']; // < 60
        $memorylimit = xoopssecure_convertToBytes($init['Core']['memory_limit']['local']);// < 16M
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
        
        
        if ($allowurlfopen == 'On') {
            $resp['phpini']['name'][] = "Allow_url_fopen";
            $resp['phpini']['current'][] = _AM_XOOPSSECURE_SETTING_ON;
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_SETTING_OFF;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_ALLOWURLFOPEN;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "warning";
        }
        
        if ($allowurlinclude == 'On') {
            $resp['phpini']['name'][] = "Allow_url_include";
            $resp['phpini']['current'][] = _AM_XOOPSSECURE_SETTING_ON;
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_SETTING_OFF;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_ALLOWURLINCLUDE;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "warning";
        }
        
        if ($enabledl == 'On') {
            $resp['phpini']['name'][] = "enable_dl";
            $resp['phpini']['current'][] = _AM_XOOPSSECURE_SETTING_ON;
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_SETTING_OFF;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_DYNAMICDL;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($registerglobals == 'On' && $phpversion < '5.3.0') {
            $resp['phpini']['name'][] = "Register_globals";
            $resp['phpini']['current'][] = _AM_XOOPSSECURE_SETTING_ON;
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_SETTING_OFF;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_REGISTERGLOBALS;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "warning";
        }

        if ($openbasedir == '' || $openbasedir == 'no value') {
            $resp['phpini']['name'][] = "open_basedir";
            $resp['phpini']['current'][] = '';
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_SETTINGS_CUSTOMDIR;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_OPENBASEDIR;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($safemode == "On" && $phpversion < '5.3.0') {
            $resp['phpini']['name'][] = "safe_mode";
            $resp['phpini']['current'][] = _AM_XOOPSSECURE_SETTING_ON;
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_SETTING_OFF;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_SAFEMODE;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($safemodegid == "On") {
            $resp['phpini']['name'][] = "safe_mode_gid";
            $resp['phpini']['current'][] = _AM_XOOPSSECURE_SETTING_ON;
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_SETTING_OFF;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_SAFEMODE;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "warning";
        }

        if ($maxexecutiontime > 30) {
            $resp['phpini']['name'][] = "max_execution_time";
            $resp['phpini']['current'][] = $maxexecutiontime;
            $resp['phpini']['recommended'][] = "< 30";
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_MAXEXECUTIONTIME;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($maxinputtime > 60) {
            $resp['phpini']['name'][] = "max_input_time";
            $resp['phpini']['current'][] = $maxinoputtime;
            $resp['phpini']['recommended'][] = "< 60";
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_MAXEXECUTIONTIME;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($memorylimit > xoopssecure_convertToBytes('60M')) {
            $resp['phpini']['name'][] = "memory_limit";
            $resp['phpini']['current'][] = $memorylimit;
            $resp['phpini']['recommended'][] = "< ".xoopssecure_convertToBytes('60M');
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_MAXEXECUTIONTIME;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($uploadmaxfilesize > xoopssecure_convertToBytes('2M')) {
            $resp['phpini']['name'][] = "max_file_uploads";
            $resp['phpini']['current'][] = $uploadmaxfilesize;
            $resp['phpini']['recommended'][] = "< ".xoopssecure_convertToBytes('2M');
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_UPLOADMAXFILESIZE;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($postmaxsize > xoopssecure_convertToBytes('8M')) {
            $resp['phpini']['name'][] = "post_max_size";
            $resp['phpini']['current'][] = $postmaxsize;
            $resp['phpini']['recommended'][] = "< ".xoopssecure_convertToBytes('8M');
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_POSTMAXSIZE;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($maxinputnestinglevels > 32) {
            $resp['phpini']['name'][] = "max_input_nesting_levels";
            $resp['phpini']['current'][] = $maxinputnestinglevels;
            $resp['phpini']['recommended'][] = "< 32";
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_MAXINPUTNESTINGLEVELS;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($displayerrors == 'On') {
            $resp['phpini']['name'][] = "display_errors";
            $resp['phpini']['current'][] = _AM_XOOPSSECURE_SETTING_ON;
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_SETTING_OFF;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_DISPLAYERRORS;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($logerrors == 'Off') {
            $resp['phpini']['name'][] = "log_errors";
            $resp['phpini']['current'][] = _AM_XOOPSSECURE_SETTING_OFF;
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_SETTING_ON;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_LOGERRORS;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        if ($errorlog == '' || $errorlog == 'no value') {
            $resp['phpini']['name'][] = "error_log";
            $resp['phpini']['current'][] = '';
            $resp['phpini']['recommended'][] = "C:\path\of\your\choice";
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_ERRORLOG;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }

        if ($exposephp == 'On') {
            $resp['phpini']['name'][] = "expose_php";
            $resp['phpini']['current'][] = _AM_XOOPSSECURE_SETTING_ON;
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_SETTING_OFF;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_EXPOSEPHP;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "warning";
        }
        
        if ($disablefunc == '' || $disablefunc == 'no value') {
            $resp['phpini']['name'][] = "disable_functions";
            $resp['phpini']['current'][] = '';
            $resp['phpini']['recommended'][] = _AM_XOOPSSECURE_DISABLEFUNCTIONS;
            $resp['phpini']['description'][] = _AM_XOOPSSECURE_DISABLEFUNCTIONS_DESC;
            $resp['phpini']['ref'][] = "";
            $resp['phpini']['errortype'][] = "notice";
        }
        
        return $resp;
    }

    /**
     * @return array
     */
    public function systemArray()
    {
        $info = $this->testServer();
        $resp = array();
        
        if (preg_match('|Apache\/(\d+)\.(\d+)\.(\d+)|', $_SERVER['SERVER_SOFTWARE'], $version)) {
            $apacheversionnum =  $version[1].'.'.$version[2].'.'.$version[3];
        }
        $resp['php']['version'] = phpversion();
        $resp['php']['vulner']  = $this->getVul('php', $resp['php']['version'], 8);
        $resp['mysql']['version'] = $this->getServerValues('mysql_version');
        $resp['mysql']['vulner']  = $this->getVul('mysql', $resp['mysql']['version'], 8);
        $resp['apache']['version'] = $apacheversionnum;
        $resp['apache']['vulner']  = $this->getVul('apache', $apacheversionnum, 6);
        $resp['xoops']['version'] = preg_replace('/[^0-9.]+/', '', substr(XOOPS_VERSION, 6, strlen(XOOPS_VERSION)-6));
        $resp['xoops']['vulner']  = $this->getVul('xoops', $resp['xoops']['version'], 4);

        return $resp;
    }
    
    /*
     * @desc get various server values used in system info tab
     * @param strint $val the name of the value to return setting for
     * @return bolean containing php, mysql, apache value
    */
    /**
     * @param $val
     * @return mixed|string
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
            case 'mysqlversion':
                return getApacheModules($val='', $version=false);
                break;
            case 'mod_rewrite':
                $search = $this->getApacheModules($val='mod_rewrite');

                return $search['value'];
                break;
            case 'mysql_version':
                $search = $this->parsePHPModules($name=INFO_ALL);
                $value = $search['mysql']['Client API version'];
                $result = explode("-", $value);

                return preg_replace('/[^0-9.]+/', '', $result[0]);
                break;
            case 'allow_urlfopen':
                $search = $this->parsePHPModules($name=INFO_ALL);
                $value = $search['Core']['allow_url_fopen'];

                return $value;
           
        }
    }
 
    /**
     * Get cvedetail json related issues to software and version number
     * @param  string $software which software to retrieve value from
     * @param  intval $ver      version number of software
     * @param  intval $severe   value from 0-10 describing how many security issues to return
     * @return json
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

        $postURL = "http://www.cvedetails.com/json-feed.php?".
        "numrows=10&".
        "cvssscoremin=".$severe."&".
        "vendor_id=".$vendor_id.
        "&product_id=".$product_id.
        "&version_id=".$version_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $postURL);
        $result=curl_exec($ch);

        return json_decode($result, true);
    }

    /**
     * get value from software and version number corresponding the cvedetail.com value
     * @param  string $soft
     * @param  intval $ver
     * @return intval
     */
    public function getNumCVE($soft, $ver)
    {
        $resp = array(
            'xoops' => array(
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
                '2.3.0' => 90903,
                '2.2.3' => 27083,
                '2.0.18.2' => 117075,
                '2.0.18.1' => 58504,
                '2.0.18' => 51812,
                '2.0.17.1' => 117078,
                '2.0.17' => 90894,
                '2.0.16' => 40627,
                '2.0.15' => 90893,
                '2.0.14' => 36399,
                '2.0.13.2' => 33919,
                '2.0.13.1' => 26405,
                '2.0.13' => 90891,
                '2.0.12' => 90889,
                '2.0.12a' => 90890,
                '2.0.11' => 24059,
                '2.0.10' => 24058,
                '2.0.9.3' => 24064,
                '2.0.9.2' => 22240,
                '2.0.9' => 24063,
                '2.0.7.3' => 90887,
                '2.0.7.2' => 90886,
                '2.0.7.1' => 90885,
                '2.0.7' => 24062,
                '2.0.6' => 24061,
                '2.0.5.2' => 19696,
                '2.0.5.1' => 19695,
                '2.0.5' => 19694,
                '2.0.4' => 24060,
                '2.0.3' => 19693,
                '2.0.2' => 19692,
                '2.0.1' => 12519,
                '2.0.0' => 90883,
                '2.0' => 12518,
                '1.3.10' => 22239,
                '1.3.9' => 12517,
                '1.3.8' => 12516,
                '1.3.7' => 12515,
                '1.3.6' => 12514,
                '1.3.5' => 12513,
                '1.0' => 9835
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
                '6.0.10' => 73142,
                '6.0.9' => 71381,
                '6.0.4' => 50086,
                '6.0.3' => 50085,
                '6.0.2' => 50084,
                '6.0.1' => 50083,
                '6.0.0' => 50082,
                '5.5.9' => 125805,
                '5.5.8' => 119337,
                '5.5.7' => 125806,
                '5.5.6' => 125807,
                '5.5.5' => 102658,
                '5.5.4' => 102492,
                '5.5.3' => 102491,
                '5.5.2' => 102490,
                '5.5.1' => 102489,
                '5.5.0' => 102488,
                '5.1.50' => 102657,
                '5.1.49' => 102655,
                '5.1.48' => 102487,
                '5.1.47' => 95087,
                '5.1.46' => 92642,
                '5.1.45' => 91259,
                '5.1.44' => 91592,
                '5.1.43' => 91593,
                '5.1.42' => 91594,
                '5.1.41' => 91595,
                '5.1.40' => 91596,
                '5.1.39' => 91597,
                '5.1.38' => 91598,
                '5.1.37' => 91599,
                '5.1.36' => 91600,
                '5.1.35' => 91601,
                '5.1.34' => 91602,
                '5.1.33' => 91603,
                '5.1.32' => 91604,
                '5.1.31' => 91605,
                '5.1.30' => 71379,
                '5.1.29' => 95081,
                '5.1.28' => 95082,
                '5.1.27' => 95083,
                '5.1.26' => 95084,
                '5.1.25' => 95085,
                '5.1.24' => 95086,
                '5.1.23' => 55514,
                '5.1.22' => 55513,
                '5.1.21' => 55512,
                '5.1.20' => 55511,
                '5.1.19' => 55510,
                '5.1.18' => 55509,
                '5.1.17' => 44751,
                '5.1.16' => 44755,
                '5.1.15' => 44754,
                '5.1.14' => 44753,
                '5.1.13' => 39652,
                '5.1.12' => 44752,
                '5.1.11' => 35237,
                '5.1.10' => 35236,
                '5.1.9' => 35244,
                '5.1.8' => 35243,
                '5.1.7' => 35242,
                '5.1.6' => 35241,
                '5.1.5' => 34578,
                '5.1.4' => 35240,
                '5.1.3' => 35239,
                '5.1.2' => 35238,
                '5.1.1' => 35235,
                '5.1' => 61898,
                '5.0.94' => 120514,
                '5.0.93' => 120513,
                '5.0.92' => 120512,
                '5.0.91' => 91606,
                '5.0.90' => 91607,
                '5.0.89' => 91608,
                '5.0.88' => 91609,
                '5.0.87' => 91610,
                '5.0.86' => 91611,
                '5.0.85' => 91612,
                '5.0.84' => 91613,
                '5.0.83' => 71378,
                '5.0.82' => 71377,
                '5.0.81' => 71376,
                '5.0.80' => 140318,
                '5.0.79' => 140319,
                '5.0.78' => 140320,
                '5.0.77' => 71375,
                '5.0.76' => 140321,
                '5.0.75' => 71374,
                '5.0.74' => 140322,
                '5.0.74' => 140323,
                '5.0.72' => 140324,
                '5.0.70' => 140326
            ),
            
            'php' => array(
                '5.3.0' => 82589,
                '5.3.1' => 87778,
                '5.3.2' => 90936,
                '5.3.3' => 97802,
                '5.3.4' => 102443,
                '5.3.5' => 104679,
                '5.3.6' => 105871,
                '5.3.7' => 112737,
                '5.3.8' => 116158,
                '5.3.9' => 121556,
                '5.3.10' => 125887,
                '5.3.11' => 125886,
                '5.3.12' => 125889,
                '5.3.13' => 125892,
                '5.3.14' => 130365,
                '5.3.15' => 142891,
                '5.3.16' => 142890,
                '5.3.17' => 136532,
                '5.3.18' => 142889,
                '5.3.19' => 142888,
                '5.3.20' => 142887,
                '5.3.21' => 142886,
                '5.3.22' => 147275,
                '5.3.23' => 147276,
                '5.3.24' => 147277,
                '5.3.25' => 147278,
                '5.3.26' => 148441,
                '5.3.27' => 149816,
                '5.4.0'  => 38980,
                '5.4.1'  => 125888,
                '5.4.2' => 125891,
                '5.4.3' => 126108,
                '5.4.4' => 130366,
                '5.4.5' => 142899,
                '5.4.6' => 142898,
                '5.4.7' => 142897,
                '5.4.8' => 142896,
                '5.4.9' => 142895,
                '5.4.10' => 142894,
                '5.4.11' => 142893,
                '5.4.12' => 142892,
                '5.4.13' => 146498,
                '5.4.14' => 146495,
                '5.4.15' => 147279,
                '5.4.16' => 157067,
                '5.4.17' => 150257,
                '5.4.18' => 157066,
                '5.4.19' => 157065,
                '5.4.20' => 157064,
                '5.4.21' => 157063,
                '5.4.22' => 157062,
                '5.5.0' => 150258,
                '5.5.1' => 149815,
                '5.5.2' => 156308,
                '5.5.3' => 156307,
                '5.5.4' => 156306,
                '5.5.5' => 156305,
                '5.5.6' => 156304,
                '6.0' => 42348
            )
        );

        return (isset($resp[$soft][$ver])) ? $resp[$soft][$ver]:"";
    }

    /* parse php modules from phpinfo
     *
     * @param $name Which part of info to get. Possible names are =
     *  INFO_GENERAL	    The configuration line, php.ini location, build date, Web Server, System and more.
     *  INFO_CREDITS	    PHP Credits. See also phpcredits().
     *  INFO_CONFIGURATION	Current Local and Master values for PHP directives. See also ini_get().
     *  INFO_MODULES	    Loaded modules and their respective settings. See also get_loaded_extensions().
     *  INFO_ENVIRONMENT	Environment Variable information that's also available in $_ENV.
     *  INFO_VARIABLES	    Shows all predefined variables from EGPCS (Environment, GET, POST, Cookie, Server).
     *  INFO_LICENSE	    PHP License information. See also the license FAQ.
     *  INFO_ALL            Show all of above
     *
     * @return array of $config name => values
    */

    /**
     * @param $name
     * @return array
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
        for ($i=1;$i<count($vTmp);$i++) {
            if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $vTmp[$i], $vMat)) {
                $vName = trim($vMat[1]);
                $vTmp2 = explode("\n", $vTmp[$i+1]);
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
}// end class
