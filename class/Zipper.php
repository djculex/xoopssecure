<?php
namespace XoopsModules\Xoopssecure;

use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use XoopsModules\Xoopssecure\Db;
use RecursiveIteratorIterator;
use XoopsModules\Xoopssecure\MySQLBackup;
use ZipArchive;
use mysqli;

/**
 * Zipping files for backup
 *
 * This class will zip selected files or if selected all files
 * as well as MySql backup and enable downloading.
 *
 * Module:  Xoopssecure
 *
 * @author    Michael Albertsen <culex@culex.dk>
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */

class Zipper extends \XoopsPersistableObjectHandler
{
    public $dirToBackup;
    public $dest;
    public $filename;
    public $archive;
    public $sqlname;
    public $backup_file_sql;
    public $helper;

    public function __construct()
    {
        if (null === $this->helper) {
            $this->helper = Helper::getInstance();
        }

        $this->dirToBackup = $this->caltypeofbackup(); // Get special folders to backup
        $this->dest = XOOPS_ROOT_PATH . "/uploads/backup/"; // make sure this directory exists!
        // make project backup folder
        if (!file_exists($this->dest)) {
            mkdir($this->dest, 0777, true);
        }

        $this->filename = "backup_" . date('d-m-Y__H_i') . ".zip"; // Name Zip to create
        $this->archive = $this->dest . $this->filename; // Where it is placed
        $this->sqlname = XOOPS_DB_NAME; // Name of xoops database
        $this->backup_file_sql  = XOOPS_ROOT_PATH . "/uploads/backup/tmp/sql.sql"; //Name of sql to create
        if (!is_dir(dirname($this->backup_file_sql))) {
            mkdir(dirname($this->backup_file_sql), 0777, true);
        }
    }

    /** 
     * Call functions to create array of files to backup
     *
     * @return array
     */
    public static function caltypeofbackup()
    {
        $helper = \XoopsModules\xoopssecure\Helper::getInstance();
        $config = $helper->getConfig('XCISBACKUPTYPE');
        if ($config[0] == "Minimum") {
            return array(
                XOOPS_ROOT_PATH . "/themes",
                XOOPS_ROOT_PATH . "/uploads",
                XOOPS_ROOT_PATH . "/xoops_data",
                XOOPS_ROOT_PATH . "/xoops_lib",
                XOOPS_ROOT_PATH . "/mainfile.php",
                XOOPS_ROOT_PATH . "/install/page_end.php"
            );
        } elseif ($config[0] == "Full") {
            return array(
                XOOPS_ROOT_PATH
            );
        } elseif ($config[0] == "Custom") {
            return xoopssecure_StringToArray($helper->getConfig('XCISBACKUPCUSTOMFILES'));
        } else {
            return array();
        }
    }

    /**
     * Zipping folders for backup
     *
     * @param  $folder path of folder
     * @param  $zipFile Name of zip
     * @param  null $subfolder
     * @return bool if zip is done with success return true
     */
    public function folderToZip($folder, $zipFile, $subfolder = null)
    {
        if ($zipFile === null) {
            // no resource given, exit
            return false;
        }
        if (is_file($folder)) {
            $zipFile->addFile($folder, $this->stripPathPart($folder));
        } elseif (is_dir($folder) && $folder != XOOPS_ROOT_PATH . "/uploads/backup") {
            $tmpfo = str_split($folder);
            $folder = (end($tmpfo) == "/") ? $folder . "" : $folder . "/";
            $tmpsubfo = str_split($subfolder);
            $subfolder = (end($tmpsubfo) == "/") ? $subfolder . "" : $subfolder . "/";
            $subfolder = (substr($subfolder, 0, 1) == '/') ? substr($subfolder, 1) : $subfolder;
            try {
                $handle = opendir($folder);
            }catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
            while ($f = readdir($handle)) {
                if ($f != "." && $f != "..") {
                    if (is_file($folder . $f)) {
                        if ($subfolder !== null) {
                            try {
                                $zipFile->addFile($folder . $f, $this->stripPathPart($subfolder) . $f);
                            } catch (Exception $e) {
                                echo 'Caught exception: ',  $e->getMessage(), "\n";
                            }
                        } else {
                            try {
                                $zipFile->addFile($folder . $this->stripPathPart($f));
                            } catch (Exception $e) {
                                echo 'Caught exception: ',  $e->getMessage(), "\n";
                            }
                        }
                    } elseif (is_dir($folder . $f)) {
                        if ($subfolder !== null) {
                            $zipFile->addEmptyDir($this->stripPathPart($subfolder . $f));
                            try {
                                Zipper::folderToZip($folder . $f, $zipFile, $this->stripPathPart($subfolder . $f));
                            } catch (Exception $e) {
                                echo 'Caught exception: ',  $e->getMessage(), "\n";
                            }
                        } else {
                            $zipFile->addEmptyDir($this->stripPathPart($f));
                            try {
                            Zipper::folderToZip($folder . $f, $zipFile, $this->stripPathPart($f));
                            } catch (Exception $e) {
                                echo 'Caught exception: ',  $e->getMessage(), "\n";
                            }
                        }
                    }
                }
            }
        }
    }

    /** 
     * Strip rootpath from string to create shorter dir struc in zip file
     *
     * @param string $string The string containing the path info
     * @return string $string shorteded by prev. param
     */
    public function stripPathPart($string)
    {
        return str_replace(XOOPS_ROOT_PATH . "/", "htdocs/", $string);
    }

    /** 
     * Create sql and copy files ready for doing zip
     *
     * @param string $archive the name of archieve
     * @param string $dirToBackup send paths for zip
     */
    public function doZip($archive, $dirToBackup)
    {
        // create the zip
        $z = new ZipArchive();
        $z->open($archive, ZIPARCHIVE::CREATE);

        //Use Pdo to log on to db
        $dbc = new MySQLBackup(
            'localhost',    // HOST
            XOOPS_DB_USER,  // Username
            XOOPS_DB_PASS,  // Password
            XOOPS_DB_NAME,  // Database name
            ''              // Port, not needed
        );
        $this->backup_file_sql = XOOPS_ROOT_PATH . '/uploads/backup/dump ' . date('Y-m-d H-i');
        $dbc->setFilename($this->backup_file_sql);
        $dbc->dump();

        foreach ($dirToBackup as $d) {
            self::folderToZip($d, $z, $d);
        }
        $z->addFile($this->backup_file_sql . ".sql", "mysqlbackup/" . basename($this->backup_file_sql . ".sql"));
        $z->close();
        \unlink($this->backup_file_sql . ".sql"); // Delete now zipped sql file from temp
        \rmdir(XOOPS_ROOT_PATH . '/uploads/backup/tmp'); // Delete temp folder
        header('Content-disposition: attachment; filename="' . basename($this->archive) . '.zip"');
        header('Content-type: application/zip');
        readfile($this->archive);
    }
}
