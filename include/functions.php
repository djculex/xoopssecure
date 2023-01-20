<?php
declare(strict_types=1);

/**
 * Xoops Xoopssecure module for xoops
 *
 * @copyright 2021 XOOPS Project (https://xoops.org)
 * @license   GPL 2.0 or later
 * @package   Xoopssecure
 * @since     1.0
 * @min_xoops 2.5.11
 * @author    Culex - Email:culex@culex.dk - Website:https://www.culex.dk
 */

/**
 * Get the number of stats from the sub categories of a category or sub topics of or topic
 *
 * @param  $mytree
 * @param  $stats
 * @param  $entries
 * @param  $cid
 * @return int
 */
function xoopssecure_NumbersOfEntries($mytree, $stats, $entries, $cid)
{
    $count = 0;
    if (\in_array($cid, $stats)) {
        $child = $mytree->getAllChild($cid);
        foreach (\array_keys($entries) as $i) {
            if ($entries[$i]->getVar('id') == $cid) {
                $count++;
            }
            foreach (\array_keys($child) as $j) {
                if ($entries[$i]->getVar('id') == $j) {
                    $count++;
                }
            }
        }
    }
    return $count;
}

/**
 * Add content as meta tag to template
 *
 * @param  $content
 * @return void
 */
function xoopssecure_MetaKeywords($content)
{
    global $xoopsTpl, $xoTheme;
    $myts = MyTextSanitizer::getInstance();
    $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
    if (isset($xoTheme) && \is_object($xoTheme)) {
        $xoTheme->addMeta('meta', 'keywords', \strip_tags($content));
    } else {    // Compatibility for old Xoops versions
        $xoopsTpl->assign('xoops_meta_keywords', \strip_tags($content));
    }
}

/**
 * Add content as meta description to template
 *
 * @param  $content
 * @return void
 */
function xoopssecure_MetaDescription($content)
{
    global $xoopsTpl, $xoTheme;
    $myts = MyTextSanitizer::getInstance();
    $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
    if (isset($xoTheme) && \is_object($xoTheme)) {
        $xoTheme->addMeta('meta', 'description', \strip_tags($content));
    } else {    // Compatibility for old Xoops versions
        $xoopsTpl->assign('xoops_meta_description', \strip_tags($content));
    }
}

/**
 * Rewrite all url
 *
 * @param  string $module module name
 * @param  array  $array  array
 * @param  string $type   type
 * @return null|string $type    string replacement for any blank case
 */
function xoopssecure_RewriteUrl($module, $array, $type = 'content')
{
    $comment = '';
    $helper = \XoopsModules\Xoopssecure\Helper::getInstance();
    $statsHandler = $helper->getHandler('stats');
    $lenght_id = $helper->getConfig('lenght_id');
    $rewrite_url = $helper->getConfig('rewrite_url');

    if (0 != $lenght_id) {
        $id = $array['content_id'];
        while (\strlen($id) < $lenght_id) {
            $id = '0' . $id;
        }
    } else {
        $id = $array['content_id'];
    }

    if (isset($array['topic_alias']) && $array['topic_alias']) {
        $topic_name = $array['topic_alias'];
    } else {
        $topic_name = xoopssecure_Filter(xoops_getModuleOption('static_name', $module));
    }

    switch ($rewrite_url) {
        case 'none':
            if ($topic_name) {
                 $topic_name = 'topic=' . $topic_name . '&amp;';
            }
            $rewrite_base = '/modules/';
            $page = 'page=' . $array['content_alias'];
            return \XOOPS_URL . $rewrite_base . $module . '/' . $type . '.php?' . $topic_name . 'id=' . $id . '&amp;' . $page . $comment;
            break;

        case 'rewrite':
            if ($topic_name) {
                $topic_name .= '/';
            }
            $rewrite_base = xoops_getModuleOption('rewrite_mode', $module);
            $rewrite_ext = xoops_getModuleOption('rewrite_ext', $module);
            $module_name = '';
            if (xoops_getModuleOption('rewrite_name', $module)) {
                $module_name = xoops_getModuleOption('rewrite_name', $module) . '/';
            }
            $page = $array['content_alias'];
            $type .= '/';
            $id .= '/';
            if ('content/' === $type) {
                $type = '';
            }
            if ('comment-edit/' === $type || 'comment-reply/' === $type || 'comment-delete/' === $type) {
                return \XOOPS_URL . $rewrite_base . $module_name . $type . $id . '/';
            }

            return \XOOPS_URL . $rewrite_base . $module_name . $type . $topic_name  . $id . $page . $rewrite_ext;
            break;

        case 'short':
            if ($topic_name) {
                $topic_name .= '/';
            }
             $rewrite_base = xoops_getModuleOption('rewrite_mode', $module);
             $rewrite_ext = xoops_getModuleOption('rewrite_ext', $module);
             $module_name = '';
            if (xoops_getModuleOption('rewrite_name', $module)) {
                $module_name = xoops_getModuleOption('rewrite_name', $module) . '/';
            }
             $page = $array['content_alias'];
             $type .= '/';
            if ('content/' === $type) {
                $type = '';
            }
            if ('comment-edit/' === $type || 'comment-reply/' === $type || 'comment-delete/' === $type) {
                return \XOOPS_URL . $rewrite_base . $module_name . $type . $id . '/';
            }

            return \XOOPS_URL . $rewrite_base . $module_name . $type . $topic_name . $page . $rewrite_ext;
            break;
    }
    return null;
}

/**
 * Replace all escape, character, ... for display a correct url
 *
 * @param  string $url  string to transform
 * @param  string $type string replacement for any blank case
 * @return string $url
 */
function xoopssecure_Filter($url, $type = '')
{

    // Get regular expression from module setting. default setting is : `[^a-z0-9]`i
    $helper = \XoopsModules\Xoopssecure\Helper::getInstance();
    $statsHandler = $helper->getHandler('stats');
    $regular_expression = $helper->getConfig('regular_expression');

    $url = \strip_tags($url);
    $url .= \preg_replace('`\[.*\]`U', '', $url);
    $url .= \preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $url);
    $url .= \htmlentities($url, ENT_COMPAT, 'utf-8');
    $url .= \preg_replace('`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', "\1", $url);
    $url .= \preg_replace([$regular_expression, '`[-]+`'], '-', $url);
    $url = ('' == $url) ? $type : \strtolower(\trim($url, '-'));
    return $url;
}

/**
 * Return array of folders and subfolders in class to use in preloads/autoloader
 *
 * @return array $folders
 */
function xoopssecure_GetClassSubFolders(string $path): array
{
    $directories = [];
    $items = scandir($path);
    foreach ($items as $item) {
        if ($item == '..' || $item == '.') {
            continue;
        }
        if (is_dir($path . '/' . $item)) {
            $directories[] = str_replace("\\", DIRECTORY_SEPARATOR, trim($item));
        }
        if (is_file($path . '/' . $item)) {
            $directories[] = str_replace("\\", DIRECTORY_SEPARATOR, trim($item));
        }
    }
    array_push($directories, '');
    return $directories;
}


/**
 * Translate constant with/without arguments
 *
 * Looks through defined constants for key[string] and
 * returns parsed string using arguments if any used
 * ---------
 * Example
 * 1) echo xoopssecure_TranslateString('
         _SCAN_XOOPSSECURE_WRONFILEPERMISSION_FIXED',
         XOOPS_VAR_PATH . '/mainfile.php', '0666', "0444", "0444", "heyhey"
      );
 * 2) echo xoopssecure_TranslateString('XOOPS_URL', $unustedArg=3, 'Heyhey', 1, 2, 3);
 *
 * result
 * 1) C:/xampp/htdocs/xoops_test/htdocs/xoops_data/mainfile.php had file permission : 0666,
        recommended setting is 0444. Xoopssecure has fixed permissions to.: 0444
 * 2) http://localhost/xoops_test/htdocs
 * ---------
 * @author Michael Albertsen (culex@culex.dk)
 * @param $text string constant to look for
 * @return string $returnText
 */
function xoopssecure_TranslateString($text)
{
    $def = get_defined_constants();
    $returnText = $def[$text];
    $args = func_get_args();
    // if there is arguments
    if ($args > 0) {
        array_shift($args);
        return vsprintf($returnText, $args);
    } else {
        // return org. string
        return $returnText;
    }
}

/** Get a list of dirs based on path
 *
 * @param string $dir the path to start from
 * @param string $needle ext of files to getAllChild
 * @return array
 */
function xoopssecure_listdirs($dir, $needle = null)
{
    $subDir = array();
    $directories = array_filter(glob($dir), 'is_dir');
    $subDir = array_merge($subDir, $directories);
    foreach ($directories as $directory) {
        $subDir = array_merge($subDir, xoopssecure_listdirs($directory . '/*'));
    }
    return $subDir;
}

/** 
 * function to explode string to array
 *
 * @param string $string
 * @return array $array
 */
function xoopssecure_StringToArray($string)
{
    return ($string != '') ? preg_split("/\r\n|\n|\r/", $string) : array();
}

/**
 * Returns array of minimum files and folders to be used as default in config
 *
 * @return array $data containing paths
 */
function xoopssecure_backupFilesMin()
{
    return array(
       XOOPS_ROOT_PATH . "/modules/" => XOOPS_ROOT_PATH . "/modules",
       XOOPS_ROOT_PATH . "/uploads/" => XOOPS_ROOT_PATH . "/uploads",
       XOOPS_ROOT_PATH . "/modules/" => XOOPS_ROOT_PATH . "/modules",
       XOOPS_ROOT_PATH . "/themes/" => XOOPS_ROOT_PATH . "/themes",
       XOOPS_PATH . "/" => XOOPS_PATH,
       XOOPS_VAR_PATH . "/" => XOOPS_VAR_PATH,
       XOOPS_ROOT_PATH . "/mainfile.php" => XOOPS_ROOT_PATH . "/mainfile.php"
    );
}

 /**
  * Returns scantype based on value
  *
  * Translate value to corresponding string
  *
  * @param value is the scantype fron db log
  * @return string
  */
function xoopssecure_scantypeToString($val)
{
    switch ($val) {
        case '0':
            return _SCAN_XOOPSSECURE_MALLWARE_SHORTTITLE_FULL;
        break;
        case '1':
            return _SCAN_XOOPSSECURE_MALLWARE_SHORTTITLE_PERM;
        break;
        case '2':
            return _SCAN_XOOPSSECURE_MALLWARE_SHORTTITLE_INDX;
        break;
        case '3':
            return _SCAN_XOOPSSECURE_MALLWARE_SHORTTITLE_MALLW;
        break;
        case '4':
            return _SCAN_XOOPSSECURE_MALLWARE_SHORTTITLE_CODES;
        break;
    }
}
