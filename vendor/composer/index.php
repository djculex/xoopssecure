<?php
/**
 * Index.php file.
 * 
 * Using an index file in every folder not having any is one way of preventing directory browsing
 * The best way is to remove the Indexes directive from your httpd.conf, 
this is however not always an option on for instance hosted server 
 * 
 */


/**
  * This index.php file will show an error '404 not found' when entering this folder
  * and was created : 24-02-2023 16:23:02 by a xoopsSecure scan/create.

  * @package      \XoopsModules\xoopssecure
  * @copyright    The XOOPS Project (https://xoops.org)
  * @copyright    2023 Culex
  * @author       Michael Albertsen (http://culex.dk) <culex@culex.dk>
  * @link         https://github.com/XoopsModules25x/xoopssecure
  * @since        1.0
  */

header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);