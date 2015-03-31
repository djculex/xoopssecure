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
 * @author 			Hervé Thouzard (http://www.herve-thouzard.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */

if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

/* strpos that takes an array of values to match against a string
 * note the stupid argument order (to match strpos)
 */
function xoopssecure_strpos_arr($haystack, $needle)
{
    if (!is_array($needle)) {
        $needle = array($needle);
    }
    foreach ($needle as $what) {
        if (($pos = strpos($haystack, $what)) !== false) {
            return $pos;
        }
    }

    return false;
}

/* Search an array for occurence of string
 * @param $needle the string to search for
 * @param $haystack the array to search within
 * @param $strict if string and occurence need to be identical else equal
 * @return True or false
 */
function xoopssecure_in_array_r($needle, $haystack, $strict = false)
{
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && xoopssecure_in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

/*
 * @desc Replace linebreaks to \n
 * @param string $s
 * @return string $s with replaced linebreaks
 */
function xoopssecure_lbc($s)
{
    $s = str_replace("\r\n", "\n", $s);
    $s = str_replace("<br>", "\n", $s);
    $s = str_replace("<br/>", "\n", $s);

    return $s;
}

/*
 * @desc Replace linebreaks to \n
 * @param string $s
 * @return string $s with replaced linebreaks
 */
function xoopssecure_ntobr($s)
{
    $s = str_replace("\n", "<br>", $s);

    return $s;
}

function xoopssecure_getArrayKeys($array)
{
    $array = implode(", ", $array);

    return $array;
}

function xoopssecure_dirSep($filename)
{
    return str_replace("\\", "/", $filename);
}

/*
 * Get pathinfo from url
 * @param string $path the url to do the work on
 * @return $tab
*/
function xoopssecure_pathinfo($path)
{
    $tab = pathinfo($path);
    $tab["basenameWE"] = substr($tab["basename"], 0, strlen($tab["basename"]) - (strlen($tab["extension"]) + 1));

    return $tab;
}

/*
 * @desc checks if an apachemodule is set or not
 * @param string $val the name of the desired apache mod name
 * @return TRUE if the $val is set OR FALSE if not
*/
function xoopssecure_apachemodule($val)
{
    $apachemod = apache_get_modules();
    if (in_array($val, $apachemod)) {
        return true;
    } else {
        return false;
    }
}

/*
 * @desc get options to the preferences based on server settings. Checks
 *       if server accept mod_rewrite or not.
 * @return array $opt
*/
function xoopssecure_modversion_apachemod()
{
    $opt = array();
    $check = xoopssecure_apachemodule('mod_rewrite');
    if ($check === true) {
        $opt['_MI_XOOPSSECURE_AUTOINDEXFILESSELECT_HTTACCESSFILE'] = 0;
        $opt['_MI_XOOPSSECURE_AUTOINDEXFILESSELECT_HTMLFILE'] = 1;
        $opt['_MI_XOOPSSECURE_AUTOINDEXFILESSELECT_BOTH'] = 2;
    } else {
        $opt['_MI_XOOPSSECURE_AUTOINDEXFILESSELECT_HTMLFILE'] = 1;
    }

    return $opt;
}

/*
 * @desc strip back-slashes and replace with single forwared slash
 * @param string $string the string to work over
 * @return $string the slash free string
*/
function xoopssecure_cleanUrl($string)
{
    $string = str_replace('\\', '/', $string);
    $string = str_replace('//', '/', $string);
    $string = trim($string);

    return $string;
}

/*
 * @desc function to remove quotes from string
 * $param string $s the string to search quotes
 * @return string $s stripped of quotes
*/
function xoopssecure_removequot($s)
{
    if (is_object($s) || is_array($s)) {
        foreach ($s as &$value) {
            $value = xoopssecure_removequot($value);
        }
    } else {
        $s = html_entity_decode($s);
        $s = str_replace("'", "", $s);
        $s = str_replace('"', "", $s);
    }

    return $s;
}

/*
 * Return a calculated ../ type relative path
 * @param url $path the destination url
 * @param url $from the origin url
 * @return string - the translated relative path between the two paths
 *
*/
function xoopssecure_getRelativePath($path, $from = __FILE__)
{
    $path = xoopssecure_cleanUrl($path);
    $from = xoopssecure_cleanUrl($from);
    $path = explode("/", $path);
    $from = explode("/", dirname($from.'.'));
    $common = array_intersect_assoc($path, $from);

    $base = array('.');
    if ($pre_fill = count(array_diff_assoc($from, $common))) {
        $base = array_fill(0, $pre_fill, '..');
    }
    $path = array_merge($base, array_diff_assoc($path, $common));

    return implode("/", $path);
}

function xoopssecure_rootToUrl($fn)
{
    $fn = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $fn);
    $fn = str_replace("\\", '/', $fn);
    $fn = str_replace("//", '/', $fn);

    return $fn;
}

function xoopssecure_relToAbsUrlCheck($url)
{
    return xoopssecure_cleanUrl(
        str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $url)
    );
}

/*
 * Removes children of folder url or
 *
 *
 */
function xoopssecure_rmChildren($url, $val)
{
    global $xoopsDB;
    $url = xoopssecure_removequot($url);
    $orgurl = $url;
    $url = str_replace(XOOPS_ROOT_PATH, '', $url);
    $url = str_replace(XOOPS_URL, '', $url);
    $url = str_replace("/", '.', $url);
    
    $arr = array();
    
    $sql   = "SELECT id, url FROM ".$xoopsDB->prefix('xoopsecure_ignores');
    $sql  .= " WHERE val = '".$val."'";
    $result = $xoopsDB->queryF($sql);
    $resp = false;
    while ($r = $xoopsDB->fetchArray($result)) {
        $r['urla'] = str_replace(XOOPS_ROOT_PATH, '', $r['url']);
        $r['urla'] = str_replace(XOOPS_URL, '', $r['urla']);
        $r['urla'] = str_replace("/", '\\/', $r['urla']);
        $r['urla'] = "~".$r['urla']."~";
        $arr[] = $r;
    }
    foreach ($arr as $a) {
        $burl = dirname($a['url'])."/";
        preg_match_all($a['urla'], $url, $matches);
        //echo $burl ." === ". $orgurl."<br>";
        if (!empty($matches[0]) || $burl === $orgurl) {
            $sql = "DELETE FROM ".$xoopsDB->prefix('xoopsecure_ignores').
                " Where id = '".$a['id']."'";
            if (is_dir($orgurl) && dirname($orgurl) === dirname($a['url'])) {
                $sql .= " OR url = '".$a['url']."'";
            }
            $sql .= " AND val = '".$val."'";
            $result = $xoopsDB->queryF($sql);
        }
    }
}

/*
 * Will check if an ignore value is in the ignore list or
 * if the parent folder is.
 * @param string $url of the file to look for
 * @param string $val ('ignore' or 'chmod')
 * @return bolean $resp (true or false)
 */
function xoopssecure_isfolderonlist($url, $val)
{
    global $xoopsDB;
    
    $arr = array();

    $sql  = "SELECT url FROM ".$xoopsDB->prefix('xoopsecure_ignores');
    $sql .= " WHERE val = '".$val."'";
    $result = $xoopsDB->queryF($sql);
    $resp = false;
    while ($r = $xoopsDB->fetchArray($result)) {
        $r['urla'] = "~".$r['url']."~";
        preg_match_all($r['urla'], $url, $matches);
        if (!empty($matches[0])) {
            $resp = true;
        }
    }

    return $resp;
}

/*
 * recursively flatten a multi level array
 * @param array $array to flatten
 * @return array $return
 */
function xoopssecure_flatten(array $array)
{
    $return = array();
    array_walk_recursive($array, function ($a) use (&$return) { $return[] = $a; });

    return $return;
}

/* function to explode string to array
 * @param string $string
 * @return array $array
 */
 function xoopssecure_StringToArray($string)
 {
     return ($string != '') ? preg_split("/\r\n|\n|\r/", $string) : array();
 }

/* implode array to sring
 * @param array $array
 * @return string $string
 */
 function xoopssecure_ArrayToString($array)
 {
     return (!empty($array)) ? implode("\n", $array) : '';
 }
 
 
 /* Returns array of minimum files and folders to be used as default in config
  * @return array $data containing paths
  */
 
 function xoopssecure_backupFilesMin()
 {
     return array(
        XOOPS_ROOT_PATH."/uploads/" => XOOPS_ROOT_PATH."/uploads/",
        XOOPS_ROOT_PATH."/modules/" => XOOPS_ROOT_PATH."/modules/",
        XOOPS_ROOT_PATH."/themes/" => XOOPS_ROOT_PATH."/themes/",
        XOOPS_PATH."/" => XOOPS_PATH."/",
        XOOPS_VAR_PATH."/" => XOOPS_VAR_PATH."/",
        XOOPS_ROOT_PATH."/mainfile.php" => XOOPS_ROOT_PATH."/mainfile.php"
    );
 }
 
/*
 * Get value from xoopsconfig
 * @param string $option where $option is the XoopsConfig issue name
 * @param string $xoopssecure the module name from XoopsConfig
 * @return bolean $retval the value parsed from database
 */
 
function xoopssecure_GetModuleOption($option, $repmodule='xoopssecure')
{
    global $xoopsModuleConfig, $xoopsModule;
    static $tbloptions = array();
    if (is_array($tbloptions) && array_key_exists($option, $tbloptions)) {
        return $tbloptions[$option];
    }
    $retval = false;
    if (isset($xoopsModuleConfig)
        && (is_object($xoopsModule)
        && $xoopsModule->getVar('dirname') == $repmodule
        && $xoopsModule->getVar('isactive'))
    ) {
        if (isset($xoopsModuleConfig[$option])) {
            $retval= $xoopsModuleConfig[$option];
        }
    } else {
        $module_handler =& xoops_gethandler('module');
        $module =& $module_handler->getByDirname($repmodule);
        $config_handler =& xoops_gethandler('config');
        if ($module) {
            $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
            if (isset($moduleConfig[$option])) {
                $retval= $moduleConfig[$option];
            }
        }
    }
    $tbloptions[$option]=$retval;

    return $retval;
}

/*
 * Checks if server is a windows server
 * @return true if server is found to be a local or windows server and FALSE if not
 */
 
function xoopssecure_iswin()
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        return true;
    } else {
        return false;
    }
}

/*
 * @desc converts intval to b, Kb, Mb or Gb
 * @param $n the initian value in Bytes
 * @return intval $n converted to desired format
 */
 
function xoopssecure_convertToBytes($n)
{
    // If n is -1 then there is no limit
    if ($n == -1) {
        return PHP_INT_MAX;
    }
    switch (substr($n, -1)) {
            case "B": return substr($n, 0, -1);
            case "K": return substr($n, 0, -1) * 1024;
            case "M": return substr($n, 0, -1) * 1024 * 1024;
            case "G": return substr($n, 0, -1) * 1024 * 1024 * 1024;
    }

    return $n;
}

/*
 * @desc Scan folders & content to get a full dir size
 * @param $string $dir the directory url to scan
 * $return intval $totalsize of all content in this path
*/
function xoopssecure_getDirectorySize($dir)
{
    $count_size = 0;
    $count = 0;
    $dir_array = scandir($dir);
    foreach ($dir_array as $key=>$filename) {
        if ($filename != ".." && $filename!=".") {
            if (is_dir($dir."/".$filename)) {
                continue;
            } elseif (is_file($dir."/".$filename)) {
                $count_size = $count_size + filesize($dir."/".$filename);
                $count++;
            }
        }
    }

    return $count_size;
}

/* Get html for ignore list with drag drop selectors
 * @param string $root is the default dir of file tree
 * @param string $dir is the selected dir to work with
 * @param string type weather this is ignore list or select dir
 * @return string echoed
 */
function xoopssecure_ignoreFileTree($root, $dir, $type)
{
    if (file_exists($root . $_POST['dir'])) {
        $files = scandir($root . $_POST['dir']);
        natcasesort($files);
        if (count($files) > 2) { /* The 2 accounts for . and .. */
            echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
            // All dirs
            foreach ($files as $file) {
                if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file)) {
                    echo "<li class=\"directory collapsed\"><a href=\"#\" ref=\"dir\" rel=\"" .
                        htmlentities($_POST['dir'] . $file) . "/\">" .
                        htmlentities($file) .
                            
                        "</a></li>";
                }
            }
            if ($type != 'scanner') {
                // All files
            foreach ($files as $file) {
                if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file)) {
                    $ext = preg_replace('/^.*\./', '', $file);
                    echo "<li class=\"file ext_$ext\"><a href=\"#\" ref=\"file\" rel=\"" .
                        htmlentities($_POST['dir'] . $file) . "\">" .
                        htmlentities($file) .
                           
                        "</a></li>";
                }
            }
            }
            echo "</ul>";
        }
    }
    if ($type === 'ignorelist') {
        echo '<script type="text/javascript">';
        echo    'xoopssecure_ignoreDragDrop ();';
        echo '</script>';
    }
}

/* Download files setting headers correctly
 * @param string $url = url to fetch
 * @return read content of file using readfile();
 */
 
function xoopssecure_DownloadFile($file) // $file = include path
{
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        //ob_clean();
        flush();
        readfile($file);
        exit;
    }
}

/* Delete backupfolder
 * @param url $file is the filename
 * @return void
 */
 
function xoopssecure_deleteBackupFolder($file)
{
    if (file_exists($file)) {
        unlink($file);
    }
}

function xoopssecure_deleteFolder($dirPath)
{
    foreach (
        new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $dirPath, FilesystemIterator::SKIP_DOTS
            ), RecursiveIteratorIterator::CHILD_FIRST
        ) as $path
    ) {
        $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
    }
    rmdir($dirPath);
}
