<?php

declare(strict_types=1);

namespace XoopsModules\Xoopssecure;

use RuntimeException;
use XoopsDatabaseFactory;
use XoopsMySQLDatabase;
use XoopsObjectHandler;
use XoopsPersistableObjectHandler;
use function basename;
use function class_exists;
use function dirname;
use function ucfirst;

/**
 * Helper class
 *
 * @author    Michael Albertsen <culex@culex.dk>
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */

/**
 * Class Helper
 */
class Helper extends \Xmf\Module\Helper
{
    /**
     * @var bool
     */
    public $debug;

    /**
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
        $moduleDirName = basename(dirname(__DIR__));
        parent::__construct($moduleDirName);
    }

    /**
     * @return string
     */
    public function getDirname(): string
    {
        return $this->dirname;
    }

    /**
     * Get an Object Handler
     *
     * @param string $name name of handler to load
     *
     * @return bool|XoopsObjectHandler|XoopsPersistableObjectHandler
     */
    public function getHandler($name)
    {
        $class = __NAMESPACE__ . '\\' . ucfirst($name) . 'Handler';
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }
        /** @var XoopsMySQLDatabase $db */
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $helper = self::getInstance();
        $ret = new $class($db, $helper);
        $this->addLog("Getting handler '{$name}'");

        return $ret;
    }

    /**
     * @param bool $debug
     *
     * @return Helper
     */
    public static function getInstance($debug = false)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($debug);
        }

        return $instance;
    }
}
