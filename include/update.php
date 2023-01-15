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

/** Update module
 * @param $module
 * @param null $prev_version
 *
 * @return bool|null
 */
function xoops_module_update_xoopssecure($module, $prev_version = null)
{
    $ret = null;
    /*
    if ($prev_version < 10) {
        //enter code or call your function
    }
    */
    $ret = xoopssecure_check_db($module);

    //check upload directory
    include_once __DIR__ . '/install.php';
    $ret = xoops_module_install_xoopssecure($module);

    $errors = $module->getErrors();
    if (!empty($errors)) {
        \print_r($errors);
    }

    return $ret;
}

/**
 * @param $module
 *
 * @return bool
 */
function xoopssecure_check_db($module)
{
    $ret = true;
    //insert here code for database check

    /*
    // Example: update table (add new field)
    $table   = $GLOBALS['xoopsDB']->prefix('xoopssecure_images');
    $field   = 'img_exif';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` TEXT NULL AFTER `img_state`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    // Example: create new table
    $table   = $GLOBALS['xoopsDB']->prefix('xoopssecure_categories');
    $check   = $GLOBALS['xoopsDB']->queryF("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='$table'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        // create new table 'xoopssecure_categories'
        $sql = "CREATE TABLE `$table` (
                  `cat_id`        INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `cat_text`      VARCHAR(100)    NOT NULL DEFAULT '',
                  `cat_date`      INT(8)          NOT NULL DEFAULT '0',
                  `cat_submitter` INT(8)          NOT NULL DEFAULT '0',
                  PRIMARY KEY (`cat_id`)
                ) ENGINE=InnoDB;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when creating table '$table'.");
            $ret = false;
        }
    }
    */
    return $ret;
}
