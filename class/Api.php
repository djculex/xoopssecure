<?php

/**
 * Xoops xoopssecure module for xoops
 *
 * @copyright  2023 Culex (culex.dk)
 * @package    Xoopssecure
 * @sub-packet
 * @author     Culex <culex@culex.dk>
 * @license    GPL 2.0 or later
 * @since      1.0
 * @min_xoops  2.5.10
 */

namespace XoopsModules\Xoopssecure;

use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use XoopsModules\Xoopssecure\Db;
use RecursiveIteratorIterator;
use XoopsModules\Xoopssecure\MySQLBackup;
use ZipArchive;
use mysqli;

/**
 * Using Api
 *
 * This class will get access from various sources for different info,
 * Initially use will be with GitHub json api for updates on main script
 *
 * @author    Michael Albertsen <culex@culex.dk>
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class Api
{

    /**
     * @var
     */
    public $obj;

    /**
     * @var
     */
    public $result;

    /**
     * @var
     */
    public $url;

    /**
     * @var Helper
     */
    public $helper;

    /**
     * @var array|false|string[]
     */
    public array|false $repos;


    /**
     *
     */
    public function __construct()
    {
        if (null === $this->helper) {
            $this->helper = Helper::getInstance();
        }

        $this->repos = ($this->helper->getConfig('XCISCHECKUPDATEDREPOS') != '') ? preg_split("/\r\n|\n|\r/", $this->helper->getConfig('XCISCHECKUPDATEDREPOS')) : [];

    }


    /**
     * checking $this->repos if it is an array or not
     *
     * @return array $return
     */
    public function parseObjs(): array
    {
        $objs   = $this->repos;
        $return = [];
        if (is_array($objs)) {
            foreach ($objs as $ob) {
                $testObj = $this->parse($this->connect($ob));
                if (!empty($testObj)) {
                    $return[] = $testObj;
                }
            }
        }

        return $return;

    }


    /**
     * create php array of json obj
     *
     * @param  string $obj
     * @return array $info of info
     */
    public function parse($obj): array
    {
        $info = [];
        if (!isset($obj['message']) && !empty($obj)) {
            $info['url']      = $obj['html_url'];
            $info['version']  = self::keepOnlyNumbersEtc($obj['tag_name']);
            $info['name']     = $obj['name'];
            $info['date']     = date('d-m-Y', strtotime($obj['published_at']));
            $info['zip']      = $obj['zipball_url'];
            $info['bodytext'] = str_replace('-', '<br/>-', $obj['body']);
        }

        return $info;

    }


    /**
     * Strip a string for letters etc. keeping only numbers and dots
     *
     * @param  string $string text to be checked
     * @return string $string with replaced values
     */
    public function keepOnlyNumbersEtc($string): string
    {
        return preg_replace('/[^0-9.]/', '', $string);

    }


    /**
     * connect to github xoops latest release
     *
     * @param  string $url the link for repository
     * @return array|string json response
     */
    public function connect($url='https://api.github.com/repos/XOOPS/XoopsCore25/releases/latest'): array|string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_USERPWD, 'user.ca:password');
        curl_setopt($ch, CURLOPT_USERAGENT, 'CulexSecureApp');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $fetch = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }

        curl_close($ch);

        if (isset($error_msg)) {
            echo $error_msg;
        }

        return (!empty($fetch)) ? json_decode($fetch, true) : [];

    }


}
