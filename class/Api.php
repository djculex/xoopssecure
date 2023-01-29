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
 * Using Api
 *
 * This class will get access from various sources for diffent info,
 * Initially use will be with github json api for updates on main script
 *
 * @author    Michael Albertsen <culex@culex.dk>
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class Api
{
    public $obj;
    public $result;
    public $url;
    public $helper;
    public $repos;

    public function __construct()
    {
        if (null === $this->helper) {
            $this->helper = Helper::getInstance();
        }
        $this->repos = ($this->helper->getConfig('XCISCHECKUPDATEDREPOS') != '') ?
            preg_split("/\r\n|\n|\r/", $this->helper->getConfig('XCISCHECKUPDATEDREPOS')) : [];
    }

    /** connext to github xoops latest release
     *
     * @param $url the link for reposit
     * @return json response
     */
    public function connect($url = "https://api.github.com/repos/XOOPS/XoopsCore25/releases/latest")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_USERPWD, 'user.ca:password');
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

    /** create php array of json obj
     *
     * @param json $obj
     * @return array $info of info
     */
    public function parse($obj)
    {
        $info = [];
        if (!isset($obj['message']) && !empty($obj)) {
            $info['url']        = $obj['html_url'];
            $info['version']    = self::keepOnlyNumbersEtc($obj['tag_name']);
            $info['name']       = $obj['name'];
            $info['date']       = date("d-m-Y", strtotime($obj['published_at']));
            $info['zip']        = $obj['zipball_url'];
            $info['bodytext'] = str_replace("-", '<br/>-', $obj['body']);
        }
        return $info;
    }

    /** checking $this->repos if it is an array or not
     *
     * @return array $return
     */
    public function parseObjs()
    {
        $objs = $this->repos;
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

    /** Strip a string for letters etc keeping only numbers and dots
     *
     * @param $string text to be checked
     * @return string $string with replaced values
     */
    public function keepOnlyNumbersEtc($string)
    {
        return preg_replace('/[^0-9.]/', '', $string);
    }
}
