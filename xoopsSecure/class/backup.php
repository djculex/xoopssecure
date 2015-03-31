<?php
/**
 * Zipping files for backup
 *
 * This class will zip selected files or if selected all files
 * as well as MySql backup and enable downloading. 
 *
 * @copyright  2014 Culex.dk
 * @license    http://www.zend.com/license/3_0.txt   PHP License 5.6
 * @version    Release: @package_version@
 * @link       http://dev.zend.com/package/PackageName
 * @since      Class available since Release 1.0.0
 */ 
class xoopsSecureZipper {
	
	var $dirToBackup;
	var $dest;
	var $filename;
	var $archive;
	var $sqlname;
	var $backup_file_sql;
	var $dbname;
	
	function __construct() 
	{
		$this->dirToBackup = $this->caltypeofbackup ();
		$this->dest = XOOPS_ROOT_PATH."/uploads/backup/"; // make sure this directory exists!
		// make project backup folder
		if(!file_exists($this->dest)){
			mkdir($this->dest, 0775, true);
		}
		
		$this->filename = "backup_".date('d-m-Y__H_i').".zip";
		$this->archive = $this->dest.$this->filename;	
		$this->sqlname = XOOPS_DB_NAME;
		$this->backup_file_sql  = XOOPS_ROOT_PATH."/uploads/backup/tmp/sql.sql";
		if (!is_dir(dirname($this->backup_file_sql)))
		{
			mkdir(dirname($this->backup_file_sql), 0755, true);
		}
		$this->dbname = XOOPS_DB_NAME;
	}
	
	public static function caltypeofbackup ()
	{
		$config = xoopssecure_GetModuleOption($option='backuptype', $repmodule='xoopssecure');
		if ($config[0] == "Minimum") {
			return array(
				XOOPS_ROOT_PATH."/themes",
				XOOPS_ROOT_PATH."/uploads",
				XOOPS_ROOT_PATH."/xoops_data",
				XOOPS_ROOT_PATH."/xoops_lib",
				XOOPS_ROOT_PATH."/mainfile.php",
				XOOPS_ROOT_PATH."/install/page_end.php"
			);
		} elseif ($config[0] == "Full") {
			return array (
				XOOPS_ROOT_PATH
			);
		} elseif ($config[0] == "Custom") {
			return xoopssecure_StringToArray(
				xoopssecure_GetModuleOption(
					$option='backupcustomfiles', 
					$repmodule='xoopssecure')
				);
		} else {
			return array();
		}
	}
	
	public static function folderToZip($folder, $zipFile, $subfolder = null) {
		if ($zipFile == null) {
		 // no resource given, exit
		 return false;
		}
		if (is_file($folder)) {
			$zipFile->addFile($folder);
		} elseif (is_dir($folder) && $folder != XOOPS_ROOT_PATH."/uploads/backup") {
			$folder .= end(str_split($folder)) == "/" ? "" : "/";
			$subfolder .= end(str_split($subfolder)) == "/" ? "" : "/";
			$subfolder = (substr($subfolder,0,1)=='/')? substr($subfolder,1):$subfolder;
			$handle = opendir($folder);
				while ($f = readdir($handle)) {
					if ($f != "." && $f != "..") {
						if (is_file($folder . $f)) {
							if ($subfolder != null) {
								$zipFile->addFile($folder . $f, $subfolder . $f);
							} else {
								$zipFile->addFile($folder . $f);
							}
						} elseif (is_dir($folder . $f)) {
							if ($subfolder != null) {
								$zipFile->addEmptyDir($subfolder . $f);
								xoopsSecureZipper::folderToZip($folder . $f, $zipFile, $subfolder . $f);
							} else {
								$zipFile->addEmptyDir($f);
								xoopsSecureZipper::folderToZip($folder . $f, $zipFile, $f);
							}
						}
					}
				}
		}
	}


	function doZip ($archive, $dirToBackup)
	{
		global $xoopsUser, $xoTheme, $xoopsTpl,$xoopsLogger, $scan, $backup_file_sql;
		// create the zip
		$z = new ziparchive();
		$z->open($archive, ZIPARCHIVE::CREATE);
		
		/*Do backup of mysql
		
		$db = new DBBackup(array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'user' => XOOPS_DB_USER,
			'password' => XOOPS_DB_PASS,
			'database' => XOOPS_DB_NAME
		));
		$backup = $db->backup();
		if(!$backup['error']){
			// If there isn't errors, show the content
			// The backup will be at $var['msg']
			// You can do everything you want to. Like save in a file.
			$fp = fopen(XOOPS_ROOT_PATH."/uploads/backup/tmp/sql.sql", 'a+');
			fwrite($fp, $backup['msg']);
			fclose($fp);
			//echo nl2br($backup['msg']);
		} else {
			echo 'An error has ocurred.';
		} 
		*/
		foreach($dirToBackup as $d){
			self::folderToZip($d, $z, $d);
		}
		$z->addFile($this->backup_file_sql, "/mysqlbackup/sql.sql");
		$z->close();
	}
}

/**
 *
 * Use this class to do a backup of your database
 * @author Raul Souza Silva (raul.3k@gmail.com)
 * @category Database
 * @copyright No one. You can copy, edit, do anything you want. If you change anything to better, please let me know.
 *
 */
Class DBBackup {
    /**
     *
     * The host you will connect
     * @var String
     */
    private $host;
    /**
     *
     * The driver you will use to connect
     * @var String
     */
    private $driver;
    /**
     *
     * The user you will use to connect to a database
     * @var String
     */
    private $user;
    /**
     *
     * The password you will use to connect to a database
     * @var String
     */
    private $password;
    /**
     *
     * The database you will use to connect
     * @var String
     */
    private $dbName;
    /**
     *
     * String to connect to the database using PDO
     * @var String
     */
    private $dsn;

    /**
     *
     * Array with the tables of the database
     * @var Array
     */
    private $tables = array();

    /**
     *
     * Hold the connection
     * @var ObjectConnection
     */
    private $handler;
    /**
     *
     * Array to hold the errors
     * @var Array
     */
    private $error = array();

    /**
     *
     * The result string. String with all queries
     * @var String
     */
    private $final;

    /**
     *
     * The main function
     * @method DBBackup
     * @uses Constructor
     * @param Array $args{host, driver, user, password, database}
     * @example $db = new DBBackup(array('host'=>'my_host', 'driver'=>'bd_type(mysql)', 'user'=>'db_user', 'password'=>'db_password', 'database'=>'db_name'));
     */
    public function DBBackup($args){
        if(!$args['host']) $this->error[] = 'Parameter host missing';
        if(!$args['user']) $this->error[] = 'Parameter user missing';
        if(!isset($args['password'])) $this->error[] = 'Parameter password missing';
        if(!$args['database']) $this->error[] = 'Parameter database missing';
        if(!$args['driver']) $this->error[] = 'Parameter driver missing';

        if(count($this->error)>0){
            return;
        }

        $this->host = $args['host'];
        $this->driver = $args['driver'];
        $this->user = $args['user'];
        $this->password = $args['password'];
        $this->dbName = $args['database'];

        $this->final = 'CREATE DATABASE ' . $this->dbName.";\n\n";

        if($this->host=='localhost'){
            // We have a little issue in unix systems when you set the host as localhost
            $this->host = '127.0.0.1';
        }
        $this->dsn = $this->driver.':host='.$this->host.';dbname='.$this->dbName;

        $this->connect();
        $this->getTables();
        $this->generate();
    }

    /**
     *
     * Call this function to get the database backup
     * @example DBBackup::backup();
     */
    public function backup(){
        //return $this->final;
        if(count($this->error)>0){
            return array('error'=>true, 'msg'=>$this->error);
        }
        return array('error'=>false, 'msg'=>$this->final);
    }

    /**
     *
     * Generate backup string
     * @uses Private use
     */
    private function generate(){
        foreach ($this->tables as $tbl) {
            $this->final .= '--CREATING TABLE '.$tbl['name']."\n";
            $this->final .= $tbl['create'] . ";\n\n";
            $this->final .= '--INSERTING DATA INTO '.$tbl['name']."\n";
            $this->final .= $tbl['data']."\n\n\n";
        }
        $this->final .= '-- THE END'."\n\n";
    }

    /**
     *
     * Connect to a database
     * @uses Private use
     */
    private function connect(){
        try {
            $this->handler = new PDO($this->dsn, $this->user, $this->password);
        } catch (PDOException $e) {
            $this->handler = null;
            $this->error[] = $e->getMessage();
            return false;
        }
    }

    /**
     *
     * Get the list of tables
     * @uses Private use
     */
    private function getTables(){
        try {
            $stmt = $this->handler->query('SHOW TABLES');
            $tbs = $stmt->fetchAll();
            $i=0;
            foreach($tbs as $table){
                $this->tables[$i]['name'] = $table[0];
                $this->tables[$i]['create'] = $this->getColumns($table[0]);
                $this->tables[$i]['data'] = $this->getData($table[0]);
                $i++;
            }
            unset($stmt);
            unset($tbs);
            unset($i);

            return true;
        } catch (PDOException $e) {
            $this->handler = null;
            $this->error[] = $e->getMessage();
            return false;
        }
    }

    /**
     *
     * Get the list of Columns
     * @uses Private use
     */
    private function getColumns($tableName){
        try {
            $stmt = $this->handler->query('SHOW CREATE TABLE '.$tableName);
            $q = $stmt->fetchAll();
            $q[0][1] = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $q[0][1]);
            return $q[0][1];
        } catch (PDOException $e){
            $this->handler = null;
            $this->error[] = $e->getMessage();
            return false;
        }
    }

    /**
     *
     * Get the insert data of tables
     * @uses Private use
     */
    private function getData($tableName){
        try {
            $stmt = $this->handler->query('SELECT * FROM '.$tableName);
            $q = $stmt->fetchAll(PDO::FETCH_NUM);
            $data = '';
            foreach ($q as $pieces){
                foreach($pieces as &$value){
                    $value = htmlentities(addslashes($value));
                }
                $data .= 'INSERT INTO '. $tableName .' VALUES (\'' . implode('\',\'', $pieces) . '\');'."\n";
            }
            return $data;
        } catch (PDOException $e){
            $this->handler = null;
            $this->error[] = $e->getMessage();
            return false;
        }
    }
} 

?>
