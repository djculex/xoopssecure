<?php

declare(strict_types=1);

if (!defined('XOOPS_ROOT_PATH')) {
    include \dirname(__DIR__, 3) . '/mainfile.php';
}

require_once \dirname(__DIR__, 3) . '/modules/xoopssecure/include/functions.php';

/** Loading all classes from class folder
 * @see https://www.php-fig.org/psr/psr-4/examples/
 */
spl_autoload_register(
    static function ($class): void {
        // Get recursive foldernames from class
        $dirs = xoopssecure_GetClassSubFolders(XOOPS_ROOT_PATH . '/modules/xoopssecure/class/', $results = []);
        // foreach them to include them all inc subfolders
        foreach ($dirs as $dir) {
                
                // project-specific namespace prefix
                $prefix = 'XoopsModules\\' . \ucfirst(\basename(\dirname(__DIR__)));
                // base directory for the namespace prefix
                $baseDir = \dirname(__DIR__) . '/class/' . $dir;
                //echo $baseDir . "<br>";
                // does the class use the namespace prefix?
                $len = \mb_strlen($prefix);
            if (0 !== \strncmp($prefix, $class, $len)) {
                return;
            }

                // get the relative class name
                $relativeClass = \mb_substr($class, $len);
                // replace the namespace prefix with the base directory, replace namespace
                // separators with directory separators in the relative class name, append
                // with .php
                $file = $baseDir . \str_replace('\\', DIRECTORY_SEPARATOR, trim($relativeClass)) . '.php';
                
                // if the file exists, require it
            if (\file_exists($file)) {
                include $file;
            }
        }
    }
);
