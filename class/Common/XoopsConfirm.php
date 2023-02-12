<?php

namespace XoopsModules\xoopssecure\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Module:  Xoopssecure
 *
 * @author    Michael Albertsen
 * @since     21.11.2022
 * @copyright Michael Albertsen culex@culex.dk
 * @version   1.1
 * @license   GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 */

use Xmf\Request;
use XoopsFormButton;
use XoopsFormElementTray;
use XoopsFormHidden;
use XoopsFormLabel;
use XoopsModules\xoopssecure;
use XoopsThemeForm;
use function define;
use function defined;
use function xoops_load;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object XoopsConfirm
 */
class XoopsConfirm
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
     * @public function getXoopsConfirm
     * @return XoopsThemeForm
     */
    public function getFormXoopsConfirm()
    {
        //in order to be accessable from user and admin area this should be place in language common.php
        if (!defined('CO_MYMODULE_DELETE_CONFIRM')) {
            define('CO_MYMODULE_DELETE_CONFIRM', 'Confirm delete');
            define('CO_MYMODULE_DELETE_LABEL', 'Do you really want to delete:');
        }

        // Get Theme Form
        if ('' === $this->action) {
            $this->action = Request::getString('REQUEST_URI', '', 'SERVER');
        }
        if ('' === $this->title) {
            $this->title = CO_MYMODULE_DELETE_CONFIRM;
        }
        if ('' === $this->label) {
            $this->label = CO_MYMODULE_DELETE_LABEL;
        }

        xoops_load('XoopsFormLoader');
        $form = new XoopsThemeForm($this->title, 'formXoopsConfirm', $this->action, 'post', true);
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
