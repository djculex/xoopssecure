<?php

namespace XoopsModules\Xoopssecure;

use DirectoryIterator;
use RecursiveDirectoryIterator;
use XoopsCache;
use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use XoopsModules\Xoopssecure\Db;
use RecursiveIteratorIterator;
use XoopsMailer;
use XoopsPersistableObjectHandler;
use XoTheme;
use function time;
use function xoops_loadLanguage;

/**
 * File handling class for XoopsSecure
 *
 * This class will zip selected files or if selected all files
 * as well as MySql backup and enable downloading.
 *
 * @author    Michael Albertsen <culex@culex.dk>
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class FileH extends XoopsPersistableObjectHandler
{
    /**
     * @var int|string
     */
    public int|string $timestamp;
    /**
     * @var array
     */
    public array $FileDir;
    /**
     * @var array|mixed
     */
    public mixed $startPath;
    /**
     * @var int
     */
    public int $deleteBackupAfterDays;
    /**
     * @var false|int
     */
    public int|false $backupFilesMaxAge;
    /**
     * @var bool|string
     */
    public string|bool $timeForBackup;
    /**
     * @var bool|string
     */
    public string|bool $timeForCron;
    /**
     * @var \XoopsModules\Xoopssecure\Db
     */
    public $db;
    /**
     * @var Helper|null
     */
    public ?Helper $helper;

    /**
     * constructor
     *
     * @param Helper $helper init helper
     */
    public function __construct($helper = null)
    {
        if (null === $helper) {
            $helper = Helper::getInstance();
        }
        $this->helper = $helper;
        $this->db = new Db();

        $this->timestamp = time();
        $this->startPath = $helper->getConfig('XCISSTARTPATH');
        $this->FileDir = [];
        $this->deleteBackupAfterDays = (int)$helper->getConfig('XCISAUTOBACKUPDELETE');
        $this->backupFilesMaxAge = strtotime("-{$this->deleteBackupAfterDays} days", time());
        $this->timeForBackup = $this->db->setTimedEvent("backup");
        $this->timeForCron = $this->db->setTimedEvent("cronscan");
    }

    /**
     * Create text for the buy me a coffee link
     *
     * @return string $text
     */
    public static function buymecoffey(): string
    {
        return '
		<a href="https://www.buymeacoffee.com/culex99906" target="_blank">
			<img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" 
            alt="Buy Me A Coffee" 
            style="height: 40px !important;width: 175px !important;" >
		</a>';
    }

    /**
     * Get all files from start path to array
     *
     * @return array $arr of file paths
     */
    public function GetAllFiles(): array
    {
        set_time_limit(0);
        $db = new db();
        $arr = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->startPath
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                if (!in_array($file->getRealpath(), $arr)) {
                    $arr[] = $file->getRealpath();
                }
            }
        }
        return $arr;
    }

    /**
     * Get all zip files with unix dates
     *
     * @return void
     */
    public function autoDelBackupsFiles(): void
    {
        //path to directory to scan
        $directory = XOOPS_ROOT_PATH . "/uploads/backup/";
        $iterator = new DirectoryIterator($directory);
        $eol = PHP_EOL;
        $result = [];
        $i = 0;
        foreach ($iterator as $ff) {
            if ($ff->isDot()) {
                continue;
            }
            if ($ff->isFile() && $ff->getExtension() == "zip") {
                if ($ff->getMTime() < $this->backupFilesMaxAge) {
                    unlink(XOOPS_ROOT_PATH . "/uploads/backup/" . $ff->getFilename());
                }
            }
            $i++;
        }
    }

    /**
     * Get latest html table of backups
     *
     * @return void
     */
    public function GetLatestBackupTable()
    {
        $files = $this->getBackupsFiles();
        $num = count($files);

        echo "<table class='table' id='xoopssecure_backup_downloadtable'>";
        if (!empty($files)) {
            echo "<thead>
						<tr>
							<th class='type'></th>
							<th class='name truncate'>" . DO_XOOPSSECURE_DOWNLOAD_BACKUPFILENAME . "</th>
							<th class='date'>" . DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEDATE . "</th>
							<th class='size'>" . DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEDOWNLOAD . "</th>
							<th class='size'>" . DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEACTION . "</th>
						</tr>
						</thead>
						<tbody>";

            foreach ($files as $file) {
                echo "<tr id='xoopssecure_trbackup_" . $file['filename'] . "'>
								<td class='type'><i class='fa fa-archive'></i></td>
								<td class='name truncate'>" . $file['filename'] . "</td>
								<td class='date'>" . $file['time'] . "</td>
								<td class='size'>
                                    <a href=" . XOOPS_URL . "/uploads/backup/" . $file['filename'] . ">" .
                    DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEDOWNLOADTEXT . "
                                    </a>
                                </td>
								<td class='delete'>
									<a role='button' 
										id = 'xoopssecure_delete_zip' 
										data-id='" . $file['filename'] . "' 
										data-tip='delete' 
										data-conftext='" . DO_XOOPSSECURE_CONDELETEBACKUP_TEXT . "'
										data-confyes='" . DO_XOOPSSECURE_CONFIRM_YES . "' 
                                        data-confno='" . DO_XOOPSSECURE_CONFIRM_NO . "'>
                                        <i class='fa fa-trash'></i>
									</a>
								</td>
							</tr>";
            }
        } else {
            echo "<tr id='xoopssecure_trbackup_0'>
							<td class='type'></td>
							<td colspan='4' class='name truncate' style='font-size: 16px;text-align: center;font-weight: bold;'>" .
                                DO_XOOPSSECURE_NOBACKUPYET . "
                            </td>
							<td class='delete'></td>
						</tr>";
        }
        echo "</tbody>
						</table>";
    }

    /**
     * Get all zip files with unix dates
     *
     * @return array $result    Array containing filename and time
     */
    public function getBackupsFiles(): array
    {
        //path to directory to scan
        if (!is_dir(XOOPS_ROOT_PATH . "/uploads/backup")) {
            @mkdir(XOOPS_ROOT_PATH . "/uploads/backup", 0755, true);
        }
        $directory = XOOPS_ROOT_PATH . "/uploads/backup/";
        $iterator = new DirectoryIterator($directory);
        $eol = PHP_EOL;
        $result = [];
        $i = 0;
        foreach ($iterator as $ff) {
            if ($ff->isDot()) {
                continue;
            }
            if ($ff->isFile() && $ff->getExtension() == "zip") {
                if ($ff->getMTime() < $this->backupFilesMaxAge) {
                    unlink(XOOPS_ROOT_PATH . "/uploads/backup/" . $ff->getFilename());
                } else {
                    $result[$i]['filename'] = $ff->getFilename();
                    $result[$i]['time'] = date("d-m-Y H:i:s", $ff->getMTime());
                }
            }
            $i++;
        }
        arsort($result);
        return $result;
    }

    /**
     * Check coding standard of single path
     *
     * @param string $path of file
     * return void
     */
    public function checkCs($path)
    {
        // default values
        $options['format'] = "array"; // default format
        // Get user selection
        $configFile = XOOPS_ROOT_PATH . "/modules/xoopssecure/class/phpcheckstyle/config/xoops.cfg.xml";
        $options['exclude'] = [];
        $formats = explode(',', $options['format']);

        // Launch PHPCheckstyle
        if (true === is_array($path)) {
            $sources = explode(',', $path);
        } else {
            $sources = $path;
        }
        $style = new PHPCheckstyle($formats, 0, $configFile, false, false, false);
        $style->processFiles($path, $options['exclude']);
    }

    /**
     * flatten array
     *
     * @param array $array
     * @return array $result
     */
    public function xoopssecure_SuperUnique($array): array
    {
        $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->xoopssecure_SuperUnique($value);
            }
        }
        return $result;
    }

    /**
     * Count dirs in start path
     *
     * @return int count
     */
    public function countDirs(): int
    {
        return count($this->listdirs([$this->startPath]));
    }

    /**
     * Get dirs from path
     *
     * @param string $path the start path
     * @return array $dir_paths only unique dirs
     */
    public function listdirs($path): array
    {
        global $dir_paths; //global variable where to store the result
        foreach ($path as $dir) { //loop the input
            $dir_paths[] = str_replace('\\', '/', $dir); //can use also "basename($dir)" or "realpath($dir)"
            $subdir = glob($dir . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR); //use DIRECTORY_SEPARATOR to be OS independent
            if (!empty($subdir)) { //if subdir is not empty make function recursive
                $this->listdirs($subdir); //execute the function again with current subdir
            }
        }
        return array_unique($dir_paths);
    }

    /**
     * Does dir already have index file ?
     *
     * @param string $dir the path
     * @return array with dir and bool value
     */
    public function indexFileExists($dir): array
    {
        $indexFileExists = 0;
        $ret = [];
        $filenames =
            [
                'index.html',
                'index.htm',
                'index.php',
                'index.php3'
            ];
        foreach ($filenames as $fn) {
            if (is_file($dir . DIRECTORY_SEPARATOR . $fn)) {
                $indexFileExists = $indexFileExists + 1;
            }
        }
        return ($indexFileExists == 0) ? ["dir" => $dir, "code" => 1] : ["dir" => $dir, "code" => 0];
    }

    /**
     * check folder for index files for database
     *
     * @param string|array $checkfile where to look
     * @param string $autoFeature string bool is set/ not set
     * @param string $scanstart the time stamp set
     * @return void
     */
    public function chkIndexFiles($checkfile, $autoFeature, $scanstart): void
    {
        $dat = new Db();
        foreach ($checkfile as $d => $c) {
            if ($c == '1') { // if missing
                $scanTime = $scanstart;
                $scanDesc = sprintf(_SCAN_XOOPSSECURE_MISSINGINDEXFILE, $checkfile['dir']);
                if ($autoFeature == 'true') { // auto create is set true
                    $this->createIndexFile($checkfile['dir']);
                    $scanDesc = sprintf(_SCAN_XOOPSSECURE_MISSINGINDEXFILE_FIXED, $checkfile['dir']);
                    $dat->loadSave(
                        $scanTime,
                        '1',
                        '0',
                        $scanDesc,
                        _SCAN_XOOPSSECURE_MISSINGINDEXFILE_TITLE,
                        '',
                        $checkfile['dir'],
                        $rating = 0,
                        $linenumber = 0,
                        $op = 'save'
                    );
                    $this->db->updateLog("indexfilesscan");
                } else { // auto create is set false
                    $dat->loadSave(
                        $scanTime,
                        '1',
                        '1',
                        $scanDesc,
                        _SCAN_XOOPSSECURE_MISSINGINDEXFILE_TITLE,
                        '',
                        $checkfile['dir'],
                        $rating = 0,
                        $linenumber = 0,
                        $op = 'save'
                    );
                }
            }
        }
    }

    /**
     * Create index file
     *
     * @param string $url where create
     * @return void
     */
    public function createIndexFile($url): void
    {
        // Get original dir write permissions
        $orgch = substr(sprintf('%o', fileperms($url)), -4);
        if (!is_writable($url)) {
            // If write protect, temp. make writable
            chmod($url, 0777);
        }
        $text = "<?php" . "\n" .
            "/**" . "\n" .
            " * Index.php file." . "\n" .
            " * " . "\n" .
            " * Using an index file in every folder not having any is one way of preventing directory browsing" . "\n" .
            " * The best way is to remove the Indexes directive from your httpd.conf, " . "\n" .
            "this is however not always an option on for instance hosted server " . "\n" .
            " * " . "\n" .
            " */" . "\n" .
            "\n" . "\n" .
            "/**" . "\n" .
            "  * This index.php file will show an error '404 not found' when entering this folder" . "\n" .
            "  * and was created : " . date('d-m-Y H:i:s', $this->timestamp) . " by a xoopsSecure scan/create." . "\n\n" .
            "  * @package      \XoopsModules\xoopssecure" . "\n" .
            "  * @copyright    The XOOPS Project (https://xoops.org)" . "\n" .
            "  * @copyright    " . date('Y', $this->timestamp) . " Culex" . "\n" .
            "  * @author       Michael Albertsen (http://culex.dk) <culex@culex.dk>" . "\n" .
            "  * @link         https://github.com/XoopsModules25x/xoopssecure" . "\n" .
            "  * @since        1.0" . "\n" .
            "  */\n" . "\n" .
            "header(\$_SERVER[\"SERVER_PROTOCOL\"] . \" 404 Not Found\", true, 404);" .
            "";
        $path = $url . DIRECTORY_SEPARATOR . 'index.php';
        file_put_contents($path, $text);
        //Set permissions back to original
        chmod($url, $orgch);
    }

    /**
     * set file permissions based on config
     *
     * @param string $autocorrect
     * @return void
     */
    public function xoopsFilesPermissions($autocorrect): void
    {
        $dat = new Db();
        $mf = $this->getFilePermission(XOOPS_ROOT_PATH . '/mainfile.php');
        $sf = $this->getFilePermission(XOOPS_VAR_PATH . '/data/secure.php');
        $lf = $this->getFilePermission(XOOPS_VAR_PATH . '/data/license.php');

        // Set mainfile.php readonly
        if ($mf != 444) {
            $fn = XOOPS_ROOT_PATH . '/mainfile.php';
            $dn = XOOPS_ROOT_PATH;
            $scanDesc = sprintf(_SCAN_XOOPSSECURE_WRONFILEPERMISSION, XOOPS_ROOT_PATH . '/mainfile.php', $mf, "0444");
            if ($autocorrect == 'true') {
                @chmod(XOOPS_ROOT_PATH . '/mainfile.php', 0444);
                $scanDesc = sprintf(_SCAN_XOOPSSECURE_WRONFILEPERMISSION_FIXED, XOOPS_VAR_PATH . '/mainfile.php', $lf, "0444", "0444");
                $dat->loadSave(
                    $this->timestamp,
                    '0',
                    '0',
                    $scanDesc,
                    _SCAN_XOOPSSECURE_WRONFILEPERMISSION_TITLE,
                    $fn,
                    $dn,
                    $rating = 0,
                    $linenumber = 0,
                    $op = 'save'
                );
            } else {
                $dat->loadSave(
                    $this->timestamp,
                    '0',
                    '1',
                    $scanDesc,
                    _SCAN_XOOPSSECURE_WRONFILEPERMISSION_TITLE,
                    $fn,
                    $dn,
                    $rating = 0,
                    $linenumber = 0,
                    $op = 'save'
                );
            }
        }

        // Set Secure file readonly
        if ($sf != 444) {
            $fn = XOOPS_VAR_PATH . '/data/secure.php';
            $dn = XOOPS_VAR_PATH;
            $scanDesc = sprintf(_SCAN_XOOPSSECURE_WRONFILEPERMISSION, XOOPS_VAR_PATH . '/data/secure.php', $sf, "0444");
            if ($autocorrect == 'true') {
                @chmod(XOOPS_VAR_PATH . '/data/secure.php', 0444);
                $scanDesc = sprintf(_SCAN_XOOPSSECURE_WRONFILEPERMISSION_FIXED, XOOPS_VAR_PATH . '/data/secure.php', $lf, "0444", "0444");
                $dat->loadSave(
                    $this->timestamp,
                    '0',
                    '0',
                    $scanDesc,
                    _SCAN_XOOPSSECURE_WRONFILEPERMISSION_TITLE,
                    $fn,
                    $dn,
                    $rating = 0,
                    $linenumber = 0,
                    $op = 'save'
                );
            } else {
                $dat->loadSave(
                    $this->timestamp,
                    '0',
                    '1',
                    $scanDesc,
                    _SCAN_XOOPSSECURE_WRONFILEPERMISSION_TITLE,
                    $fn,
                    $dn,
                    $rating = 0,
                    $linenumber = 0,
                    $op = 'save'
                );
            }
        }

        // set secure license file
        if ($lf != 444) {
            $fn = XOOPS_VAR_PATH . '/data/license.php';
            $dn = XOOPS_VAR_PATH;
            $scanDesc = sprintf(_SCAN_XOOPSSECURE_WRONFILEPERMISSION, XOOPS_VAR_PATH . '/data/license.php', $lf, "0444");
            if ($autocorrect == 'true') {
                @chmod(XOOPS_VAR_PATH . '/data/license.php', 0444);
                $scanDesc = sprintf(_SCAN_XOOPSSECURE_WRONFILEPERMISSION_FIXED, XOOPS_VAR_PATH . '/data/license.php', $lf, "0444", "0444");
                $dat->loadSave(
                    $this->timestamp,
                    '0',
                    '0',
                    $scanDesc,
                    _SCAN_XOOPSSECURE_WRONFILEPERMISSION_TITLE,
                    $fn,
                    $dn,
                    $rating = 0,
                    $linenumber = 0,
                    $op = 'save'
                );
            } else {
                $dat->loadSave(
                    $this->timestamp,
                    '0',
                    '1',
                    $scanDesc,
                    _SCAN_XOOPSSECURE_WRONFILEPERMISSION_TITLE,
                    $fn,
                    $dn,
                    $rating = 0,
                    $linenumber = 0,
                    $op = 'save'
                );
            }
        }
        if ($autocorrect == 'true') {
            $this->db->updateLog("permissionscan");
        }
    }

    /**
     * Get file permission of file
     *
     * @param string $file the file to get fp from
     * @return string|void
     */
    public function getFilePermission($file)
    {
        if (is_readable($file)) {
            $length = strlen(decoct(fileperms($file))) - 3;
            return substr(decoct(fileperms($file)), $length);
        }
    }

    /**
     * Search for string in folder
     *
     * @param string $string to search for
     * @param string $folder the folder to look in
     * @return void
     */
    public function searchRegex($string, $folder): void
    {
        $dir = new RecursiveDirectoryIterator($folder);
        foreach (new RecursiveIteratorIterator($dir) as $filename => $file) {
            $content = file_get_contents($file->getPathname());
            if (str_contains($content, $string)) {
                echo "<br /><b>string found in file: " . $file->getPathname() . "</b><br /><br />";
            }
        }
    }

    /**
     * Set basic javascript includes
     *
     * @return void
     */
    public function setJqueryScript(): void
    {
        if ($_SESSION["xoopssecureCoreEvents"] == 0) { // Check $_session
            global $xoopsConfig;
            $theme = new XoTheme();
            // Set javascript vars
            // initiate XoopsCache class
            $xc = new XoopsCache();
            // Collect old cached to avoid double include script vars
            $xc->gc();
            $_SESSION["xoopssecureCoreEvents"] = 0;

            $helper = Helper::getInstance();

            $_SESSION["xoopssecureCoreEvents"] += 1;
            $script = null;
            $name = basename($_SERVER['REQUEST_URI']);
            // language files
            $language = $xoopsConfig['language'];
            $script = "if (typeof xoopsSecureSysUrl === 'undefined' || typeof xoopsSecureSysUrl === '') {" . "\n";
            $script .= "	var xoopsSecureSysUrl = '" . XOOPS_URL . "/modules/xoopssecure/admin/';" . "\n";
            $script .= '};' . "\n";
            if ($_SESSION["xoopssecureCoreEvents"] <= 1) {
                $theme->addScript(null, ['type' => 'text/javascript'], $script, 'xoopssecureCore');
            }
        }
    }

    /**
     * Do a scan from script
     *
     * Based on time checking for changed files
     * @return void but sends email if something found
     */
    public function cronScan(): void
    {
        if ((int)$this->helper->getConfig('XCISCRONTYPE') == 1) {
            if ($this->timeForCron === true) {
                $spam = new SpamScanner();
                $checkinterval = (int)$this->helper->getConfig('XCISCRONINTERVAL'); // 24
                $beforeTime = strtotime($checkinterval . " hours");
                $ft = $this->helper->getConfig('XCISFILETYPES');
                $allfiles = $this->get_allFilesTotal(XOOPS_ROOT_PATH);
                $info = $this->sumupFiles($allfiles);
                if (!empty($allfiles)) {
                    $this->sendCronMail($info);
                }
                $this->db->updateLog("cronscan");
            }
        }
    }

    /**
     * Get all files
     *
     * Get files and test if modified or new in last X hours
     * @return array $array of files
     */
    public function get_allFilesTotal($path): array
    {
        $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $spam = new SpamScanner();
        $files = [];
        $checkinterval = (int)$this->helper->getConfig('XCISCRONINTERVAL'); // 24
        $beforeTime = strtotime("-" . $checkinterval . " hours");
        foreach ($iter as $file) {
            if ($file->isDir()) {
                continue;
            }
            if (!in_array(dirname($path), $spam->omitdirs)) {
                if (is_file($file->getPathname()) && $file->getMTime() >= $beforeTime) {
                    $files[] = $file->getPathname();
                }
            }
        }
        return $files;
    }

    /**
     * Collect array to string
     *
     * If any files have changed collect to html string
     * @param array $arr the array of files
     * @return string $content - the table with file paths
     */
    public function sumupFiles($arr): string
    {
        $moduleDirName = $GLOBALS['xoopsModule']->getVar('dirname');
        xoops_loadLanguage('mail', $moduleDirName);
        $content = "<h2 class='filelistheader'>" . sprintf(MAIL_XOOPSSECURE_FILESCHANGED, (int)$this->helper->getConfig('XCISCRONINTERVAL')) . "</h2><br><br>";
        $content .= "<table align='center' class='filelisttable'>";
        $content .= "<tr><th>" . "Filepath" . "</th></tr>";
        foreach ($arr as $file) {
            $content .= "<tr><td>{$file}</td></tr>";
        }
        $content .= "</table>";
        return $content;
    }

    /**
     * Sends mail
     *
     * @param $info
     * @return bool according to success of send.
     */
    public function sendCronMail($info): bool
    {
        $ret = false;
        if ($this->timeForCron === true) {
            $moduleDirName = $GLOBALS['xoopsModule']->getVar('dirname');
            xoops_loadLanguage('mail', $moduleDirName);
            $date = date('m-d-Y H:i:s', time());
            $mail = xoops_getMailer();
            $message = '';

            $from = $GLOBALS['xoopsConfig']['adminmail'];
            $to = $GLOBALS['xoopsConfig']['adminmail'];

            $subject = MAIL_XOOPSSECURE_MAIL_FROM . " - " . $GLOBALS['xoopsConfig']['sitename'];

            $time = date("d-m-Y H:i:s", time());
            $link = XOOPS_ROOT_PATH . '/modules/xoopsSecure/admin/';

            $lnk = XOOPS_ROOT_PATH
                . '/modules/xoopsSecure/language/'
                . $GLOBALS['xoopsConfig']['language']
                . '/mail/mail_cron.tpl';

            $xoopsMailer = xoops_getMailer();
            $xoopsMailer->useMail();
            $xoopsMailer->setToEmails($GLOBALS['xoopsConfig']['adminmail']);
            $xoopsMailer->setFromEmail($GLOBALS['xoopsConfig']['adminmail']);
            $xoopsMailer->setTemplateDir(
                XOOPS_ROOT_PATH . '/modules/xoopssecure/language/' .
                $GLOBALS['xoopsConfig']['language'] . '/mail/'
            );
            $xoopsMailer->setTemplate('mail_cron.tpl');

            $xoopsMailer->assign('SENDERNAME', 'XoopsSecure');
            $xoopsMailer->assign('TIME', $time);
            $xoopsMailer->assign('LINK', $link);
            $xoopsMailer->assign('SITENAME', $GLOBALS['xoopsConfig']['sitename']);
            $xoopsMailer->assign('ISSUES', $info);

            $xoopsMailer->setSubject($subject);
            $xoopsMailer->multimailer->isHTML(true);
            $ret = $xoopsMailer->send();
        }
        if ($ret) {
            return true;
        } else {
            return false;
        }
    }
}
