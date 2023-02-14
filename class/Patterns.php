<?php

namespace XoopsModules\Xoopssecure;

use XoopsDatabase;
use XoopsModules\Xoopssecure;
use XoopsModules\Xoopssecure\Constants;
use function xoops_loadLanguage;

/**
 * Spam patterns for Xoops Modules Xoopssecure
 *
 * @author    Michael Albertsen <culex@culex.dk>
 * @credit    Bernard Toplak [WarpMax] <bernard@orion-web.hr>
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class Patterns
{
    /**
     * @var string|string[]
     */
    public $BadFileNames;
    /**
     * @var array|array[]
     */
    public $BadPatterns;
    /**
     * @var string
     */
    public $BadStrings;
    /**
     * @var string
     */
    public $BadFileScanners;

    /**
     * @var array
     */
    public array $Bads;

    /**
     * @var Helper|null
     */
    public $helper;

    /**
     * constructor
     *
     * @param XoopsDatabase|null $db init MySql connect
     * @param null $helper init helper
     */
    public function __construct(XoopsDatabase $db = null, $helper = null)
    {
        if (null === $helper) {
            $helper = Helper::getInstance();
        }
        $this->helper = $helper;
        $this->BadFileNames = $this->badFileNames();
        $this->BadPatterns = $this->badPatterns();
        $this->BadFileScanners = $this->badFileScanners();
        $this->BadStrings = $this->badStrings();
        $this->Bads = $this->arrayMerge();
    }

    /**
     * Return string of "bad file names"
     *
     * @return array|string
     */
    public function badFileNames(): array|string
    {
        xoops_loadLanguage('scanner', "xoopssecure");
        return [
            _SCAN_XOOPSSECURE_BFN_01 => 'ofc_upload_image.php',
            _SCAN_XOOPSSECURE_BFN_02 => 'r57.php',
            _SCAN_XOOPSSECURE_BFN_03 => 'c99.php',
            _SCAN_XOOPSSECURE_BFN_04 => 'c100.php',
            _SCAN_XOOPSSECURE_BFN_05 => 'phpinfo.php',
            _SCAN_XOOPSSECURE_BFN_06 => 'perlinfo.php'
        ];
    }

    /**
     * Return array of regexes and info to search for in file content
     *
     * @return array
     */
    public function badPatterns(): array
    {
        xoops_loadLanguage('scanner', "xoopssecure");
        return [
            [
                'preg_replace\s*\(\s*[\"\']\s*(\W)(?-s).*\1[imsxADSUXJu\s]*e[imsxADSUXJu\s]*[\"\'].*\)', // [0] = RegEx search pattern
                _SCAN_XOOPSSECURE_PAT01_TITLE, // [1] = Name / Title
                _SCAN_XOOPSSECURE_PAT01_DESC, // [2] = description
                'http://sucuri.net/malware/backdoor-phppreg_replaceeval', // Link for more description
                '0' // Rating
            ],
            [
                'c999*sh_surl',
                _SCAN_XOOPSSECURE_PAT02_TITLE,
                _SCAN_XOOPSSECURE_PAT02_DESC,
                'http://sucuri.net/malware/backdoor-phpc99045', // Link for more description
                '0' // Rating
            ],
            [
                'preg_match\s*\(\s*\"\s*/\s*bot\s*/\s*\"',
                _SCAN_XOOPSSECURE_PAT03_TITLE,
                _SCAN_XOOPSSECURE_PAT03_DESC,
                'http://sucuri.net/malware/backdoor-phpr5701', // Link for more description
                '0' // Rating
            ],
            [
                'eval[\s/\*\#]*\(stripslashes[\s/\*\#]*\([\s/\*\#]*\$_(REQUEST|POST|GET)\s*\[\s*\\\s*[\'\"]\s*asc\s*\\\s*[\'\"]',
                _SCAN_XOOPSSECURE_PAT04_TITLE,
                _SCAN_XOOPSSECURE_PAT04_DESC,
                'http://sucuri.net/malware/backdoor-phpgeneric07', // Link for more description
                '0' // Rating
            ],
            [
                'preg_replace\s*\(\s*[\"\'\”]\s*/\s*\.\s*\*\s*/\s*e\s*[\"\'\”]\s*,\s*[\"\'\”]\s*\\x65\\x76\\x61\\x6c',
                _SCAN_XOOPSSECURE_PAT05_TITLE,
                _SCAN_XOOPSSECURE_PAT05_DESC,
                'http://sucuri.net/malware/backdoor-phpfilesman02', // Link for more description
                '0' // Rating
            ],
            [
                '(include|require)(_once)*\s*[\"\'][\w\W\s/\*]*php://input[\w\W\s/\*]*[\"\']',
                _SCAN_XOOPSSECURE_PAT06_TITLE,
                _SCAN_XOOPSSECURE_PAT06_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                'data:;base64',
                _SCAN_XOOPSSECURE_PAT07_TITLE,
                _SCAN_XOOPSSECURE_PAT07_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                'RewriteCond\s*%\{HTTP_REFERER\}',
                _SCAN_XOOPSSECURE_PAT08_TITLE,
                _SCAN_XOOPSSECURE_PAT08_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                'jquery.min.php',
                _SCAN_XOOPSSECURE_PAT09_TITLE,
                _SCAN_XOOPSSECURE_PAT09_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                'GIF89a.*[\r\n]*.*<\?php',
                _SCAN_XOOPSSECURE_PAT10_TITLE,
                _SCAN_XOOPSSECURE_PAT10_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                '\$ip[\w\W\s/\*]*=[\w\W\s/\*]*getenv\(["\']REMOTE_ADDR["\']\);[\w\W\s/\*]*[\r\n]\$message',
                _SCAN_XOOPSSECURE_PAT11_TITLE,
                _SCAN_XOOPSSECURE_PAT11_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                '(?:(?:base64_decode|str_rot13)[\s\/\*\w\W\(]*){2,};',
                _SCAN_XOOPSSECURE_PAT12_TITLE,
                _SCAN_XOOPSSECURE_PAT12_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                '<\s*iframe',
                _SCAN_XOOPSSECURE_PAT13_TITLE,
                _SCAN_XOOPSSECURE_PAT13_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                'strrev[\s/\*\#]*\([\s/\*\#]*[\'"]\s*tressa\s*[\'"]\s*\)',
                _SCAN_XOOPSSECURE_PAT14_TITLE,
                _SCAN_XOOPSSECURE_PAT14_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                'is_writable[\s/\*\#]*\([\s/\*\#]*getcwd',
                _SCAN_XOOPSSECURE_PAT15_TITLE,
                _SCAN_XOOPSSECURE_PAT15_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                '(?:\\\\x[0-9A-Fa-f]{1,2}|\\\\[0-7]{1,3}){2,}',
                _SCAN_XOOPSSECURE_PAT16_TITLE,
                _SCAN_XOOPSSECURE_PAT16_DESC,
                '0' // Rating
            ],
            [
                '\$_F\s*=\s*__FILE__\s*;\s*\$_X\s*=',
                _SCAN_XOOPSSECURE_PAT17_TITLE,
                _SCAN_XOOPSSECURE_PAT17_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                '(?:passthru|shell_exec|proc_|popen)[\w\W\s/\*]*\([\s/\*\#\'\"\w\W\-\_]*(?:\$_GET|\$_POST|\$_REQUEST)',
                _SCAN_XOOPSSECURE_PAT18_TITLE,
                _SCAN_XOOPSSECURE_PAT18_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                'fsockopen\s*\(\s*[ \'\"](?:localhost|127\.0\.0\.1)[ \'\"]',
                _SCAN_XOOPSSECURE_PAT19_TITLE,
                _SCAN_XOOPSSECURE_PAT19_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                'fsockopen\s*\(.*,\s*[ \'\"](?:25|587|465|475|2525)[ \'\"]',
                _SCAN_XOOPSSECURE_PAT20_TITLE,
                _SCAN_XOOPSSECURE_PAT20_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                '(?:readfile|popen)\s*\(\s*[ \'\"]*\s*(?:file|http[s]*|ftp[s]*|php|zlib|data|glob|phar|ssh2|rar|ogg|expect|\$POST|\$GET|\$REQUEST)',
                _SCAN_XOOPSSECURE_PAT21_TITLE,
                _SCAN_XOOPSSECURE_PAT21_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                'array_(?:diff_ukey|diff_uassoc|intersect_uassoc|udiff_uassoc|udiff_assoc|uintersect_assoc|uintersect_uassoc)\s*\(.*(?:\$_REQUEST|\$_POST|\$_GET).*;',
                _SCAN_XOOPSSECURE_PAT22_TITLE,
                _SCAN_XOOPSSECURE_PAT22_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                '^(((.*)(=|;)(\s*)?)|((@|\s)*))(include|require)(_once)?\s*\(?("|\')https?://',
                _SCAN_XOOPSSECURE_PAT23_TITLE,
                _SCAN_XOOPSSECURE_PAT23_DESC,
                '', // Link for more description
                '0' // Rating
            ],
            [
                '\\\\x([abcdef0-9]{2}){3,}',
                _SCAN_XOOPSSECURE_PAT24_TITLE,
                _SCAN_XOOPSSECURE_PAT24_DESC,
                '', // Link for more description
                '0' // Rating
            ]
        ];
    }

    /**
     * Return string of "bad file scanners"
     *
     * @return string
     */
    public function badFileScanners(): string
    {
        return
            'base64_decode|gzdecode|gzdeflate|gzuncompress|gzcompress|readgzfile|zlib_decode|zlib_encode|gzfile' .
            '|gzget|gzpassthru|iframe|strrev|lzw_decompress|passthru|shell_exec|proc_|popen|proc_open|bruteforce' .
            '|brute force|edoced_46esab|parse_ini_file|display:none|display=none|display=\'none\'';
    }

    /**
     * Return string of "bad strings"
     *
     * @return string
     */
    public function badStrings(): string
    {
        return 'r0nin|m0rtix|iskorpitx|upl0ad|r57shell' .
            '|c99shell|shellbot|phpshell|void\.ru|phpremoteview' .
            '|directmail|bash_history|cwings|vandal|bitchx|eggdrop' .
            '|guardservices|psybnc|dalnet|undernet|vulnscan|spymeta' .
            '|raslan58|Webshell|str_rot13|FilesMan|FilesTools|Web Shell' .
            '|ifrm|bckdrprm|hackmeplz|wrgggthhd|WSOsetcookie|Hmei7' .
            '|Inbox Mass Mailer|HackTeam|Hackeado|Janissaries|Miyachung' .
            '|ccteam|Adminer|OOO000000|$GLOBALS|findsysfolder|makeret.ru' .
            '|c0d3d by|C0de For|Perl Auto Rooter Perl Script|curl_multi_exec' .
            '|create_function|b374k|Web Shell by boff|Web Shell by oRb|devilzShell' .
            '|Shell by Mawar_Hitam|N3tshell|Storm7Shell|Locus7Shell|private Shell by m4rco' .
            '|w4ck1ng shell|blackhat Shell|FaTaLisTiCz_Fx Fx29Sh|th3w1tch Shell' .
            '|Goog1e_analistRCBot|Antihutan|Attijari|ByroeNet|cpftpcrack|KAdot|MulCiShell' .
            '|PHPJackal|POSTpe80|ReZulT|SRCrew|Safe0ver|SimShell|Storm7|Surrogafier|TuR334Vl' .
            '|UberCracker|Vrs-hCk|Cyb3rDevils|DxShell|DataCha0s|Forever2008|InsideTeam|ItsmYarD' .
            '|aKpuMPiN|Xnuxer|cgitelnet|ShellHook|Perlovga|Mirccrack|CookStealer|Bypassshell|r00t3r' .
            '|zerocnbct|Ylyshell|egyspider|evilc0der|violaoeucc0101|iTSecTeam|putr4XtReme|aZRaiL' .
            '|cbLorD|91.239.15.61|_YM82iAN|XXRANDOMXX|_POST..n13e558|envir0nn@yahoo.com|$bogel' .
            '|c999sh_surl|xVebaPURjEzLc|AQSP|ANTIPIDERSIA|uzanc|xadpritox|blackboy007|nacomb13' .
            '|Devilzc0de|8a4bf282852bf4c49e17f0951f645e72|k2ll33d|tsxpwkpqbk|HackerBooty' .
            '|JE8wMDBPME8wMD1mb3BlbigkT09PME8w|Rawckerhead|sPMQhNQMR9XM05Cvsbg1DTE5vRJiEnn|UnixCrew' .
            '|HolaKo|4xI0DHgMAmwFstDDeTdg26|fb0979fa651bb915d186ac0fddcd1bc6' .
            '|fb621f5060b9f65acf8eb4232e3024140dea2b34|xunzhaocangjingkong|123321|WwW.7jyewu.Cn' .
            '|zbazszez64z_zdeczodze|nr9Sb1ehwpGJoIkcy5LEUxtRVxEzGglYpr5xIy|HaniXavi|k_i@outlook.com' .
            '|hanikadi0@gmail.com|naruto@localhost.com|JSUlJSUlJbEk9J3NldF90aW1lX2xpbWl0Jzs' .
            '|Dz93hR3fWlPVRtrH2txMf+DrmGvyq4tsaa|IRCBot|Locus7s|c100 Shell|Project x2300|Captain Crunch Team' .
            '|Shadow & Preddy|w4ck1ng|milw0rm|Rootshell.c|Snailsor,FuYu,BloodSword,Cnqing|ASPXSpy' .
            '|Iranian Hackers|Hossein Asgary|SimAttacker|simorgh-ev|BuqX@HotMail.Com|GrayHatz Hacking' .
            '|Kacak FSO|grayhatz.org|TurkGuvenligi|r57.biz|evalinfect|1dt.w0lf|http://ghc.ru|evilc0der.com';
    }

    /**
     * Merger arrays
     *
     * @return array
     */
    public function arrayMerge(): array
    {
        $s = [];
        return array_merge(
            $this->BadFileNames,
            $this->BadPatterns,
            explode('|', $this->BadFileScanners),
            explode('|', $this->BadStrings)
        );
    }
}
