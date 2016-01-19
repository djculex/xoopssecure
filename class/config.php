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
set_time_limit(999999);

/**
 * Class xoopsSecure_Config
 */
class xoopsSecure_Config
{
    
    //var $term;

    /**
     *
     */
    public function __construct()
    {
        //$this->term = isset($_GET['preferences_get_dir_term']) ? $_GET['preferences_get_dir_term'] : '';
    }

    /**
     * @param $term
     */
    public function getDirsForPrefs($term)
    {
        global $xoopsDB;
        //LOCATE('{$term}','filename') > 0
        $sql = "SELECT filename FROM ".$xoopsDB->prefix('xoopsecure_files').
               " ORDER BY filename";
        $result = $xoopsDB->queryF($sql);
        // loop through each zipcode returned and format the response for jQuery
        $data = array();
        while ($row = $xoopsDB->fetchArray($result)) {
            if (strpos(xoopssecure_cleanUrl($row['filename']), $term) !== false) {
                $data[] = array(
                        'label' => $row['filename'],
                        'value' => $row['filename']
                    );
            }
        }
         
        // jQuery wants JSON data
        echo json_encode($data);
        flush();
    }
}// end class
