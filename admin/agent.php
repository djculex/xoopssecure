<?php
declare(strict_types=1);

/**
 * Xoops XoopsSecure module for xoops
 *
 * @copyright 2021 XOOPS Project (https://xoops.org)
 * @license   GPL 2.0 or later
 * @package   Xoopssecure
 * @since     1.0
 * @min_xoops 2.5.11
 * @author    Culex - Email:culex@culex.dk - Website:https://www.culex.dk
*/
namespace XoopsModules\Xoopssecure;

use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use XoopsModules\Xoopssecure\FileH;
use XoopsModules\Xoopssecure\SpamScanner;
use XoopsModules\Xoopssecure\Db;
use XoopsModules\Xoopssecure\GeSHi;
use Xmf\Request;

require_once dirname(__DIR__, 3) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';

//Dont disrupt script
//@set_time_limit(3000);
//ignore_user_abort(true);

//$helper = Helper::getInstance();
require __DIR__ . '/header.php';
$moduleDirName      = $GLOBALS['xoopsModule']->getVar('dirname');
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);
$helper = Helper::getInstance();
\xoops_loadLanguage('scanner', $moduleDirName);
\xoops_loadLanguage('log', $moduleDirName);
\xoops_loadLanguage('download', $moduleDirName);

$type = ($_GET['type'] != "") ? $_GET['type'] : '';
$dir = ($_GET['Dir'] != "") ? $_GET['Dir'] : '';
$val = ($_GET['val'] != "") ? $_GET['val'] : '';
$t = time();

$fh = new FileH();
$dat = new Db();
$spam = new Xoopssecure\SpamScanner();
$autobackup = (int)$helper->getConfig('XCISAUTOBACKUP');

switch ($type) {
        // Get All files to json
    case 'jsonfiledir':
                $af = $fh->listdirs(array($fh->startPath));
                $fh->parseFolders(array_unique($af));
                header("Content-Type: application/json; charset=UTF-8");
                echo json_encode($af, JSON_PRETTY_PRINT);
        break;

        // --- NB ---- Not used at the moment. A count of dirs to go through.
    case 'getdirnum':
                $af = $fh->countDirs();
                header("Content-Type: application/json; charset=UTF-8");
                echo json_encode($af, JSON_PRETTY_PRINT);
        break;

        // Get a list of files to scan from path defined. From these only thoose
        // modified since last scan and skipping files defined in omit files
    case 'getFilesJson':
                header("Content-Type: application/json; charset=UTF-8");
                $pattern = "/^.*\.(" . $spam->fileTypesToScan . ")$/i";
                $dir = $spam->startPath;
                // script time : 18.29 seconds (16213 files) without db check
                // ----- // -- : 31,00 seconds (16213 files) with mod check empty db
                // ----- // -- : 38,00 seconds (63 files)      with unix - 2 days
                $f = $spam->getFilesJson($dir, $pattern);
                echo json_encode($f, JSON_PRETTY_PRINT);
        break;

        // Get array of dirs to exclude from scan
    case 'getOmitDirs':
                $d = $spam->omitdirs;
        break;

        // Get latest scan date (previous scan date)
    case 'getScanDate':
                header("Content-Type: application/json; charset=UTF-8");
                $d = $dat->getLatestTimeStamp();
                echo json_encode($d, JSON_PRETTY_PRINT);
        break;

        // Setting scan begin time
    case 'initStats':
                // At start of script
                $f = $dat->setScanDateStart(time());
        break;

        //Delete issues by filename
    case 'xoopssecuredeleteIssueByFN':
                $fn = $_GET['id'];
                $confirm = ($_GET['conf'] === true) ? true : false;
        if ($confirm === true) {
            $dat->deleteIssueByFN($fn, $confirm);
        } else {
            echo json_encode("NO", JSON_PRETTY_PRINT);
        }
        break;

        // Delete issue by id
    case 'xoopsSecureDeleteIssueByID':
                $id = $_GET['id'];
                $dat->deleteIssueByID($id);
        break;

        // Add file path to omit file setting
    case 'xoopsSecureAddtoOmitfilesByFilename':
                $fn = $_GET['id'];
                $confirm = ($_GET['conf'] === true) ? true : false;

        if ($confirm === true) {
            $dat->getConfigDateOmitfile($fn);
            $dat->deleteIssueByFN($fn, $confirm);
            echo json_encode("YES", JSON_PRETTY_PRINT);
        } else {
            echo json_encode("NO", JSON_PRETTY_PRINT);
        }
        break;

        // Add directory path to omit dirs setting
    case 'xoopssecureaddToOmitByDirN':
                $fn = $_GET['id'];
                $confirm = ($_GET['conf'] === true) ? true : false;

        if ($confirm == true) {
            $dat->getConfigDateOmitdir($fn, $confirm);
            $dat->deleteIssueByDirname($fn, $confirm); // Delete all containing dir name
            echo json_encode("YES", JSON_PRETTY_PRINT);
        } else {
            echo json_encode("NO", JSON_PRETTY_PRINT);
        }
        break;

        // Content of a file to div in log.php page.
        // Style using GeSHi and highlight line number in question
    case "getSourceCode":
                $fn = $_GET['filename'];
                $ln = $_GET['linenumber'];
                $source = file_get_contents($fn);

                $g = new Geshi(); // Initiate GeSHi class
                $fxt = pathinfo($fn, PATHINFO_EXTENSION); // Get extension of file
                $g->set_language(strtoupper($fxt)); // CAP LETTERS of ext.
                $g->load_from_file($fn); // Log content of file
                $g->set_header_type(GESHI_HEADER_PRE_VALID); // Make a header
                $g->enable_classes(true); // Enable classes
                $g->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 37); // Enable line numbers
                $g->set_overall_style('color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;', true); // Set overall style DUH
                $g->set_line_style('font: normal normal 95% \'Courier New\', Courier, monospace; color: #003030;', 'font-weight: bold; color: #006060;', true); // well....
                $g->set_code_style('color: #000020;', 'color: #000020;'); // hmmm...
                $g->set_link_styles(GESHI_LINK, 'color: #000060;'); // ditto
                $g->set_link_styles(GESHI_HOVER, 'background-color: #f0f000;'); // ditto again
        if ($ln != "" || $ln != 0) {
            $g->highlight_lines_extra((int)$ln, 'background-color:yellow'); // if line number is real make highligh yellow of this line
        }
                echo "<style type='text/css'>"; // Echo style start
                echo $g->get_stylesheet(); // echo stylesheet for file ext
                echo "</style>"; // close style
                echo $g->parse_code(); // print the styles, numbered and highlighted code
        break;

        //Check codingstandards
    case 'singleCSScan':
                $p = $_GET['filePath'];
                $spam->timestamp = $_GET['scanstart'];
        if (!$dat->filealreadyscanned($p, $spam->timestamp)) {
            $spam->malwareScanFile($p);
        }
        break;

        // Do malware scan on single page.
    case 'singleMalwareScan':
                //header("Content-Type: application/json; charset=UTF-8");
                $p = $_GET['filePath'];
                $spam->timestamp = $_GET['scanstart'];
        if (!$dat->filealreadyscanned($p, $spam->timestamp)) {
            $spam->malwareScanFile($p);
        }
        break;

        // Get array of options to use in scan dates drop down in log.php page
        // TODO.: replace dropdown
    case 'scanDatesForDropdown':
                header("Content-Type: application/json; charset=UTF-8");
                echo json_encode($dat->getLogDropdownDates(), JSON_PRETTY_PRINT);
        break;

        //Count issues by scantime.
    case 'getIssueCount':
                header("Content-Type: application/json; charset=UTF-8");
                $time = $_GET['time'];
                $issue = $_GET['issue'];
                $d = $dat->getIssueCount($time, $issue);
                echo json_encode($d, JSON_PRETTY_PRINT);
        break;

        //Check dir for missing index files
    case 'singleFileTest':
                $dir = $_GET['Dir']; //dir
                $autoFeature = $_GET['checkIndexfiles']; //bool
                $scanstart = $_GET['scanstart']; //timestamp
                $exists = $fh->indexFileExists($dir);
                $fh->chkIndexFiles($exists, $autoFeature, $scanstart);
        break;

        // Check file for permissions.
    case 'checkpermissions':
                //Check for file permissions
                $checkPermissions = $_GET['checkPermissions'];
                $fh->timestamp = $_GET['scanstart'];
                $fh->xoopsFilesPermissions($checkPermissions);
        break;

        // Set div with latest log result for short info
    case 'GetLatestInfoforScanpage':
        $dat->GetLatestLogCandT();
        break;

        //After scan set stats to be used befor next scan.
        // Also set counts of issues for stats.
    case 'DoStatsEnd':
        $t = array(
                'start'          => $_GET['starttime'],
                'end'            => $_GET['endtime'],
                'type'           => $_GET['scantype'],
                'permStack'      => $_GET['ps'],
                'permSet'        => $dat->getIssueCount($_GET['starttime'], '0'),
                'indexStack'     => $_GET['is'],
                'indexSet'       => $dat->getIssueCount($_GET['starttime'], '1'),
                'malStack'       => $_GET['ms'],
                'malSet'         => $dat->getIssueCount($_GET['starttime'], '2'),
                'csStack'        => $_GET['cs'],
                'csSet'          => $dat->getIssueCount($_GET['starttime'], '4'),
                );
        $dat->doStats($t, $op = 'save');
        $dat->GetLatestLogCandT();
        break;

        // Use sugguestion in xoops_version for paths
    case '"suggest"':
        // Initialize Recursive Iterator
        $query = $_GET['query'];
        $directory = XOOPS_ROOT_PATH;
        $result = xoopssecure_listdirs($directory, $query);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($result);
        break;

        // Delete logs from database by time
    case 'xoopsSecureLogfromDbByTime':
                $dtime = $_GET['dtime'];
                $dat->deleteLogByTime($dtime);
        break;
        
        // Do cron scan
    case 'doCronScan':
                $fh->cronScan();
        break;
        
        // Create backup of db and files
    case 'createzip':
        header('Content-type: application/zip');
        $zip = new Zipper();
        if ($helper->getConfig('XCISBACKUPTYPE') != 'none') {
            $zip->doZip($zip->archive, $zip->dirToBackup);
            $dat->updateLog("backup");
        }
        break;

        // Create backup of db and files
    case 'doAutoCreatezip':
        if ($autobackup == 1) {
            if ($fh->timeForBackup === true) {
                $zip = new Zipper();
                if ($helper->getConfig('XCISBACKUPTYPE') != 'none') {
                    $zip->doZip($zip->archive, $zip->dirToBackup);
                }
                $fh->autoDelBackupsFiles();
                $dat->updateLog("backup");
            }
        }
        break;

        // Delete backup zip
    case 'deleteZip':
        $name = $_GET['fn'];
        $link = XOOPS_ROOT_PATH . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "backup" . DIRECTORY_SEPARATOR . $name;
        if ($xoopsUser->isAdmin($xoopsModule->mid())) {
            unlink($link);
        }
        $fh->autoDelBackupsFiles();
        $fh->GetLatestBackupTable();
        break;

        // Load backuptable
    case 'getZipHtml':
        $fh->autoDelBackupsFiles();
        $fh->GetLatestBackupTable();
        break;
        
    case 'test':
        $pattern = "/^.*\.(" . $spam->fileTypesToScan . ")$/i";
        $dir = $spam->startPath;
                // script time : 18.29 seconds (16213 files) without db check
                // ----- // -- : 31,00 seconds (16213 files) with mod check empty db
                // ----- // -- : 38,00 seconds (63 files)      with unix - 2 days
        $f = $spam->getFilesJson($dir, $pattern);
        break;
}

    $GLOBALS['xoopsLogger']->activated = false;
