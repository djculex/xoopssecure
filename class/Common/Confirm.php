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

use Xmf\Request;
use XoopsFormButton;
use XoopsFormElementTray;
use XoopsFormHidden;
use XoopsFormLabel;
use XoopsThemeForm;
use function basename;
use function constant;
use function define;
use function defined;
use function mb_strtoupper;
use function xoops_load;
use const _NO;
use const _YES;

/**
 * Module:  Xoopssecure
 *
 * @author    Michael Albertsen
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 *
 *
 * Example:
 * $customConfirm = new Common\Confirm(
 * ['ok' => 1, 'item_id' => $itemId, 'op' => 'delete'],
 * $_SERVER['REQUEST_URI'],
 * \sprintf(\_MA_MYMODULE_FORM_SURE_DELETE,
 * $itemsObj->getCaption()));
 * $form = $customConfirm->getFormConfirm();
 * $GLOBALS['xoopsTpl']->assign('form', $form->render());
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Confirm
 */
class Confirm
{
    private $hiddens = [];
    private $action = '';
    private $title = '';
    private $label = '';
    private $object = '';

    /**
     * @public function constructor class
     * @param  $hiddens
     * @param  $action
     * @param  $object
     * @param string $title
     * @param string $label
     */
    public function __construct($hiddens, $action, $object, $title = '', $label = '')
    {
        $this->hiddens = $hiddens;
        $this->action = $action;
        $this->object = $object;
        $this->title = $title;
        $this->label = $label;
    }

    /**
     * @public function getFormConfirm
     * @return XoopsThemeForm
     */
    public function getFormConfirm()
    {
        $moduleDirName = basename(__DIR__);
        $moduleDirNameUpper = mb_strtoupper($moduleDirName);
        //in order to be accessable from user and admin area this should be place in language common.php
        if (!defined('CO_' . $moduleDirNameUpper . '_DELETE_CONFIRM')) {
            define('CO_' . $moduleDirNameUpper . '_DELETE_CONFIRM', 'Confirm delete');
            define('CO_' . $moduleDirNameUpper . '_DELETE_LABEL', 'Do you really want to delete:');
        }

        // Get Theme Form
        if ('' === $this->action) {
            $this->action = Request::getString('REQUEST_URI', '', 'SERVER');
        }
        if ('' === $this->title) {
            $this->title = constant('CO_' . $moduleDirNameUpper . '_DELETE_CONFIRM');
        }
        if ('' === $this->label) {
            $this->label = constant('CO_' . $moduleDirNameUpper . '_DELETE_LABEL');
        }

        xoops_load('XoopsFormLoader');
        $form = new XoopsThemeForm($this->title, 'formConfirm', $this->action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormLabel($this->label, $this->object));
        //hiddens
        foreach ($this->hiddens as $key => $value) {
            $form->addElement(new XoopsFormHidden($key, $value));
        }
        $form->addElement(new XoopsFormHidden('ok', 1));
        $buttonTray = new XoopsFormElementTray('');
        $buttonTray->addElement(new XoopsFormButton('', 'confirm_submit', _YES, 'submit'));
        $buttonBack = new XoopsFormButton('', 'confirm_back', _NO, 'button');
        $buttonBack->setExtra('onclick="history.go(-1);return true;"');
        $buttonTray->addElement($buttonBack);
        $form->addElement($buttonTray);

        return $form;
    }
}
