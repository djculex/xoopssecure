<?php

namespace XoopsModules\Xoopssecure;

use XoopsDatabase;
use XoopsDatabaseFactory;
use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use XoopsPersistableObjectHandler;

/**
 * Database class for XoopsSecure
 *
 * Methods for all database handling
 *
 * @author    Michael Albertsen <culex@culex.dk>
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class Xoopssecure_Db extends XoopsPersistableObjectHandler
{
    /**
     * @var XoopsDatabase|null
     */
    public $db;

    /**
     * @var Xoopssecure_Helper|null
     */
    public Xoopssecure_Helper|null $helper;

    /**
     * @var int|string $timestamp
     */
    public string|int $timestamp;


    /**
     * constructor
     *
     * @param XoopsDatabase|null $db     initiating MySql connection_aborted
     * @param null               $helper initiating Helper
     */
    public function __construct(XoopsDatabase $db=null, $helper=null)
    {
        if (null === $helper) {
            $helper = Xoopssecure_Helper::getInstance();
        }

        $this->helper = $helper;

        if (null === $db) {
            $db = XoopsDatabaseFactory::getDatabaseConnection();
        }

        $this->db = $db;
    }//end __construct()


    /**
     * saving Coding Standard array to Database
     *
     * @param array          $array     response from phpcheckstyle
     * @param integer|string $timestamp what time is it
     */
    public function parseCsArray($array, $timestamp)
    {
        if (is_array($array)) {
            foreach ($array as $arr) {
                foreach ($arr as $key => $val) {
                    $filename = $key;

                    $this->timestamp = $timestamp;
                    foreach ($val as $v) {
                        $level   = $v[0]['level'];
                        $line    = $v[0]['line'];
                        $message = $v[0]['message'];
                        $check   = $v[0]['check'];
                        $rating  = 0;
                        if (!$this->issueExists($message, $line)) {
                            $this->loadSave(
                                $this->timestamp,
                                '4',
                                '0',
                                htmlentities($message, ENT_QUOTES),
                                htmlentities($check, ENT_QUOTES),
                                $filename,
                                dirname($filename),
                                $rating     = 0,
                                $linenumber = $line,
                                $op         = 'save'
                            );
                        }
                    } //end foreach
                } //end foreach
            } //end foreach
        } //end if
    }//end parseCsArray()


    /**
     * Check if issue already exists in database
     *
     * @param  string $desc       description of issue
     * @param  string $linenumber well line number
     * @return boolean (issue found or not found)
     */
    public function issueExists($desc, $linenumber): bool
    {
        $sql     = 'Select * From '.$this->db->prefix('xoopssecure_issues')." where `desc` = '".addslashes($desc)."' AND `linenumber` = '".addslashes($linenumber)."'";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows == 0 || $numrows == '') {
            return false;
        } else {
            return true;
        }
    }//end issueExists()


    /**
     * Insert config values to table
     *
     * @param  string  $scanTime   The timestamp
     * @param  string  $scanType   The type of scan 0=index file, 1=malware, 2=permissions
     * @param  string  $scanValue  value for file/folder 0=none, 1=issue found
     * @param  string  $scanDesc   a description of the issue
     * @param  string  $title      Title/tag of issue
     * @param  string  $filename   Name of file
     * @param  string  $dirname    Parent dir of file
     * @param  integer $rating     rating 0-10 of issue (not yet implemented)
     * @param  string  $linenumber Line number in file of issue
     * @param  string  $op         Action to do (save, update, delete)
     * @return boolean $result
     */
    public function loadSave(
        $scanTime,
        $scanType,
        $scanValue,
        $scanDesc,
        $title,
        $filename,
        $dirname,
        $rating=0,
        $linenumber=0,
        $op='save'
    ): bool {
        if ($op == 'save') {
            $sql = 'INSERT INTO '.$this->db->prefix('xoopssecure_issues').' (`id`, `time`, `scantype`, `value`, `title`, `filename`, `dirname`, '.'`linenumber`, `desc`, `rating`) VALUES '.'(null, "'.$scanTime.'", "'.addslashes($scanType).'", "'.addslashes($scanValue).'", "'.addslashes($title).'", "'.addslashes($filename).'", "'.addslashes($dirname).'", "'.addslashes($linenumber).'", "'.addslashes($scanDesc).'", "'.addslashes($rating).'")';
        }

        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return $result;
    }//end loadSave()


    /**
     * Load issues from Database by timestamp
     *
     * @param integer $start the timestamp
     *                       $return array $arr with MySql return
     */
    public function loadIFissues($start): array
    {
        $this->SetGlobal();
        $arr     = [];
        $sql     = 'Select * From '.$this->db->prefix('xoopssecure_issues')." where `time` = '".addslashes($start)."' AND scantype = '1' GROUP BY `dirname` ".'ORDER BY `dirname` ASC';
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows >= 1) {
            $i = 0;
            while ($row = $this->db->fetchArray($result)) {
                $arr[$i]['humantime']   = date('d-m-Y H:i:s', round(($row['time'] / 1000)));
                $arr[$i]['dirname']     = $row['dirname'];
                $arr[$i]['fixed']       = ($row['value'] == '0') ? true : false;
                $arr[$i]['description'] = stripcslashes($row['desc']);
                $i++;
            }
        }

        return $arr;
    }//end loadIFissues()


    /**
     * Set temp. global in Mysql
     *
     * Temporarily disable error code 1055 in MySql
     * Global mode trick disable "Error Code: 1055.
     * Expression #1 of ORDER BY clause is not in GROUP BY clause and contains non-aggregated column"
     */
    public function SetGlobal()
    {
        $sql    = "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        $result = $this->db->queryF($sql);
    }//end SetGlobal()


    /**
     * Load issues from Database by timestamp
     *
     * @param integer $start the timestamp
     *     $return array $arr with MySql return
     */
    public function loadErrissues($start): array
    {
        // $this->SetGlobal();
        $arr     = [];
        $sql     = 'Select * From '.$this->db->prefix('xoopssecure_issues')." where `time` = '".addslashes($start)."' AND scantype = 'x' GROUP BY `dirname` ORDER BY `dirname` ASC";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows >= 1) {
            $i = 0;
            while ($row = $this->db->fetchArray($result)) {
                $arr[$i]['humantime']   = date('d-m-Y H:i:s', round(($row['time'] / 1000)));
                $arr[$i]['filename']    = $row['filename'];
                $arr[$i]['dirname']     = $row['dirname'];
                $arr[$i]['description'] = stripcslashes($row['desc']);
                $i++;
            }
        }

        return $arr;
    }//end loadErrissues()


    /**
     * Load file permission array from Database by timeStamp
     *
     * @param  integer $start the timestamp
     * @return array $arr the database respons
     */
    public function loadFPissues($start): array
    {
        $this->SetGlobal();
        $arr     = [];
        $sql     = 'Select * From '.$this->db->prefix('xoopssecure_issues')." where `time` = '".addslashes($start)."' AND scantype = '0' GROUP BY `filename` ORDER BY `filename` ASC";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows >= 1) {
            $i = 0;
            while ($row = $this->db->fetchArray($result)) {
                $arr[$i]['humantime']   = date('d-m-Y H:i:s', round(($row['time'] / 1000)));
                $arr[$i]['filename']    = $row['filename'];
                $arr[$i]['fixed']       = ($row['value'] == '0') ? true : false;
                $arr[$i]['description'] = stripcslashes($row['desc']);
                $i++;
            }
        }

        return $arr;
    }//end loadFPissues()


    /**
     * Load malware issues array from Database by timeStamp
     *
     * @param  integer $start the timestamp
     * @return array $arr the database respons
     */
    public function loadMalIssue($start): array
    {
        $file = new Xoopssecure_FileH();
        $arr  = [];
        $val  = '1';
        $this->SetGlobal();
        $sql     = 'Select * From '.$this->db->prefix('xoopssecure_issues')." where `time` = '".addslashes($start)."' AND `scantype` = '2' GROUP BY `filename` ORDER BY `filename` ASC";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows >= 1) {
            $i = 0;
            while ($row = $this->db->fetchArray($result)) {
                $arr[$i]['id']              = $row['id'];
                $arr[$i]['time']            = $row['time'];
                $arr[$i]['humantime']       = date('d-m-Y H:i:s', round(($row['time'] / 1000)));
                $arr[$i]['scantype']        = $row['scantype'];
                $arr[$i]['value']           = $row['value'];
                $arr[$i]['filename']        = $row['filename'];
                $arr[$i]['dirname']         = $row['dirname'];
                $arr[$i]['shortname']       = basename($row['filename']);
                $arr[$i]['filepermissions'] = $file->getFilePermission($row['filename']);
                $arr[$i]['lastmod']         = (is_readable($row['filename'])) ? date('d-m-Y H:i:s', filemtime($row['filename'])) : 0;
                $arr[$i]['issues']          = $this->getIssuesByFn($row['filename'], $row['time'], $val);
                $i++;
            }
        }

        return $arr;
    }//end loadMalIssue()


    /**
     * Calculate server up time
     *
     * @param  string $filename file path
     * @param  string $date     timestamp
     * @param  string $val      config value
     * @return array $arr with database info of file
     */
    public function getIssuesByFn($filename, $date, $val): array
    {
        $arr     = [];
        $sql     = 'Select * From '.$this->db->prefix('xoopssecure_issues')." where `time` = '".addslashes($date)."' AND `filename` = '".addslashes($filename)."' AND value = '".$val."' ORDER BY CAST(linenumber AS INT) ASC";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows >= 1) {
            $i = 0;
            while ($row = $this->db->fetchArray($result)) {
                $arr[$i]['id']         = stripslashes($row['id']);
                $arr[$i]['title']      = stripslashes($row['title']);
                $arr[$i]['linenumber'] = $row['linenumber'];
                $arr[$i]['desc']       = html_entity_decode($row['desc']);
                $arr[$i]['rating']     = $row['rating'];
                $arr[$i]['time']       = date('d-m-Y', round(($row['time'] / 1000)));
                $arr[$i]['shortname']  = basename($row['filename']);
                $arr[$i]['filename']   = $row['filename'];
                $i++;
            }
        }

        return $arr;
    }//end getIssuesByFn()


    /**
     * Load Coding standard issues array from Database by timeStamp
     *
     * @param  integer $start the timestamp
     * @return array $arr the database respons
     */
    public function loadCsIssue($start): array
    {
        $file = new Xoopssecure_FileH();
        $arr  = [];
        $val  = '0';
        $this->SetGlobal();
        $sql     = 'Select * From '.$this->db->prefix('xoopssecure_issues')." where `time` = '".addslashes($start)."' AND `scantype` = '4' GROUP BY `filename` ORDER BY `filename` ASC";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows >= 1) {
            $i = 0;
            while ($row = $this->db->fetchArray($result)) {
                $arr[$i]['id']              = $row['id'];
                $arr[$i]['time']            = $row['time'];
                $arr[$i]['humantime']       = date('d-m-Y H:i:s', round(($row['time'] / 1000)));
                $arr[$i]['scantype']        = $row['scantype'];
                $arr[$i]['value']           = $row['value'];
                $arr[$i]['filename']        = $row['filename'];
                $arr[$i]['dirname']         = $row['dirname'];
                $arr[$i]['shortname']       = basename($row['filename']);
                $arr[$i]['filepermissions'] = $file->getFilePermission($row['filename']);
                $arr[$i]['lastmod']         = date('d-m-Y H:i:s', filemtime($row['filename']));
                $arr[$i]['issues']          = $this->getIssuesByFn($row['filename'], $row['time'], $val);
                $i++;
            }
        }

        return $arr;
    }//end loadCsIssue()


    /**
     * Update config with new file path to NOT include in scans
     *
     * @param  string $value file path
     * @return boolean
     */
    public function getConfigDateOmitfile($value): bool
    {
        $mid    = $this->midByName(_MI_XOOPSSECURE_NAME);
        $sql    = 'SELECT `conf_value` FROM '.$GLOBALS['xoopsDB']->prefix('config')." WHERE `conf_modid` = '".$mid."' AND conf_title = '\_MI_XOOPSSECURE_SCISSKIPFILES'";
        $result = $this->db->queryF($sql);
        $val    = [];
        $value  = str_replace(XOOPS_ROOT_PATH.'/', '', str_replace('\\', '', trim($value)));
        while ($row = $this->db->fetchArray($result)) {
            $r = $row['conf_value'];
        }

        $val = explode("\n", $r);
        if (!in_array($value, $val)) {
            $val[] = str_replace(XOOPS_ROOT_PATH.'/', '', str_replace('\\', '', trim($value)));
        }

        asort($val);
        $valstr = implode("\n", $val);
        $sql    = 'UPDATE '.$GLOBALS['xoopsDB']->prefix('config')." SET `conf_value` = '".addslashes($valstr)."' WHERE `conf_modid` = '".addslashes($mid)."' AND conf_title = '\_MI_XOOPSSECURE_SCISSKIPFILES'";
        if (!$result = $this->db->queryF($sql)) {
            return true;
        } else {
            return false;
        }
    }//end getConfigDateOmitfile()


    /**
     * GET module mid by name
     *
     * @param  string $name module name to search for
     * @return string $n the number in Database for module
     */
    public function midByName($name): string
    {
        $n       = '';
        $query   = 'SELECT mid FROM '.$GLOBALS['xoopsDB']->prefix('modules')." WHERE name = '".addslashes($name)."' ORDER BY mid ASC ";
        $result  = $GLOBALS['xoopsDB']->queryF($query);
        $counter = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($counter >= 1) {
            while ($sqlfetch = $GLOBALS['xoopsDB']->fetchArray($result)) {
                $n = $sqlfetch['mid'];
            }
        }

        return $n;
    }//end midByName()


    /**
     * Update config with new dir path to NOT include in scans
     *
     * @param  string $value dir path
     * @return boolean
     */
    public function getConfigDateOmitdir($value): bool
    {
        $mid    = $this->midByName(_MI_XOOPSSECURE_NAME);
        $sql    = 'SELECT `conf_value` FROM '.$GLOBALS['xoopsDB']->prefix('config')." WHERE `conf_modid` = '".$mid."' AND conf_title = '\_MI_XOOPSSECURE_SCISSKIPFOLDERS'";
        $result = $this->db->queryF($sql);
        $val    = [];
        while ($row = $this->db->fetchArray($result)) {
            $r = $row['conf_value'];
        }

        $val = explode("\n", $r);
        if (!in_array($value, $val)) {
            $val[] = str_replace(XOOPS_ROOT_PATH.'/', '', str_replace('\\', '', trim($value)));
        }

        asort($val);
        $valstr = implode("\n", $val);
        $sql    = 'UPDATE '.$GLOBALS['xoopsDB']->prefix('config')." SET `conf_value` = '".addslashes($valstr)."' WHERE `conf_modid` = '".addslashes($mid)."' AND conf_title = '\_MI_XOOPSSECURE_SCISSKIPFOLDERS'";
        if (!$result = $this->db->queryF($sql)) {
            return true;
        } else {
            return false;
        }
    }//end getConfigDateOmitdir()


    /**
     * Delete issues from database by id
     *
     * @param  string  $fn   the file path
     * @param  boolean $conf is deleted confirmed by user
     * @return void
     */
    public function deleteIssueByFN(string $fn, bool $conf)
    {
        if ($conf) {
            $sql    = 'DELETE FROM '.$this->db->prefix('xoopssecure_issues')." WHERE `filename` = '".addslashes($fn)."'";
            $result = $this->db->queryF($sql);
            echo json_encode('OK', JSON_PRETTY_PRINT);
        }
    }//end deleteIssueByFN()


    /**
     * Delete issues from database by dirname
     *
     * @param  string  $dn   the dir path
     * @param  boolean $conf is deleted confirmed by user
     * @return void json
     */
    public function deleteIssueByDirname($dn, $conf): void
    {
        if ($conf) {
            $sql    = 'DELETE FROM '.$this->db->prefix('xoopssecure_issues')." WHERE `dirname` = '".addslashes($dn)."'";
            $result = $this->db->queryF($sql);
            echo json_encode('OK', JSON_PRETTY_PRINT);
        }
    }//end deleteIssueByDirname()


    /**
     * Delete issues from database by id
     *
     * @param  string $id of issue
     * @return void string
     */
    public function deleteIssueByID($id): void
    {
        $sql    = 'DELETE FROM '.$this->db->prefix('xoopssecure_issues')." WHERE `id` = '".(int) $id."'";
        $result = $this->db->queryF($sql);
        echo json_encode('OK', JSON_PRETTY_PRINT);
    }//end deleteIssueByID()


    /**
     * Delete issues from database by time
     *
     * @param  string $dtime date time stamp
     * @return void
     */
    public function deleteLogByTime($dtime): void
    {
        $query   = 'DELETE FROM '.$this->db->prefix('xoopssecure_issues')." WHERE `time` = '".$dtime."'";
        $query2  = 'DELETE FROM '.$this->db->prefix('xoopssecure_stats')." WHERE `scanstart` = '".$dtime."'";
        $result  = $this->db->queryF($query);
        $result2 = $this->db->queryF($query2);
    }//end deleteLogByTime()


    /**
     * Calculate server up time
     *
     * @return string $ret string of uptime value
     */
    public function serverUptime(): string
    {
        $ret    = [];
        $sql    = "SHOW GLOBAL STATUS LIKE 'Uptime';";
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $ret = $row;
        }

        return $ret['Value'];
    }//end serverUptime()


    /**
     * Get version number of MySql
     *
     * @return array with type and version number
     */
    public function getMysqlVersion(): array
    {
        $typeString = $this->db->getServerVersion();
        if (str_contains($typeString, 'MariaDB')) {
            $type = 'MariaDB';
        } else {
            $type = 'mysql';
        }

        $ver = preg_replace('/[^0-9.]+/', '', trim($typeString));
        return [
            'type' => $type,
            'ver'  => $ver,
        ];
    }//end getMysqlVersion()


    /**
     * Change code read from file to styled format
     *
     * @param  string $filename   file path
     * @param  string $linenumber line number to highlight
     * @param  string $startline  from where to start reading
     * @param  string $mark
     * @param  string $language   coding language of text
     * @return html string
     */
    public function showSource($filename, $linenumber, $startline, $mark, $language): html
    {
        $source   = file_get_contents($filename);
        $language = $language;
        $g        = new GeSHi($source, $language);
        $g->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS, 37);
        $g->start_line_numbers_at($startline);
        $g->set_line_style('color: red; font-weight: bold;', 'color: green;');
        $g->enable_classes();

        if ($linenumber == '' || $linenumber == 0) {
            $g->highlight_lines_extra(1, 'background-color:yellow');
        } else {
            $g->highlight_lines_extra($linenumber, 'background-color:yellow');
        }

        $css = $g->get_stylesheet();
        return $g->parse_code();
    }//end showSource()


    /**
     * Get drop down values from database for log page
     *
     * @return array $values
     */
    public function getLogDropdownDates(): array
    {
        $values  = [];
        $sql     = 'Select DISTINCT `scanstart`, `type` From '.$this->db->prefix('xoopssecure_stats').' ORDER BY `scanstart` ASC';
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        while ($row = $this->db->fetchArray($result)) {
            $key      = $row['scanstart'];
            $value    = date('d-m-Y H:i:s', round(($row['scanstart'] / 1000)));
            $scantype = xoopssecure_scantypeToString($row['type']);
            $values[] = [
                'id'   => $key,
                'name' => $value." ($scantype)",
            ];
        }

        return $values;
    }//end getLogDropdownDates()


    /**
     * Count issues based on time and issue
     *
     * @param  string $time  Timestamp
     * @param  string $issue issue type
     * @return string $arr with count
     */
    public function getIssueCount($time, $issue): string
    {
        $sql     = 'Select count(*) as count From '.$this->db->prefix('xoopssecure_issues')." where `time` = '".addslashes($time)."' AND `scantype` = '".$issue."'";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        while ($row = $this->db->fetchArray($result)) {
            $arr[] = $row;
        }

        return $arr[0]['count'];
    }//end getIssueCount()


    /**
     * Insert config values to table
     *
     * @param  string $ls       Last scan
     * @param  string $mod      Modified time
     * @param  string $path     Path of file
     * @param  string $desc     Description of issue
     * @param  string $scantime timestamp
     * @return boolean $result
     */
    public function safeFileInfo($ls, $mod, $path, $desc, $scantime, $op='save'): bool
    {
        if ($op == 'save') {
            $sql = 'INSERT INTO '.$this->db->prefix('xoopssecure_files').' (`id`, `lastscan`, `modified`, `path`, `desc`) VALUES '.'(null, "'.$ls.'", "'.addslashes($mod).'", "'.addslashes($path).'", "'.addslashes($desc).'")';
        }

        if ($op == 'update') {
            $sql = 'UPDATE '.$this->db->prefix('xoopssecure_files')." SET lastscan = '".addslashes($scantime)."', modified = '".addslashes($mod)."' WHERE path = '".addslashes($path)."'";
        }

        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return $result;
    }//end safeFileInfo()


    /**
     * Does file path exist in database
     *
     * @param  string $filename the url to file
     * @return boolean
     */
    public function Fexists($filename): bool
    {
        $sql     = 'Select * From '.$this->db->prefix('xoopssecure_files')." where path = '".addslashes($filename)."'";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows == 0) {
            return false;
        } else {
            return true;
        }
    }//end Fexists()


    /**
     * Does scan already exist
     *
     * @param  string $start the timestamp to look for
     * @return boolean
     */
    public function StatExists($start): bool
    {
        $sql     = 'Select * From '.$this->db->prefix('xoopssecure_stats')." where `scanstart` = '".addslashes($start)."'";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows == 0) {
            return false;
        } else {
            return true;
        }
    }//end StatExists()


    /**
     * Has file been modified since last ?
     *
     * @param  string $filename the url to file
     * @param  $mod
     * @return boolean
     * @rapam  int $mod the modification timestamp
     */
    public function Fchanged($filename, $mod): bool
    {
        $sql    = 'Select * From '.$this->db->prefix('xoopssecure_files')." where path = '".addslashes($filename)."'";
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $arr[] = $row;
        }

        if ($arr[0]['modified'] < $mod) {
            return true;
        } else {
            return false;
        }
    }//end Fchanged()


    /**
     * Is table empty ?
     *
     * @return boolean
     */
    public function isTableEmpty(): bool
    {
        $sql     = 'Select * From '.$this->db->prefix('xoopssecure_files');
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows == 0) {
            return true;
        } else {
            return false;
        }
    }//end isTableEmpty()


    /**
     * Get latest scan log
     *
     * @return void html created from database
     */
    public function GetLatestLogCandT(): void
    {
        $sql    = 'SELECT * FROM '.$this->db->prefix('xoopssecure_stats').' ORDER by `scanstart` DESC LIMIT 0,1';
        $result = $this->db->queryF($sql);
        $num    = $this->db->getRowsNum($result);
        if ($num > 0) {
            while ($row = $this->db->fetchArray($result)) {
                $i = ((int) $row['permissues'] + (int) $row['indexissues'] + (int) $row['malissues'] + (int) $row['csissues']);
                echo '<div class="row">
                    <div class="col"><h3>'._SCAN_XOOPSSECURE_INLINEISSUESFOUND.'<h3></div>
                    <div class="col"><h3>'._SCAN_XOOPSSECURE_INLINEDATESCANNED.'</h3></div>
					<div class="col"><h3>'._SCAN_XOOPSSECURE_INLINESCANTYPE.'</h3></div>
                    <div class="col"></div>
                    </div>
                    <div class="row">
                    <div class="col">'.$i.'</div>
                    <div class="col">'.date('d-m-Y H:i:s', (round(($row['scanstart'] / 1000)))).'</div>
					<div class="col">'.xoopssecure_scantypeToString($row['type']).'</div>
                    <div class="col">
                        <a href="log.php?starttime='.$row['scanstart'].'">'._SCAN_XOOPSSECURE_INLINELINKTEXT.'</a>
                    </div>
                    </div>';
            }//end while
        }//end if
    }//end GetLatestLogCandT()


    /**
     * Get timestamp of the latest scan
     *
     * @return boolean|integer
     */
    public function getLatestTimeStamp(): bool|int
    {
        $sql     = 'Select `scanfinished` from '.$this->db->prefix('xoopssecure_stats').' order by `scanfinished` DESC limit 0,1';
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        $arr     = [];
        if ($numrows == 0) {
            return 0;
        } else {
            while ($row = $this->db->fetchArray($result)) {
                $arr[] = $row;
            }

            return $arr[0]['scanfinished'];
        }
    }//end getLatestTimeStamp()


    /**
     * Get first timestamp of today
     *
     * @param  sting $d the timestamp
     * @return integer|string time from Database
     */
    public function getTodayStart($d): int|string
    {
        $dt      = date('d-m-Y', ($d));
        $sql     = 'Select `time` from '.$this->db->prefix('xoopssecure_issues')." WHERE FROM_UNIXTIME(`time`, '%d-%m-%Y') = '".$dt."' order by `time` ASC limit 0,1";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        $arr     = [];
        if ($numrows == 0) {
            return 0;
        } else {
            while ($row = $this->db->fetchArray($result)) {
                $arr[] = $row;
            }

            return $arr[0]['time'];
        }
    }//end getTodayStart()


    /**
     * Create log based on scan
     *
     * @param  array  $array of stats
     * @param  string $op    the action to do f.i. save
     * @return void
     */
    public function doStats($array, $op='save'): void
    {
        // if time exists already
        if ($op = 'save') {
            $sql = 'INSERT INTO '.$this->db->prefix('xoopssecure_stats').'(`type`, `scanstart`, `scanfinished`, `permissues`, `perfilestotal`, '.'`indexissues`, `indexfilestotal`, `malissues`, `malfilestotal`, `csissues`, '.'`csfilestotal`) VALUES '."('".addslashes($array['type'])."', "."'".addslashes($array['start'])."', "."'".addslashes($array['end'])."', "."'".addslashes($array['permSet'])."', "."'".addslashes($array['permStack'])."', "."'".addslashes($array['indexSet'])."', "."'".addslashes($array['indexStack'])."', "."'".addslashes($array['malSet'])."', "."'".addslashes($array['malStack'])."', "."'".addslashes($array['csSet'])."', "."'".addslashes($array['csStack'])."')";
            // if time exists already exists do nothing
            if (!$this->ExistsStats($array['start'])) {
                $result = $this->db->queryF($sql);
            }
        }//end if
    }//end doStats()


    /**
     * Check if issue exists based on start time
     *
     * @param  string $starttime the timestamp
     * @return boolean
     */
    public function ExistsStats($starttime): bool
    {
        $sql     = 'Select * from '.$this->db->prefix('xoopssecure_stats')." WHERE `scanstart` = '".$starttime."' order by `scanstart` ASC limit 0,1";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows < 1) {
            return false;
        } else {
            return true;
        }
    }//end ExistsStats()


    /**
     * Check if file is already scanned
     *
     * @param  string $filename  the filename
     * @param  string $starttime the timestamp
     * @return boolean
     */
    public function filealreadyscanned($filename, $starttime): bool
    {
        $sql     = 'Select * from '.$this->db->prefix('xoopssecure_issues')." WHERE `time` = '".addslashes($starttime)."' AND `filename` = '".addslashes($filename)."' order by `time` ASC limit 0,1";
        $result  = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows == 0) {
            return false;
        } else {
            return true;
        }
    }//end filealreadyscanned()


    /**
     * is it time for automatic scan ?
     *
     * @param  string $config the type to look for
     * @return string $ret initiall set to ""
     */
    public function setTimedEvent($config): string
    {
        $ret = '';
        switch ($config) {
            case 'cronscan':
                $ret = strtotime(
                    ' + '.$this->helper->getConfig('XCISCRONINTERVAL').' HOURS',
                    $this->getLatestLog('cronscan')
                ) < time();
                break;

            case 'backup':
                $ret = strtotime(
                    ' + '.$this->helper->getConfig('XCISAUTOBACKUPINTERVAL').' DAYS',
                    $this->getLatestLog('backup')
                ) < time();
                break;
        }

        return $ret;
    }//end setTimedEvent()


    /**
     * Get latest log of auto scans
     *
     * @param  string $scanname name of scan (backup / cronscan)
     * @return integer|array $arr time stamp or 0 if none
     */
    public function getLatestLog(string $scanname): int|array
    {
        $sql    = "Select `$scanname` FROM ".$this->db->prefix('xoopssecure_log');
        $result = $this->db->queryF($sql);
        $arr    = '';
        while ($row = $this->db->fetchArray($result)) {
            $arr = $row[$scanname];
        }

        return ($arr != '') ? (int) $arr : 0;
    }//end getLatestLog()


    /**
     * Do a log of automatic scans
     *
     * @param  string $scanname name of scan (back up / cron scan)
     * @return void
     */
    public function updateLog($scanname): void
    {
        $time   = time();
        $sql    = 'INSERT INTO '.$this->db->prefix('xoopssecure_log')." (id, $scanname) VALUES (0, $time) ON DUPLICATE KEY UPDATE id = 0, $scanname = $time";
        $result = $this->db->queryF($sql);
    }//end updateLog()
}//end class
