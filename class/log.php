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
include_once (XOOPS_ROOT_PATH.'/class/template.php');
include_once XOOPS_ROOT_PATH . '/modules/xoopsSecure/class/scan.php';
set_time_limit(999999);
if (!isset($xoopsTpl)) {
    $xoopsTpl = new XoopsTpl();
}
    $scan = new xoopsSecure_scan;

class xoopsSecure_log {    
    var $filenameArray;
    var $userdatetype;
    var $singleFilesIgnore;
    var $DirIgnore;
    var $DbHasFiles = false;
    var $dbHasMallIssues;
        
    public function __construct(){
        global $scan;
        $this->userdatetype = xoopssecure_GetModuleOption('dateformat');
        $this->singleFilesIgnore = $scan->getIgnoreArray ('ignore', 'isfile');
        $this->DirIgnore= $scan->getIgnoreArray ('ignore', 'isdir');
        $this->DbHasFiles = $this->dbHasFiles ();
        $this->dbHasMallIssues = $scan->xoopssecure_dbHasMallIssues();
    }

    /*
     * @desc 
     * @return 
     */
     function getIssues ($filetype) 
     {
        global $xoopsDB, $xoopsTpl, $xoopsTheme, $scan;
            foreach ($this->singleFilesIgnore as $r) {
                $sfi[] = "'".$r['url']."'";
            }
            
            $query  = "SELECT * FROM ".$xoopsDB->prefix('xoopsecure_issues')." WHERE filetype = '".$filetype."'";
                if (!empty($sfi)) {
                $query  .= " AND filename NOT in (".implode(",", xoopssecure_flatten($sfi)).")";
                }
                $query  .= " AND ignored = 0 GROUP BY filename";
            $result = $xoopsDB->queryF($query);
            $count = $xoopsDB->getRowsNum($result);
            $arr = array();
            if ($count != 0) {
                while ($row = $xoopsDB->fetchArray($result)) {
                    $data[] = $row;
                }
                foreach ($data as $r) { 

                    $arr['id'] = $r['id'];
                    $arr['scantype'] = $r['scantype'];
                    $arr['time'] = $this->getdatetime ($r['scantype']);
                    $arr['filename'] = $r['filename'];
                    $arr['dirname'] = dirname($r['filename']);
                    $arr['fileicon']  = $r['filename'];
                    $arr['diricon']  = dirname($r['filename']);
                    $arr['filetype'] = $r['filetype'];
                    $arr['shortfilename'] = basename($r['filename']);
                    $arr['accessed'] = ($r['accessed'] != '') ? date($this->userdatetype, $r['accessed']):'';
                    $arr['changed'] = ($r['changed'] != '') ? date($this->userdatetype, $r['changed']):'';
                    $arr['modified'] = ($r['modified'] != '') ? date($this->userdatetype, $r['modified']):'';
                    $arr['permission'] = ($r['permission'] != '') ? sprintf("%04s", $r['permission']):'';
                    $arr['issuearray'] = $this->Issues($r['filename'], $filetype);
                    $arr['issuecount'] = count ($arr['issuearray']);
                    if ($filetype == 'file') {
                        $xoopsTpl->append('issues', $arr['issuearray']);
                        $xoopsTpl->append('fileinfo', $arr);
                    } else {
                        $xoopsTpl->append('dirissues', $arr['issuearray']);
                        $xoopsTpl->append('dirinfo', $arr);
                    }
                }
            }
            $xoopsTpl->assign('dbhasfiles', $this->DbHasFiles);
            $xoopsTpl->assign('dbHasMallIssues', $this->dbHasMallIssues);            
     }
     
     /**
     * @Get issues based on filename
     * @param int $filename of the file to get issues
     * @param string $type of folder to scan for. Options are 'file' or 'dir'
     * @return array
     */  
    public function Issues($filename, $type)
    {
        global $xoopsDB, $xoopsTpl;
        $data = array();
        $iss = array();
        if ($type === "file") {
            $query = "SELECT * FROM ".$xoopsDB->prefix('xoopsecure_issues')
            ." WHERE filename = '".$filename
            ."' AND filetype = 'file' order by filename, linenumber";
        } else {
            $query = "SELECT * FROM ".$xoopsDB->prefix('xoopsecure_issues')
            ." WHERE filename = '".$filename
            ."' AND filetype = 'dir' order by filename, linenumber";       
        }
        $result = $xoopsDB->queryF($query);
        $count = $xoopsDB->getRowsNum($result);
        $i = 0;
        if ($count != 0) {    
            while ($r = $xoopsDB->fetchArray($result)) {
                $iss[$i]['issueid'] = $r['id'];
                $iss[$i]['issuetime'] = $r['time'];
                $iss[$i]['issuefn'] = $r['filename'];
                $iss[$i]['issuetype'] = $r['issuetype'];
                $iss[$i]['issuecat'] = $r['issuecat'];
                $iss[$i]['issuedesc'] = html_entity_decode($r['issuedesc']);
                $iss[$i]['issuecode'] = html_entity_decode($r['issuecode']);
                $iss[$i]['linenumber'] = $r['linenumber'];
                $iss[$i]['issuecount'] = $count;
                $iss[$i]['tag'] = $r['tag'];              
                $i++;
            }   
            return $iss;
        }
        
    }
    
    public function getdatetime ($type)
    {
        global $xoopsDB;
        $min = 0;
        $max = 0;
        $sql = "select min(time) as min ,max(time) as max from ".$xoopsDB->prefix("xoopsecure_issues"). " WHERE scantype = ".$type."";
        $result = $xoopsDB->queryF($sql);
        while ($r = $xoopsDB->fetchArray($result)) {
            $min = date($this->userdatetype, $r['min']);
            $max = date($this->userdatetype, $r['max']);
        }
        return ($min != $max) ? $min." - ".$max : $min;
        
    }
    
     public function dbHasFiles ()
    {
        global $xoopsDB;
        $sql = "select min(lastdate) as min from ".$xoopsDB->prefix("xoopsecure_files");
        $result = $xoopsDB->queryF($sql);
        $count = $xoopsDB->getRowsNum($result);
        while ($r = $xoopsDB->fetchArray($result)) {
            $min = date($this->userdatetype, $r['min']);
        }
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
        
    }
    
    public function clear ($file)
    {
        global $xoopsDB;
        $sql = "DELETE FROM ".$xoopsDB->prefix("xoopsecure_issues")." WHERE filename = '".xoopssecure_removequot ($file)."'";
        $result = $xoopsDB->queryF($sql);
    }
	
	/**
     * @create a dropdown select
     * @param string $name
     * @param array $options
     * @param string $selected (optional)
     * @return string
     */
    function dropdown( $name, array $options, $selected=null ){
        $dropdown = '<select name="'.$name.'" id="'.$name.'">'."<br>";
        $selected = $selected;
        foreach( $options as $key=>$option ) {
            $select = $selected==$key ? ' selected="yes"' : "";
            $dropdown .= '<option value="'.$option.'"'.$select.'>'.date('d-m-Y',$option).'</option>'."<br>";
        }
        $dropdown .= '</select>'."<br>";
        return $dropdown;
    }
	
	public function getdropdates ()
    {
        global $xoopsDB;
        $sql = "Select DISTINCT(inittime) AS Date FROM ".$xoopsDB->prefix("xoopsecure_issues")." ORDER BY Date DESC" ;
        $result = $xoopsDB->queryF($sql);
		while ($r = $xoopsDB->fetchArray($result)) {
            $dates = $r['Date'];
        }
		return $dates;
    }
 
}