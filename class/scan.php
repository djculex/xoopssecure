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

class xoopsSecure_scan {    
    
    var $ext;
    var $urlToScan;
    var $autoindexcreate;
    var $autochmod;
    var $logphpmysqlissues;
    var $ignore;
    var $scantype;
    var $indexfiletype;
    var $autoHtaccessCreate;
    var $contentofhtmlindex;
    var $contentofhtaccessindex;
    var $excludeDirs;
    var $excludeFiles;
    var $xoops_reservedfolders;
    var $badboys;
    var $badfuncs;
    var $inittime;
    var $cronscan;
    var $cronscaninterval;
	var $backuptype;
	var $backupfiles;
    
    public function __construct(){        
        $this->urlToScan = xoopssecure_GetModuleOption('urltoscan');
        $this->autoindexcreate = xoopssecure_GetModuleOption('auto_indexfiles');
        $this->autochmod = xoopssecure_GetModuleOption('autofileperm');
        $this->logphpmysqlissues = xoopssecure_GetModuleOption('logphpmysqlissues');
        $this->ignore = xoopssecure_GetModuleOption('ignorelangfile');
        $this->indexfiletype = xoopssecure_GetModuleOption('indexfiletypes');
        $this->autoHtaccessCreate = xoopssecure_GetModuleOption('indexfiletypes');
        $this->contentofhtmlindex = xoopssecure_ntobr(xoopssecure_GetModuleOption('contentofhtmlindex'));
        $this->contentofhtaccessindex = xoopssecure_ntobr(xoopssecure_GetModuleOption('contentofhtaccessindex'));
        $this->excludeDirs = $this->getIgnoreArrayToVar ('ignore', 'isdir');
        $this->excludeFiles = $this->getIgnoreArrayToVar ('ignore', 'isfile');
        $this->xoops_reservedfolders = $this->getIgnoreArrayToVar ('chmod', 'isdir');
        $this->badboys = xoopssecure_StringToArray (xoopssecure_GetModuleOption('fullscansbadboysearch'));
        $this->badfuncs = xoopssecure_StringToArray (xoopssecure_GetModuleOption('fullscansfunctionsearch'));
        $this->cronscan = false;
        $this->cronscaninterval = xoopssecure_GetModuleOption('cronschedulehours');
        $this->backuptype = xoopssecure_GetModuleOption('backuptype');
		$this->backupfiles = xoopssecure_GetModuleOption('backupcustomfiles');
		
        /*$this->xoops_reservedfolders = array(
            XOOPS_ROOT_PATH .'/cache',
            XOOPS_ROOT_PATH .'/uploads',
            XOOPS_ROOT_PATH .'/templates_c',
            XOOPS_ROOT_PATH .'xoops_data',
            XOOPS_VAR_PATH .'configs',
            XOOPS_VAR_PATH .'caches',
            XOOPS_VAR_PATH .'caches/xoops_cache',
            XOOPS_VAR_PATH .'caches/smarty_cache',
            XOOPS_VAR_PATH .'caches/smarty_compile'
        );*/
    }
    
    /* Get extensions array of quickscan
     * @param void
     * @return void
     */
    function getExt ()
    {
        $this->ext = explode('|', xoopssecure_GetModuleOption('extensions'));
    }
    
    /* Get recursive array of dirs
     * @param string $url
     * @return array
     */    
    function getDirs ($url) {
        return $this->getArray ($url, $type = 'dir');
    }

    /* Get fileinfo recursively as array
     * @param string $url
     * @return array
     */    
    function getFiles ($url) {
        return $this->getArray ($url, $type = 'file');
    }
    
    function verifyExt ($filename)
    {
        $fullext = explode ("|", 'php|php3|php4|php5|phps|html|phtml|txt|asp|htaccess|gif|js');
        if ($this->scantype === 3) {
            if (in_array(pathinfo($filename, PATHINFO_EXTENSION), $fullext)) {
                return true;
            } else {
                return false;
            }
        } else {
            if (in_array(pathinfo($filename, PATHINFO_EXTENSION), $this->ext)) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    /* Function to retrieve info to array recursively
     * @param string $url
     * @param string $type
     * @return array
     */
    function getArray ($url, $type) {     
        $result = array();
        $this->getExt ();
        $url = isset ($url) ? xoopssecure_cleanUrl($url) : xoopssecure_cleanUrl($this->urlToScan);
        $obj = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($url),
                RecursiveIteratorIterator::SELF_FIRST);       
        foreach ($obj as $filename) {
            if ($filename->isDir()) {
                        if (xoopssecure_in_array_r(
                            xoopssecure_rootToUrl ($filename), 
                            xoopssecure_rootToUrl ($this->excludeDirs), 
                            $strict = false
                            ) === true) {
                            continue;
                        }
                    $result['dir']['dirname'][] = str_replace ("\\", "/", $filename->getPathname());                
                    $result['dir']['dirsize'][] = $filename->getSize();
                    $result['dir']['dirdate'][] = $filename->getCtime();
                    $result['dir']['dirperm'][] = substr(decoct(fileperms($filename)),1);
            } 
            if (true == $this->verifyExt($filename)
            && filesize($filename)/* skip empty ones */
            && !stripos($filename, 'scan.php')/* skip this file */) {
                if ($filename->isFile()){
                        if (xoopssecure_in_array_r(
                                xoopssecure_rootToUrl ($filename), 
                                xoopssecure_rootToUrl ($this->excludeFiles), 
                                $strict = false
                            ) === true) {
                            continue;
                        }
                            
                       $result['file']['filename'][] = str_replace ("\\", "/", $url."/".substr($filename, strlen($url) + 1));
                       $result['file']['filesize'][] = $filename->getSize();
                       $result['file']['filedate'][] = $filename->getCTime();
                       $result['file']['hash'][] = md5_file($filename);
                       $result['file']['fileperm'][] = substr(decoct(fileperms($filename)),2);
                       $this->savefileinfo (str_replace ("\\", "/", $filename));
                }
            }
        }
        
        $result['dir']['dircount'] = (!empty($result['dir'])) ? count ($result['dir']['dirname']) : 0;
        $result['file']['filecount'] = count ($result['file']['filename']);
        if ($type == 'dir') {
            return array_unique($result['dir']);
        } else {
            return array_unique($result['file']);
        }
    }
    
    /*
     * @desc Get info about files already saved in Db else scan files on server
     * @return json $array the db values of files from last scan
     */
     function getFileInfoFromDb () 
     {
        global $xoopsDB;
        $array = array();
        $sql = "SELECT * FROM ".$xoopsDB->prefix("xoopsecure_files");
        $result = $xoopsDB->queryF($sql);
        $count = $xoopsDB->getRowsNum($result);
        if (!$result || $count <= 0) {
            $url = isset ($url) ? $url : $this->urlToScan;
            $array = array_merge($this->getDirs($url), $this->getFiles($url));
        } else {
            while ($row = $xoopsDB->fetchArray($result)) { 
                $array = $row;
            }
        }
        $this->backupCheck ();
		return json_encode($array);
     }
     
     
    /*
     * @desc Get fresh scan from files and do backup if configured
     * @return json $array an merged json array of files on server
     */
    function getnewfileinfo ($url) 
    {
        global $xoopsDB;
        $array = array();
        $url = isset ($url) ? $url : $this->urlToScan;
        $url = rtrim($url, '\\');
        $url = rtrim($url, '/');
        $array = array_merge($this->getDirs($url), $this->getFiles($url));
		$this->backupCheck ();
		return json_encode($array);
    }     
    
	/**
	 * Checks if script should do backup
	 *
	 * @throws xoopsSecureZipper If settings are set
	 * @return void
 */ 
	function backupCheck ()
	{
		if ($this->backuptype != 'none') {
			$zip = new xoopsSecureZipper;
			$zip->doZip ($zip->archive, $zip->dirToBackup);
		}
	}
	
    /*
     * Scan file and return message if pattern is found in content
     * @param  string $file the full url to file on server
     * @return json $issues where empy if clean from issues or values if something is found
    */
    function getLines ($file, $ref ) 
    {
        global $xoopsConfig;
        $patterns = $this->getPatterns();
        $count = 0;
        $total_results = 0;
        $issuecount = 0;
        $issues = array();
        //Check for hash modifying
        $hash = $this->hashcheck ($file, $ref);
        if (!empty($hash)) {
            $total_results ++;
            $issues[] = array (
                'status'        => 'Issue',
                'issuename'     => $hash['issuename'],
                'issuecount'    => $total_results
            );
        }
        
        if (!($content = file_get_contents($file))) {
            $error = _AM_XOOPSSECURE_SCANFILEERROR.$file;
            echo $error;
        } else { // do a search for fingerprints
            foreach ($patterns As $pattern) {
                if (is_array($pattern)) { // it's a pattern
                    // RegEx modifiers: i=case-insensitive; s=dot matches also newlines; S=optimization
                    preg_match_all('#' . $pattern[0] . '#isS', $content, $found, PREG_OFFSET_CAPTURE);
                } else { // it's a string
                    preg_match_all('#' . $pattern . '#isS', $content, $found, PREG_OFFSET_CAPTURE);
                }
                $all_results = $found[0]; // remove outer array from results
                $results_count = count($all_results); // count the number of results
                $total_results += $results_count; // total results of all fingerprints
                $dbresult = array();

                if (!empty($all_results)) {
                    $count++;
                    if (is_array($pattern)) {
                        foreach ($all_results as $match) {
                            $dbresult[$count] = array (
                                'status'            => "Issue",
                                'scantype'          => $ref,
                                'time'              => time(),
                                'filename'          => $file,
                                'filetype'          => filetype($file),
                                'fileaccessed'      => fileatime($file),
                                'filechanged'       => filectime($file),
                                'filemodified'      => filemtime($file),
                                'filepermission'    => substr(decoct(fileperms($file)),2),
                                'issuecat'          => 'mal',
                                'issuename'         => $pattern[1],
                                'issuedesc'         => $pattern[3],
                                'linenumber'        => $this->calculate_line_number($match[1], $content),
                                'issuecode'         => htmlentities(substr($content, $match[1], 200), ENT_QUOTES),
                                'tag'               => "security"
                            );
                            $issues[] = array (
                                'status'        => 'Issue',
                                'issuename'     => $pattern[1],
                                'issuecount'    => $total_results
                            );
                            $this->insert_content ($dbresult[$count]);
                        } 
                    } else {
                        foreach ($all_results as $match) {
                            $dbresult[$count] = array (
                                'status'            => "Issue",
                                'scantype'          => $ref,
                                'time'              => time(),
                                'filename'          => $file,
                                'filetype'          => filetype($file),
                                'fileaccessed'      => fileatime($file),
                                'filechanged'       => filectime($file),
                                'filemodified'      => filemtime($file),
                                'filepermission'    => substr(decoct(fileperms($file)),2),
                                'issuecat'          => 'mal',
                                'issuename'         => $pattern,
                                'issuedesc'         => 'String '.$pattern,
                                'linenumber'        => $this->calculate_line_number($match[1], $content),
                                'issuecode'         => htmlentities(substr($content, $match[1], 200), ENT_QUOTES),
                                'tag'               => "security"
                            );
                            if ($this->checkExistIssue ($dbresult[$count]) == false) {
                            $issues[] = array (
                                'status'        => 'Issue',
                                'issuename'     => $pattern,
                                'issuecount'    => $total_results
                            );
                            } else {
                                
                            }
                            $this->insert_content ($dbresult[$count]);
                            
                        }
                    }
                }
            }
            if ($this->cronscan == false) {
                header('Content-type: application/json');
                echo json_encode($issues); 
                unset($content);
            } else {
                $this->sendCronMail ($issues);
            }
        }              
    }
    
    /*
     * @desc get md_5 hash of file on server and compare with last saved hash from same
     * @return json $issue empty if compared same else returning issues
     */
    function gethashes ($file, $ref ) 
    {
            $count = 0;
            $total_results = 0;
            $issuecount = 0;
            $issues = array();
            //Check for hash modifying
            $hash = $this->hashcheck ($file, $ref);
            if (!empty($hash)) {
                $total_results ++;
                $issues[] = array (
                    'status'        => 'Issue',
                    'issuename'     => $hash['issuename'],
                    'issuecount'    => $total_results
                );
            }
                if ($this->cronscan == false) {
                header('Content-type: application/json');
                echo json_encode($issues); 
                unset($content);      
                } else {
                    return $issues; 
                    unset($content);
                }
    }
    /* Checks issue db for existing errors
     * @param array $data using the htmlentities sanitazion
     * @return true if exists and false if not
     */
    function checkExistIssue ($data) 
    {
        global $xoopsDB;
            $test  = "SELECT * FROM ".$xoopsDB->prefix('xoopsecure_issues');
            $test .= " Where filename = '".htmlentities($data['filename'], ENT_QUOTES)."'";
            $test .= " AND linenumber = '".htmlentities($data['linenumber'], ENT_QUOTES)."'";
            $test .= " AND issuecat = '".htmlentities($data['issuecat'], ENT_QUOTES)."'";
            $test .= " AND inittime = '".$this->inittime."' ORDER BY id";
            $resulttest = $xoopsDB->queryF($test);
            $testcount = $xoopsDB->getRowsNum($resulttest);
            if ($testcount <= 0) {
                return false;
            } else {
                return true;
            }
    }
    
    /*
     * @desc inserts data to database issues
     * @param array $data the set array of the issue found in file content
     * @return void
     */
    function insert_content ($data) 
    {
        global $xoopsDB,$xoopsLogger;
        $xoopsLogger->activated = false;
        //error_reporting(E_ALL); 
        $sql = "INSERT INTO ".$xoopsDB->prefix('xoopsecure_issues'). "(
          scantype,
          time,
          inittime,
          filename,
          filetype,
          accessed,
          changed,
          modified,
          permission,
          issuecat,
          issuetype,
          issuedesc,
          linenumber,
          issuecode,
          tag) 
            VALUES (
                ".$data['scantype'].",
                ".$data['time'].",
                '".$this->inittime."',
                '".$data['filename']."',
                '".$data['filetype']."',
                '".$data['fileaccessed']."',
                '".$data['filechanged']."',
                '".$data['filemodified']."',
                '".$data['filepermission']."',
                '".$data['issuecat']."',
                '".htmlentities($data['issuename'], ENT_QUOTES)."',
                '".htmlentities($data['issuedesc'], ENT_QUOTES)."',
                '".$data['linenumber']."',
                '".htmlentities($data['issuecode'], ENT_QUOTES)."',
                '".$data['tag']."'
            )";
        if ($this->checkExistIssue ($data) == false) {
			$result = $xoopsDB->queryF($sql);
        }

    }
    
    /**
     * @desc Emties table xoopssecure_issues before initiating NEW full scan
     * @return void
     */
    function emptyIssues ()
    {
        global $xoopsDB;
        $sql = "TRUNCATE TABLE ".$xoopsDB->prefix('xoopsecure_issues')."";
        //$sql = "DELETE FROM ".$xoopsDB->prefix('xoopsecure_issues')." WHERE scantype = ".$this->scantype;
        $result = $xoopsDB->queryF($sql);
    }
	
	/**
	 * Removes issues from db when a scan previously was done (last 1 day)
	 *
	 * @return void
	 */ 
	function rmTodaysData ()
	{
		global $xoopsDB;
		$tdm = strtotime('today midnight');
		$tmm = strtotime('tomorrow midnight');
		$sql = "DELETE FROM ".$xoopsDB->prefix("xoopsecure_stats")." WHERE 'date' BETWEEN ".$tdm." AND ".$tmm."";
        $result = $xoopsDB->queryF($sql);
	}

	/**
	 * check if stats from db when of a  scan previously was done (last 1 day)
	 *
	 * @return false or true
	 */ 
	function checkTodaysStats ()
	{
		global $xoopsDB;
		$tdm = strtotime('today midnight');
		$tmm = strtotime('tomorrow midnight');
		$sql = "SELECT 'date' FROM ".$xoopsDB->prefix("xoopsecure_stats")." WHERE 'date' BETWEEN ".$tdm." AND ".$tmm."";
        $result = $xoopsDB->queryF($sql);
		$count = $xoopsDB->getRowsNum($resulttest);
		return ($count > 0) ? true : false;
	}
	
	/**
	 * Insert stats to stats
	 * @param timestamp date of scan 
	 * @param array issuenr issues this day
	 * @param array badusers users with bad ip
	 * @return void
	 */
	 function doStats ($inittime, $issues, $badusers)
	 {
		 global $xoopsDB;
		 if ($self::checkTodaysStats () == true) {
			 $sql = "UPDATE ".$xoopsDB->prefix('xoopsecure_stats'). " SET 'date' = {$inittime},
                issuenr = ".count($issues).", issues = ".serialize($issues).", badusers = ".serialize($badusers)."
				WHERE 'date' BETWEEN ".strtotime('today midnight')." AND ".strtotime('tomorrow midnight');
		 } else {
		 }
	 }
	 
	 
    /**
     * @desc Emties table xoopssecure_files before initiating NEW full scan
     * @return void
     */
    function emptyFiles ()
    {
        global $xoopsDB;
        $sql = "TRUNCATE TABLE ".$xoopsDB->prefix('xoopsecure_files')."";
        $result = $xoopsDB->queryF($sql);
    }
    
    /**
     * Calculates the line number where pattern match was found
     *
     * @param int $offset The offset position of found pattern match
     * @param str $content The file content in string format
     * @return int Returns line number where the subject code was found
     */
    function calculate_line_number($offset, $file_content) 
    {
        $file_content = htmlentities($file_content, ENT_QUOTES);
        list($first_part) = str_split($file_content, $offset); // fetches all the text before the match
        $line_nr = strlen($first_part) - strlen(str_replace("\n", "", $first_part)) + 1;
        return $line_nr;
    }

   /**
    * Saves ALL file-info to db
    * 
    * @param string $file the url to the file in question
    * @return void
    */
       
    function savefileinfo ($file)
    {
        global $xoopsDB;
        $file = xoopssecure_removequot ($file);       
        $checkfile = "SELECT * FROM ".$xoopsDB->prefix('xoopsecure_files')." WHERE filename = '".$file."'";
        $resp = $xoopsDB->queryF($checkfile);
        $count = $xoopsDB->getRowsNum($resp);
        if ($count <= 0) {
           $sql = "INSERT INTO ".$xoopsDB->prefix('xoopsecure_files'). "(
                filename,
                filesize,
                lastdate,
                hashvalue) 
                    VALUES (
                        '".$file."',
                        ".filesize($file).",
                        ".filectime($file).",
                        '".hash_file('md5', $file)."'
                    )";
            $result = $xoopsDB->queryF($sql);
        }
    }
    
    /**
     * Check db hash agains file hash and inser into issues if different
     * 
     * @param string $file the url to the file on server
     * @param intval $ref the scan type reference number
     *
     * @return json $arr containing values if difference check is true
     */
    
    function hashcheck ($file, $ref)
    {
        global $xoopsDB;
        $file = xoopssecure_removequot ($file);     
        $arr = array();
        $checkfile = "SELECT * FROM ".$xoopsDB->prefix('xoopsecure_files')." WHERE filename = '".$file."'";
        $resp = $xoopsDB->queryF($checkfile);
        $count = $xoopsDB->getRowsNum($resp);
        while ($row = $xoopsDB->fetchArray($resp)) { 
            $hash = ($row['hashvalue'] == hash_file('md5', $file)) ? true : false;
            if ($hash == false) {                  
                 $codestring = _AM_XOOPSSECURE_HASHCHEGEDVALUES;                    
                 $filename = htmlentities($file, ENT_QUOTES);
                 $fileext = filetype($file);
                 $fileatime = intval(fileatime($file));
                 $filemtime = intval(filemtime($file));
                 $filectime = intval(filectime($file));
                 $fileperm = substr(sprintf('%o', fileperms($file)), -4);
                 $filehash = hash_file('md5', $file);
                 $filecat  = "hash_change";
                 $filetype = htmlentities(_AM_XOOPSSECURE_SCANHASHCHANGEDFILE, ENT_QUOTES);
                 $filedesc = htmlentities(_AM_XOOPSSECURE_SCANHASHCHANGEDFILE_DESC, ENT_QUOTES);
                 $filecode = sprintf($codestring, $row['hashvalue'], $filehash, $row['filesize'],filesize($file));
                 $filetag  = htmlentities('security', ENT_QUOTES);
                
                $issuecheck = "SELECT filename FROM ".$xoopsDB->prefix('xoopsecure_issues'). " WHERE filename = '".$file."'";
                $issuecheck_resp = $xoopsDB->queryF($issuecheck);
                $issuecheck_count = $xoopsDB->getRowsNum($issuecheck_resp);
                
                $arr = array (
                    'status'            => "Issue",
                    'scantype'          => $ref,
                    'time'              => time(),
                    'filename'          => $file,
                    'filetype'          => filetype($file),
                    'fileaccessed'      => fileatime($file),
                    'filechanged'       => filectime($file),
                    'filemodified'      => filemtime($file),
                    'filepermission'    => substr(decoct(fileperms($file)),2),
                    'issuecat'          => 'hash-change',
                    'issuename'         => _AM_XOOPSSECURE_SCANHASHCHANGEDFILE,
                    'issuedesc'         => _AM_XOOPSSECURE_SCANHASHCHANGEDFILE_DESC,
                    'linenumber'        => '',
                    'issuecode'         => sprintf($codestring, $row['hashvalue'], $filehash, $row['filesize'],filesize($file)),
                    'tag'               => "security"
                );
                                
                if (!$issuecheck_count || $issuecheck_resp <= 0) {
                        $this->insert_content ($arr);
                        $result = $xoopsDB->queryF($sql);
                } else {
                    $sql = "UPDATE ".$xoopsDB->prefix('xoopsecure_issues'). " SET 
                            scantype = ".$this->scantype.", filename = '".$filename."',
                            filetype =  '".$fileext."', accessed = ".$fileatime.",
                            changed = ".$filemtime.", modified = ".$filectime.",
                            permission = ".$fileperm.", issuecat = '".$filecat."',
                            issuetype = '".$filetype."', issuedesc = '".$filedesc."',
                            issuecode = '".$filecode."', tag = '".$filetag."' 
                            WHERE filename = '".$file."'";
                        $result = $xoopsDB->queryF($sql);
                }
            }
            return $arr;
        }    
    }
    
    /**
     * @desc Automaticly create index file index.html if index.php or index.html not exists
     * @param string $dir the full url to dir being checked
     * Return array $issues where issue is set if index file is not found
     */
    function createindexfiles($dir)
    {
        $result = array();
        $arr = array();
        $url = xoopssecure_removequot ($dir);
        $orgch = substr(sprintf('%o', fileperms($url)), -4);
        
        if ($this->indexfiletype == 1 xor $this->indexfiletype == 2) {
            if (is_dir($url)) {
                if (!is_writable($url)) {    
                    chmod($url, 0777);
                    $this->createindexfiles($url);
                }
                if (!file_exists($url.'/index.html') && !file_exists($url.'/index.htm') && 
                    !file_exists($url.'/index.php') && !file_exists($url.'/index.xhtml')) {
                    //str_replace ("\\", "/", $Path)
                    file_put_contents( $url . '/index.html' , 
                        $this->contentofhtmlindex);
                    // SAVE INFO TO DB          
                    $count = 0;
                    $total_results = 0;
                    $issuecount = 0;
                    $issues = array();
                    $total_results ++;
                    $issues[] = array (
                        'status'        => 'Issue',
                        'issuename'     => 'missing-indexfile',
                        'issuecount'    => $total_results
                    );
                    $arr = array (
                        'status'            => "Issue",
                        'scantype'          => 3,
                        'time'              => time(),
                        'filename'          => str_replace ("\\", "/", $url),
                        'filetype'          => filetype($url),
                        'fileaccessed'      => fileatime($url),
                        'filechanged'       => filectime($url),
                        'filemodified'      => filemtime($url),
                        'filepermission'    => substr(decoct(fileperms($url)),2),
                        'issuecat'          => 'missing-indexfile-html',
                        'issuename'         => _AM_XOOPSSECURE_MISSINGHTMLINDEXFILE,
                        'issuedesc'         => _AM_XOOPSSECURE_MISSINGHTMLINDEXFILE_DESC,
                        'linenumber'        => '',
                        'issuecode'         => '',
                        'tag'               => "security"
                    );                           
                    
                    (!empty($arr)) ? $this->insert_content ($arr):'';
                    header('Content-type: application/json');
                    echo json_encode($issues); 
                    unset($content); 
                    chmod($url, $orgch);                    
                }
            }
        }
    }
 
    function checkMissingIndexfile($dir)
    {
        $result = array();
        $arr = array();
        $url = xoopssecure_removequot ($dir);
        $orgch = substr(sprintf('%o', fileperms($url)), -4);    
        if (is_dir($url)) {
            if (!is_writable($url)) {    
                chmod($url, 0777);
                $this->checkMissingIndexfile($url);
            }
            if (!file_exists($url.'/index.html') && !file_exists($url.'/index.htm') &&
                !file_exists($url.'/index.php') && !file_exists($url.'/index.xhtml') &&
                !file_exists($url.'/.htaccess')) {
                //str_replace ("\\", "/", $Path)
                
                // SAVE INFO TO DB 
                
                $count = 0;
                $total_results = 0;
                $issuecount = 0;
                $issues = array();
                $total_results ++;
                $issues[] = array (
                    'status'        => 'Issue',
                    'issuename'     => 'missing-indexfile',
                    'issuecount'    => $total_results
                );
                $arr = array (
                    'status'            => "Issue",
                    'scantype'          => 11,
                    'time'              => time(),
                    'filename'          => str_replace ("\\", "/", $url),
                    'filetype'          => filetype($url),
                    'fileaccessed'      => fileatime($url),
                    'filechanged'       => filectime($url),
                    'filemodified'      => filemtime($url),
                    'filepermission'    => substr(decoct(fileperms($url)),2),
                    'issuecat'          => 'missing-indexfile',
                    'issuename'         => _AM_XOOPSSECURE_MISSINGANYINDEXFILE,
                    'issuedesc'         => _AM_XOOPSSECURE_MISSINGANYINDEXFILE_DESC,
                    'linenumber'        => '',
                    'issuecode'         => '',
                    'tag'               => "security"
                );                           
                
                if ($this->checkExistIssue ($arr) == false) { 
                    (!empty($arr)) ? $this->insert_content ($arr):'';
                    header('Content-type: application/json');
                    echo json_encode($issues); 
                } else {
                     header('Content-type: application/json');
                    echo json_encode(array()); 
                }
               
                unset($content); 
                chmod($url, $orgch);                    
            }
        }
    }
 
    /**
     * @desc Automaticly create file .htaccess if not existing
     * @param string $dir the full url to dir being scanned
     * Return array $issues set if file found missing else empty
     */
    function createHttaccess($dir)
    {
        if (true == xoopssecure_apachemodule('mod_rewrite')) {
            $result = array();
            $arr = array();
            $url = xoopssecure_removequot ($dir);
            $orgch = substr(sprintf('%o', fileperms($url)), -4);
            
            if ($this->autoindexcreate != 0 && $this->indexfiletype == 0 xor $this->indexfiletype == 2) {
                if (is_dir($url)) {
                    if (!is_writable($url)) {    
                        chmod($url, 0777);
                        $this->createHttaccess($url);
                    }
                    if (!file_exists($url.'/.htaccess')) {
                        file_put_contents( $url . '/.htaccess' , 
                            $this->contentofhtaccessindex);         
                        $count = 0;
                        $total_results = 0;
                        $issuecount = 0;
                        $issues = array();
                        $total_results ++;
                        $issues[] = array (
                            'status'        => 'Issue',
                            'issuename'     => 'missing-httaccess',
                            'issuecount'    => $total_results
                        );
                        $arr = array (
                            'status'            => "Issue",
                            'scantype'          => 7,
                            'time'              => time(),
                            'filename'          => str_replace ("\\", "/", $url),
                            'filetype'          => filetype($url),
                            'fileaccessed'      => fileatime($url),
                            'filechanged'       => filectime($url),
                            'filemodified'      => filemtime($url),
                            'filepermission'    => substr(decoct(fileperms($url)),2),
                            'issuecat'          => 'missing-indexfile-htaccess',
                            'issuename'         => _AM_XOOPSSECURE_MISSINGHTTACCESS,
                            'issuedesc'         => _AM_XOOPSSECURE_MISSINGHTTACCESS_DESC,
                            'linenumber'        => '',
                            'issuecode'         => '',
                            'tag'               => "security"
                        );                           
                        
                        (!empty($arr)) ? $this->insert_content ($arr):'';
                        header('Content-type: application/json');
                        echo json_encode($issues); 
                        unset($content); 
                        chmod($url, $orgch);                    
                    }
                }
            } 
        }
    }
 
    /**
      Chmods files and folders with different permissions.

      @param $path An either relative or absolute path to a file or directory which should be processed.
      @param $filePerm The permissions any found files should get.
      @param $dirPerm The permissions any found folder should get.
      @param $ex_dir Skip standard permissions for these and set dir permission to 0777 (unix)
      @return Returns TRUE if the path if found and FALSE if not.
      @warning The permission levels has to be entered in octal format, which normally means adding a zero ("0") in front of the permission level.
    */

    function recursiveChmod ($path, $ref) {
        $ex_dir = $this->xoops_reservedfolders;
        $path = xoopssecure_removequot ($path);
        $count = 0;
        $total_results = 0;
        $issuecount = 0;
        $arr = array();
        $issues = array();
        // Check if the path exists
        if (!file_exists($path)) {
            return(false);
        }
        // See whether this is a file
        if (is_file($path)) {
            // Chmod the file with our given filepermissions
            if (in_array($path, $this->xoops_reservedfolders)) {                
                $arr = array (
                    'status'            => "Issue",
                    'scantype'          => $ref,
                    'time'              => time(),
                    'filename'          => $path,
                    'filetype'          => filetype($path),
                    'fileaccessed'      => fileatime($path),
                    'filechanged'       => filectime($path),
                    'filemodified'      => filemtime($path),
                    'filepermission'    => substr(decoct(fileperms($path)),2),
                    'issuecat'          => 'chmod',
                    'issuename'         => _AM_XOOPSSECURE_CHMOD,
                    'issuedesc'         => _AM_XOOPSSECURE_CHMOD_DESC,
                    'linenumber'        => '',
                    'issuecode'         => '',
                    'tag'               => "security"
                    );                           
                    
                (!empty($arr)) ? $this->insert_content ($arr):'';
                
                chmod($path, 0444);
                $total_results ++;
                $issues[] = array (
                    'status'        => 'Issue',
                    'issuename'     => _AM_XOOPSSECURE_CHMODSPECIALFILE,
                    'issuecount'    => $total_results
                ); 
            } else {
                $arr = array (
                    'status'            => "Issue",
                    'scantype'          => $ref,
                    'time'              => time(),
                    'filename'          => $path,
                    'filetype'          => filetype($path),
                    'fileaccessed'      => fileatime($path),
                    'filechanged'       => filectime($path),
                    'filemodified'      => filemtime($path),
                    'filepermission'    => substr(decoct(fileperms($path)),2),
                    'issuecat'          => 'chmod',
                    'issuename'         => _AM_XOOPSSECURE_CHMOD,
                    'issuedesc'         => _AM_XOOPSSECURE_CHMOD_DESC,
                    'linenumber'        => '',
                    'issuecode'         => '',
                    'tag'               => "security"
                    );                           
                    
                (!empty($arr)) ? $this->insert_content ($arr):'';
            
                chmod($path, 0644);
                $total_results ++;
                $issues[] = array (
                    'status'        => 'Issue',
                    'issuename'     => _AM_XOOPSSECURE_CHMODFILE,
                    'issuecount'    => $total_results
                );  
            }
        // If this is a directory...
        } elseif (is_dir($path)) {        
            if (!in_array($path, $this->xoops_reservedfolders)) {
                
                $arr = array (
                    'status'            => "Issue",
                    'scantype'          => $ref,
                    'time'              => time(),
                    'filename'          => $path,
                    'filetype'          => filetype($path),
                    'fileaccessed'      => fileatime($path),
                    'filechanged'       => filectime($path),
                    'filemodified'      => filemtime($path),
                    'filepermission'    => substr(decoct(fileperms($path)),2),
                    'issuecat'          => 'chmod',
                    'issuename'         => _AM_XOOPSSECURE_CHMOD,
                    'issuedesc'         => _AM_XOOPSSECURE_CHMOD_DESC,
                    'linenumber'        => '',
                    'issuecode'         => '',
                    'tag'               => "security"
                    );                           
                    
                (!empty($arr)) ? $this->insert_content ($arr):'';
                
                chmod($path, 0755);
                $total_results ++;
                $issues[] = array (
                    'status'        => 'Issue',
                    'issuename'     => _AM_XOOPSSECURE_CHMODDIR,
                    'issuecount'    => $total_results
                );              
            } else {
                
                $arr = array (
                    'status'            => "Issue",
                    'scantype'          => $ref,
                    'time'              => time(),
                    'filename'          => $path,
                    'filetype'          => filetype($path),
                    'fileaccessed'      => fileatime($path),
                    'filechanged'       => filectime($path),
                    'filemodified'      => filemtime($path),
                    'filepermission'    => substr(decoct(fileperms($path)),2),
                    'issuecat'          => 'chmod',
                    'issuename'         => _AM_XOOPSSECURE_CHMOD,
                    'issuedesc'         => _AM_XOOPSSECURE_CHMOD_DESC,
                    'linenumber'        => '',
                    'issuecode'         => '',
                    'tag'               => "security"
                    );                           
                    
                (!empty($arr)) ? $this->insert_content ($arr):'';
                
                chmod($path, 0777);
                $total_results ++;
                $issues[] = array (
                    'status'        => 'Issue',
                    'issuename'     => _AM_XOOPSSECURE_CHMODSPECIALDIR,
                    'issuecount'    => $total_results
                ); 
            }      
        }
        //header("content-type: application/json; charset=utf-8");
        return json_encode($issues); 
    }
     
    /**
     *
     * Return pattern to search for in files based on static array + xoopsConfig value extended array values
     *
     * @return array $patternAll the extended array of values to search for
     *
     */    
    function getPatterns ()
    {
        /*
         * @desc Pattern to search for
         * @return array $patternAll an merged array of bad function names, bad boy names and regex patterns
         *
         * @Credit "JAMSS - Joomla! Anti-Malware Scan Script V1.0.5 by Bernard Toplak (http://www.orion-web.hr)"
         *
         */
        $patternsAll = array_merge($this->badfuncs, $this->badboys, $this->mallwarepatterns());
        return $patternsAll;
    }
    
    /**
     * @desc An array of potential bad strings to search for
     * @return array $ss the words
     */
    function malwareWordstrings ()
    {
        $ss = Array (
            'eval',
            'base64_decode',
            'base64_encode',
            'gzdecode',
            'gzdeflate',
            'gzuncompress',
            'gzcompress',
            'readgzfile',
            'zlib_decode',
            'zlib_encode',
            'gzfile',
            'gzget',
            'gzpassthru',
            'iframe',
            'strrev',
            'lzw_decompress',
            'strtrexec',
            'passthru',
            'shell_exec',
            //'system',
            'proc_',
            'popen'
        );
        return $ss;
    }
    
    /**
     * @desc Array of potential bad words to search for
     * @return array $mallwareStrings the array of words
     */
     function mallwareStrings ()
     {
        $mallwareStrings = array (        
            'r0nin',
            'm0rtix',
            'upl0ad',
            'r57shell',
            'c99shell',
            'shellbot',
            'phpshell',
            'void\.ru',
            'grynaprojekt\.cba\.pl',
            'phpremoteview',
            'directmail',
            'bash_history',
            'multiviews',
            'cwings',
            'vandal',
            'bitchx',
            'eggdrop',
            'guardservices',
            'psybnc',
            'dalnet',
            'undernet',
            'vulnscan',
            'spymeta',
            'raslan58',
            'Webshell',
            'str_rot13',
            'FilesMan',
            'FilesTools',
            'Web Shell',
            'ifrm',
            'bckdrprm',
            'hackmeplz',
            'wrgggthhd',
            'WSOsetcookie',
            'Hmei7',
            'Inbox Mass Mailer',
            'HackTeam',
            'HackeadoJanissaries',
            'Miyachung',
            'ccteam',
            'Adminer',
            'OOO000000',
            '$GLOBALS',
            'findsysfolder'      
        );
        return $mallwareStrings;
     }
    
    /**
     * @desc Array of regex patterns to look for in file content
     * @retuen array $mp
     */  
    function mallwarepatterns ()
    {
        $mp = array(
            array('preg_replace\s*\(\s*[\"\']\s*(\W)(?-s).*\1[imsxADSUXJu\s]
                    *e[imsxADSUXJu\s]*[\"\'].*\)', // [0] = RegEx search pattern
                _AM_XOOPSSECURE_SECISSUE01_TITLE, // [1] = Name / Title
                1, // [2] = number
                _AM_XOOPSSECURE_SECISSUE01_DESC, // [3] = description
                ''), // [4] = More Information link
            array('c999*sh_surl',
                _AM_XOOPSSECURE_SECISSUE02_TITLE,
                2,
                _AM_XOOPSSECURE_SECISSUE02_DESC,
                ''),
            array('preg_match\s*\(\s*\"\s*/\s*bot\s*/\s*\"',
                _AM_XOOPSSECURE_SECISSUE03_TITLE,
                3,
                _AM_XOOPSSECURE_SECISSUE03_DESC,
                ''),
            array('eval[\s/\*\#]*\(stripslashes[\s/\*\#]*\([\s/\*\#]*
                    \$_(REQUEST|POST|GET)\s*\[\s*\\\s*[\'\"]\s*asc\s*\\\s*[\'\"]',
                _AM_XOOPSSECURE_SECISSUE05_TITLE,
                5,
                _AM_XOOPSSECURE_SECISSUE05_DESC,
                ''),
            array('preg_replace\s*\(\s*[\"\'\”]\s*/\s*\.\s*\*
                    \s*/\s*e\s*[\"\'\”]\s*,\s*[\"\'\”]
                    \s*\\x65\\x76\\x61\\x6c',
                _AM_XOOPSSECURE_SECISSUE07_TITLE,
                7,
                _AM_XOOPSSECURE_SECISSUE07_DESC,
                ''),
            array('(include|require)(_once)*\s*[\"\']
                    [\w\W\s/\*]*php://input[\w\W\s/\*]*[\"\']',
                _AM_XOOPSSECURE_SECISSUE08_TITLE,
                8,
                _AM_XOOPSSECURE_SECISSUE08_DESC),
            array('data:;base64',
                _AM_XOOPSSECURE_SECISSUE09_TITLE,
                9,
                _AM_XOOPSSECURE_SECISSUE09_DESC),
            array('RewriteCond\s*%\{HTTP_REFERER\}',
                _AM_XOOPSSECURE_SECISSUE10_TITLE,
                10,
                _AM_XOOPSSECURE_SECISSUE10_DESC),
            array('brute\s*force',
                _AM_XOOPSSECURE_SECISSUE11_TITLE,
                11,
                _AM_XOOPSSECURE_SECISSUE11_DESC),
            array('GIF89a.*[\r\n]*.*<\?php',
                _AM_XOOPSSECURE_SECISSUE15_TITLE,
                15,
                _AM_XOOPSSECURE_SECISSUE15_DESC),
            array('\$ip[\w\W\s/\*]*=[\w\W\s/\*]*getenv\(["\']REMOTE_ADDR["\']\);
                    [\w\W\s/\*]*[\r\n]\$message',
                _AM_XOOPSSECURE_SECISSUE16_TITLE,
                16,
                _AM_XOOPSSECURE_SECISSUE16_DESC),
            array('(?:(?:eval|gzuncompress|gzinflate|base64_decode|str_rot13|strrev
                    |strtr|preg_replace|rawurldecode|str_replace|assert|unpack|urldecode)
                    [\s/\*\w\W\(]*){2,}',
                _AM_XOOPSSECURE_SECISSUE17_TITLE,
                17,
                _AM_XOOPSSECURE_SECISSUE17_DESC),
            array('<\s*iframe',
                _AM_XOOPSSECURE_SECISSUE18_TITLE,
                18,
                _AM_XOOPSSECURE_SECISSUE18_DESC),
            array('strrev[\s/\*\#]*\([\s/\*\#]*[\'"]\s*tressa\s*[\'"]\s*\)',
                _AM_XOOPSSECURE_SECISSUE19_TITLE,
                19,
                _AM_XOOPSSECURE_SECISSUE19_DESC),
            array('is_writable[\s/\*\#]*\([\s/\*\#]*getcwd',
                _AM_XOOPSSECURE_SECISSUE20_TITLE,
                20,
                _AM_XOOPSSECURE_SECISSUE20_DESC),
            array('(?:\\\\x[0-9A-Fa-f]{1,2}|\\\\[0-7]{1,3}){2,}',
                _AM_XOOPSSECURE_SECISSUE21_TITLE,
                21,
                _AM_XOOPSSECURE_SECISSUE21_DESC),
            array('\$_F\s*=\s*__FILE__\s*;\s*\$_X\s*=',
                _AM_XOOPSSECURE_SECISSUE22_TITLE,
                22,
                _AM_XOOPSSECURE_SECISSUE22_DESC),
            array('(?:exec|passthru|shell_exec|system|proc_|popen)
                    [\w\W\s/\*]*\([\s/\*\#\'\"\w\W\-\_]*(?:\$_GET|\$_POST)',
                _AM_XOOPSSECURE_SECISSUE23_TITLE,
                23,
                _AM_XOOPSSECURE_SECISSUE23_DESC),
            /*array('\$\w[\w\W\s/\*]*=[\w\W\s/\*]*`.*`',
                _AM_XOOPSSECURE_SECISSUE24_TITLE,
                24,
                _AM_XOOPSSECURE_SECISSUE24_DESC),
            */    
            array("system(\s*)\(/",
                _AM_XOOPSSECURE_SECISSUE25_TITLE,
                25,
                _AM_XOOPSSECURE_SECISSUE25_DESC),
            
            array("(rebots\.php|flashplayer\.php)",
                _AM_XOOPSSECURE_SECISSUE26_TITLE,
                26,
                _AM_XOOPSSECURE_SECISSUE26_DESC),
            
            array("ShellBOT",
                _AM_XOOPSSECURE_SECISSUE27_TITLE,
                27,
                _AM_XOOPSSECURE_SECISSUE27_DESC)
            );
            
        return $mp;
    }
    
    /*
     * @desc check server for apache module
     * $param string $val the name of apache module to look for
     * @return array $trn the array of apache module where 
     *  exists = true/false and value = apache module setting
     */
    function getApacheModules ($val)
    {
        $apachemod = apache_get_modules();
        $rtn = array();
        $rtn['exists'] = false;
        $trn['value'] = '';
        if (in_array($val,$apachemod)) {
            $rtn['exists'] = true;
            $trn['value'] = array_search($val, $apachemod);
        } 
        return $trn;
    }
    
    /*
     * Delete issue from db
     * @param intval id of the issue to delete
     * @return void
     */
    function deleteById($id, $table)
    {
        global $xoopsDB;
        $sql = "DELETE FROM ".$xoopsDB->prefix("xoopsecure_".$table)." WHERE id = '".$id."'";
        $result = $xoopsDB->queryF($sql);
    }
    
    /*
     * Delete issue from db
     * @param intval id of the issue to delete
     * @return void
     */
    function Ignore($file, $linenumber)
    {
        global $xoopsDB;
        $file = xoopssecure_removequot ($file);
        $sql = "UPDATE ".$xoopsDB->prefix('xoopsecure_issues'). " SET ignored = '1'
                WHERE filename = '".$file."' AND linenumber = '".$linenumber."'";
        $result = $xoopsDB->queryF($sql);
    }
    
    /*
     * Inserts file or dir into ignore this table
     * @papam string $file is the filename / url 
     * @param string $type = file or dir
     * @param string $val = ignore or chmod
     */
    function ignoreFile ($file, $type, $val)
    {
        global $xoopsDB;
        xoopssecure_rmChildren ($file, $val);
        $file = xoopssecure_removequot ($file);
        $test = $this->checkIgnoreExists ($file, $type, $val);
        if ($type == 'dir' && !is_file($file)) {
            $isdir = 1;
            $isfile = 0;
        } else {
            $isdir = 0;
            $isfile = 1;
        }
        if (xoopssecure_isfolderonlist ($file, $val) !== true) {
            if (xoopssecure_relToAbsUrlCheck ($file) != xoopssecure_relToAbsUrlCheck ($this->urlToScan."/")) {
                if ($test == false) {
                    $sql = "INSERT INTO ".$xoopsDB->prefix('xoopsecure_ignores'). "(
                        url,
                        isfile,
                        isdir,
                        val)
                         VALUES (
                            '".$file."',
                            '".$isfile."',
                            '".$isdir."',
                            '".$val."'
                        )";
                    $result = $xoopsDB->queryF($sql);    
                } else {
                    $err = sprintf (_AM_XOOPSSECURE_ALREADYONLIST, $file);
                    header($_SERVER["SERVER_PROTOCOL"]." 404 {$err}"); 
                }
            } else {
                $err = sprintf (_AM_XOOPSSECURE_DROPURLISSAMEASSTART, $file);
                header($_SERVER["SERVER_PROTOCOL"]." 404 {$err}"); 
            }
        } else {
                $err = sprintf (_AM_XOOPSSECURE_DROPURLISPARTOFOTHER, $file);
                header($_SERVER["SERVER_PROTOCOL"]." 404 {$err}"); 
        }
    }
    
    /**
    * @param string $val = ignore or chmod
    */
    function checkIgnoreExists ($file, $type, $val)
    {
        global $xoopsDB;
        $test = "SELECT * FROM ".$xoopsDB->prefix("xoopsecure_ignores").
                " WHERE url = '".$file."' AND val = '".$val."'";
        $testresult = $xoopsDB->queryF($test);
        return ($xoopsDB->getRowsNum($testresult) > 0) ? true:false;
    }
    
    /*
     * Get array of ignore items to display in container
     * @param string $genus is the type of ignore (dir / file)
     * @param string $val is the definition of ignore (chmod, ignore)
     * $return array $array of items
     */
    function getIgnores ($genus, $val) 
    {
        global $xoopsDB;
        $html = "";
        if ($genus == 'dir') {
            $isdir = 1;
            $isfile = 0;
        } else {
            $isdir = 0;
            $isfile = 1;
        }
        $sql = "SELECT * FROM ".$xoopsDB->prefix('xoopsecure_ignores').
            " WHERE isdir = '".$isdir."' AND isfile = '".$isfile."' AND".
            " val = '".$val."'";
        $result = $xoopsDB->queryF($sql);
        while ($row = $xoopsDB->fetchArray($result)) {                
             $html .= '<li>
                <a  href="#" 
                    id = "xoopssecure_deleteissue"
                    data-id = '.$row['id'].'
                    data-fn = '.$row['url'].'
                    data-ln = ""
                    data-what = "8"
                    data-table = "ignores"
                >
                    '.$row['url'].'
                    <img alt="'._AM_XOOPSSECURE_DELETEISSUE_DESC.'"
                    src="../images/delete.png" 
                />
                </a>
            </li>';    
                
                
        }
        echo $html;
    }
    
    /*
     * Get array of desired ignore files
     * @param string $genus is the type of files to get (ignore, chmod, issue)
     * @param string $type ('isdir' or 'isfile')
     * @return array $data
     */
    function getIgnoreArray ($genus, $type)
    {
        global $xoopsDB;
        $sql = "SELECT * FROM ".$xoopsDB->prefix('xoopsecure_ignores').
            " Where val = '".$genus."' AND ".$type." = '1' ORDER by url ASC";
        $result = $xoopsDB->queryF($sql);
        while ($r = $xoopsDB->fetchArray($result)) {
            $data[] = $r;
        }
        if (!empty($data)) {
            return $data;
        } else {
            return array();
        }
    }
    
    
    function getIgnoreArrayToVar ($genus, $type)
    {
        global $xoopsDB;
        $sql = "SELECT url FROM ".$xoopsDB->prefix('xoopsecure_ignores').
            " Where val = '".$genus."' AND ".$type." = '1' ORDER by url ASC";
        $result = $xoopsDB->queryF($sql);
        while ($r = $xoopsDB->fetchArray($result)) {
            $data[] = xoopssecure_removequot(xoopssecure_cleanUrl($r['url']));
        }
        if (!empty($data)) {
            return $data;
        } else {
            return array();
        }        
    }
    
    function xoopssecure_fullscan_hasFiles() 
    {
        global $xoopsDB;
        $sql = "SELECT * FROM " . $xoopsDB->prefix('xoopsecure_files');
        $result = $xoopsDB->queryF($sql);
        return ($xoopsDB->getRowsNum($result) > 0) ? 1:0;
    }
    
    function xoopssecure_dbHasMallIssues()
    {
        global $xoopsDB;
        $sql = "SELECT * FROM " . $xoopsDB->prefix('xoopsecure_issues');
        $result = $xoopsDB->queryF($sql);
        return ($xoopsDB->getRowsNum($result) > 0) ? 1:0;
    }
    
    function sendCronMail ($info) {
        global $xoopsConfig, $xoopsUser;      
		$date = date('m-d-Y H:i:s',time());
		$mail = new XoopsMultiMailer;
		$tpl = new XoopsTpl();
		$message = '';
		
        $from = $xoopsConfig['adminmail'];
        $to = $xoopsConfig['adminmail'];
        	
        $subject = _XOOPSSECURE_MAIL_FROM." - ".$xoopsConfig['sitename'];

        $time = date(xoopssecure_GetModuleOption('dateformat'),$data['time']);
        $link = XOOPS_ROOT_PATH .'/modules/xoopsSecure/admin/showlog.php';

        $tpl = new XoopsTpl();
        $tpl->assign('sendername',_XOOPSSECURE_MAIL_SENDERNAME);
        $tpl->assign('time',$time);
        $tpl->assign('link',$link);
        $tpl->assign('sitename',$xoopsConfig['sitename']);
        $tpl->assign('issues',$info);
        
        $lnk = XOOPS_ROOT_PATH
            .'/modules/xoopsSecure/language/'
            .$xoopsConfig['language']
            .'/mailTpl/mail_cron.html';
            
        $message = $tpl->fetch($lnk);
        $mail->Body = $message;
        $toMail = $xoopsConfig['adminmail'];
		
		$mail->IsMail();
		$mail->IsHTML(true);
		$mail->AddAddress($to);
		$mail->Subject = $subject;
		
		
		if(!$mail->Send())
		{}
		else {
		}	
	}

}