<?php

declare(strict_types=1);

namespace XoopsModules\xoopssecure\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use function array_keys;
use function count;

/**
 * Feedback plugin for xoops modules
 *
 * @author    Michael Albertsen
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */
trait ModuleStats
{
    /**
     * @param Configurator $configurator
     * @param array $moduleStats
     * @return array
     */
    public static function getModuleStats($configurator, $moduleStats)
    {
        if (count($configurator->moduleStats) > 0) {
            foreach (array_keys($configurator->moduleStats) as $i) {
                $moduleStats[$i] = $configurator->moduleStats[$i];
            }
        }

        return $moduleStats;
    }
}
