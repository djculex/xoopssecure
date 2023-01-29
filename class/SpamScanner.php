<?php

namespace XoopsModules\Xoopssecure;

use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use XoopsModules\Xoopssecure\Db;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use XoopsModules\Xoopssecure\Patterns;

/**
 * Spam scanner class
 *
 * Class with tools for scanning php pages for security issues
 * Mostly rewritten and updated but based on script by Bernard Toplak [WarpMax] <bernard@orion-web.hr>
 *
 * @author    Michael Albertsen <culex@culex.dk>
 * @credit    Bernard Toplak [WarpMax] <bernard@orion-web.hr>
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class SpamScanner
{
    public $timestamp;
    public $startPath;
    public $startPathCs;
    public $helper;
    public $fileTypesToScan;
    public $omitdirs;
    public $omitFiles;
    public $FileStacks;
    public $latestScanDate;

    /**
     * constructor
     *
     * @param mixed $id
     */
    public function __construct($helper = null)
    {
        if (null === $helper) {
            $helper = Helper::getInstance();
        }
        $this->helper = $helper;
        $db           = new db();

        $this->timestamp       = \time();
        $this->fileTypesToScan = $helper->getConfig('XCISFILETYPES');
        $this->FileStack       = [];
        $this->startPath       = $helper->getConfig('XCISSTARTPATH');
        $this->startPathCs     = $helper->getConfig('XCISDEVSTARTPATH');
        $this->omitdirs        = $this->omitFolders();
        $this->omitfiles       = $this->omitFiles();
        $this->latestScanDate  = ($db->getLatestTimeStamp() != 0) ? $db->getLatestTimeStamp() : 0;
        $this->pattern         = new Patterns();
    }

    /**
     * Get config folders not to scan
     * @return array
     */
    public function omitFolders()
    {
        $dirs    = $this->helper->getConfig('XCISOMITFOLDERS');
        $folders = preg_split('/\s+/', $dirs);
        $p       = [];
        foreach ($folders as $f) {
            $p[] = str_replace('\\', '/', XOOPS_ROOT_PATH . "/" . $f);
        }
        return $p;
    }

    /**
     * Get config files not to scan
     *
     * @return array
     */
    public function omitFiles()
    {
        $dirs    = $this->helper->getConfig('XCISOMITFILES');
        $folders = preg_split('/\s+/', $dirs);
        $p       = [];
        foreach ($folders as $f) {
            $p[] = str_replace('\\', '/', XOOPS_ROOT_PATH . "/" . $f);
        }
        return $p;
    }

    /**
     * Get files without the folders and files not not scan
     *
     * @param string $dir
     * @param string $pattern
     * @return array
     */
    public function getFilesJson($dir, $pattern)
    {
        @set_time_limit(0);
        $files = [];
        if (is_dir($dir) && is_readable($dir)) {
            $fh = opendir($dir);
            $db = new db();
            while (($file = readdir($fh)) !== false) {
                if ($file == '.' || $file == '..') {
                    continue;
                }

                $filepath = $dir . '/' . $file;
                $fn       = str_replace('\\', '/', $filepath);
                if (is_dir($filepath) && is_readable($filepath)) {
                    if (in_array($filepath, $this->omitdirs)) {
                        continue;
                    } else {
                        if (in_array($filepath, $this->omitfiles)) {
                            continue;
                        } else {
                            $files = array_merge($files, $this->getFilesJson($filepath, $pattern));
                        }
                    }
                } else {
                    if (preg_match($pattern, $file) && is_readable($file)) {
                        if ($this->latestScanDate >= filemtime($fn) && $this->latestScanDate > 0) {
                            continue;
                        } else {
                            array_push($files, $filepath);
                        }
                    }
                }
            }
            closedir($fh);
            return $files;
        }
    }

    public function test($dir, $pattern)
    {
        @set_time_limit(0);
        $files = [];
        $fh    = opendir($dir);
        $db    = new db();

        while (($file = readdir($fh)) !== false) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $filepath = $dir . '/' . $file;
            $fn       = str_replace('\\', '/', $filepath);

            if (is_dir($filepath)) {
                if (in_array($filepath, $this->omitdirs)) {
                    continue;
                } else {
                    if (in_array($filepath, $this->omitfiles)) {
                        continue;
                    } else {
                        $files = array_merge($files, $this->test($filepath, $pattern));
                    }
                }
            } else {
                if (preg_match($pattern, $file)) {
                    if ($this->latestScanDate >= filemtime($fn) && $this->latestScanDate > 0) {
                        continue;
                    } else {
                        array_push($files, $filepath);
                    }
                }
            }
        }
        closedir($fh);
        return $files;
    }

    /**
     * Get files without the folders and files not not scan for coding standard
     *
     * @param string $dir
     * @param string $pattern
     * @return array
     */
    public function getFilesJsonCS($dir, $pattern)
    {
        @set_time_limit(0);
        $files = [];
        $fh    = opendir($dir);
        $db    = new db();

        while (($file = readdir($fh)) !== false) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $filepath = $dir . '/' . $file;
            $fn       = str_replace('\\', '/', $filepath);

            if (is_dir($filepath)) {
                if (in_array($filepath, $this->omitdirs)) {
                    continue;
                } else {
                    if (in_array($filepath, $this->omitfiles)) {
                        continue;
                    } else {
                        $files = array_merge($files, $this->getFilesJsonCS($filepath, $pattern));
                    }
                }
            } else {
                if (preg_match($pattern, $file)) {
                    array_push($files, $filepath);
                }
            }
        }
        closedir($fh);
        return $files;
    }

    /**
     * Scan given file for all malware patterns'
     *
     * @param string  $path     path of the scanned file
     * @author Bernard Toplak [WarpMax] <bernard@orion-web.hr>
     * @global string $fileExt  file extension list to be scanned
     * @global array  $patterns array of patterns to search for
     * @author Michael Albertsen
     */
    public function malwareScanFile($path)
    {
        $count         = 0;
        $total_results = 0;
        $db            = new db();
        $patterns      = new Patterns();
        $db->updateLog("mallwarescan");
        if (!stripos($path, 'Patterns.php')/* skip this file */) {
            if ($malic_file_descr = array_search(pathinfo($path, PATHINFO_BASENAME), $patterns->BadFileNames)) {
                $dbtdtit = sprintf(_SCAN_XOOPSSECURE_MALWARE_SUSPECIOUSFILENAME, $path, $malic_file_descr);
                if ($db->issueExists($dbtdtit, 0) === false) {
                    $db->loadSave(
                        $this->timestamp,
                        '2',
                        '1',
                        htmlentities($dbtdtit, ENT_QUOTES),
                        _SCAN_XOOPSSECURE_MALWARE_SUSPECIOUSFILENAME_TITLE,
                        $path,
                        dirname($path),
                        $rating = 0,
                        $linenumber = 0,
                        $op = 'save'
                    );
                }
            }
            if (!($content = file_get_contents($path))) {
                $error = sprintf(_SCAN_XOOPSSECURE_ERROR_COULDNOTREADFILE_TITLE, $path);
                $db->loadSave(
                    $this->timestamp,
                    'x',
                    'x',
                    sprintf(_SCAN_XOOPSSECURE_ERROR_COULDNOTREADFILE_DESC, $path),
                    $error,
                    $path,
                    dirname($path),
                    $rating = 0,
                    $linenumber = 0,
                    $op = 'save'
                );
            } else { // do a search for fingerprints
                foreach ($patterns->Bads as $pattern) {
                    if (is_array($pattern)) { // it's a pattern
                        preg_match_all('#' . $pattern[0] . '#isS', $content, $found, PREG_OFFSET_CAPTURE);
                    } else { // it's a string
                        preg_match_all('#' . $pattern . '#isS', $content, $found, PREG_OFFSET_CAPTURE);
                    }
                    $all_results   = $found[0]; // remove outer array from results
                    $results_count = count($all_results); // count the number of results
                    $total_results += $results_count; // total results of all fingerprints
                    if (!empty($all_results)) {
                        $count++;
                        if (is_array($pattern)) { // then it has some additional comments
                            $dbtdtit = sprintf(_SCAN_XOOPSSECURE_MALWARE_ARRAYCOM, $pattern[2], $pattern[1], $path, $pattern[3]);
                            foreach ($all_results as $match) {
                                $dbtd = $dbtdtit . sprintf(
                                        _SCAN_XOOPSSECURE_MALWARE_ARRAYCOMEXPL,
                                        $this->calculateLineNumber($match[1], $content),
                                        htmlentities(substr($content, $match[1], 350), ENT_QUOTES)
                                    );
                                if ($db->issueExists($pattern[2], $this->calculateLineNumber($match[1], $content)) == false) {
                                    $db->loadSave(
                                        $this->timestamp,
                                        '2',
                                        '1',
                                        $pattern[2],
                                        $pattern[1],
                                        $path,
                                        dirname($path),
                                        $rating = 0,
                                        $linenumber = $this->calculateLineNumber($match[1], $content),
                                        $op = 'save'
                                    );
                                }
                            }
                        } else { // it's a string, no comments available
                            $dbtdtit = sprintf(_SCAN_XOOPSSECURE_MALWARE_ARRAYNOCOM, $path, $pattern);
                            foreach ($all_results as $match) {
                                $dbtd = $dbtdtit . sprintf(
                                        _SCAN_XOOPSSECURE_MALWARE_ARRAYCOMEXPL,
                                        $this->calculateLineNumber($match[1], $content),
                                        htmlentities(substr($content, $match[1], 350), ENT_QUOTES)
                                    );
                                if ($db->issueExists($pattern, $this->calculateLineNumber($match[1], $content)) == false) {
                                    $db->loadSave(
                                        $this->timestamp,
                                        '2',
                                        '1',
                                        $pattern,
                                        $pattern,
                                        $path,
                                        dirname($path),
                                        $rating = 0,
                                        $linenumber = $this->calculateLineNumber($match[1], $content),
                                        $op = 'save'
                                    );
                                }
                            }
                        }
                    }
                }
                unset($content);
            }
        }
    }

    /**
     * Calculates the line number where pattern match was found
     *
     * @param int $offset  The offset position of found pattern match
     * @param str $content The file content in string format
     * @return int Returns line number where the subject code was found
     * @author Bernard Toplak [WarpMax] <bernard@orion-web.hr>
     * @author Michael Albertsen
     */
    public function calculateLineNumber($offset, $file_content)
    {
        [$first_part] = str_split($file_content, $offset); // fetches all the text before the match
        $line_nr = strlen($first_part) - strlen(str_replace("\n", "", $first_part)) + 1;
        return $line_nr;
    }
}
