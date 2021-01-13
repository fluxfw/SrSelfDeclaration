<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration;

use ilLink;
use ilObject;
use ilSrSelfDeclarationPlugin;
use ilUtil;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class DeclarationCtrl
 *
 * @package           srag\Plugins\SrSelfDeclaration\Declaration
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrSelfDeclaration\Declaration\DeclarationCtrl: ilUIPluginRouterGUI
 */
class DeclarationCtrl
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const CMD_BACK = "back";
    const CMD_FILL_DECLARATION = "fillDeclaration";
    const CMD_SAVE_DECLARATION = "saveDeclaration";
    const GET_PARAM_REF_ID = "ref_id";
    const LANG_MODULE = "declaration";
    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    const TAB_EDIT_DECLARATION = "edit_declaration";
    /**
     * @var Declaration
     */
    protected $declaration;
    /**
     * @var ilObject
     */
    protected $obj;
    /**
     * @var int
     */
    protected $obj_ref_id;


    /**
     * DeclarationCtrl constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/* : void*/
    {
        $this->obj_ref_id = intval(filter_input(INPUT_GET, self::GET_PARAM_REF_ID));

        if (!self::srSelfDeclaration()->declarations()->hasAccessToDeclarationOfObjectForUser($this->obj_ref_id, self::dic()->user()->getId())) {
            die();
        }

        $this->obj = self::srSelfDeclaration()->objects()->getObjByRefId($this->obj_ref_id);

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_REF_ID);

        $this->declaration = self::srSelfDeclaration()->declarations()->getDeclarationOfObjectForUser($this->obj->getId(), self::dic()->user()->getId());

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_BACK:
                    case self::CMD_FILL_DECLARATION:
                    case self::CMD_SAVE_DECLARATION:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function back()/* : void*/
    {
        self::dic()->ctrl()->redirectToURL(ilLink::_getLink($this->obj->getRefId()));
    }


    /**
     *
     */
    protected function fillDeclaration()/* : void*/
    {
        if (self::srSelfDeclaration()->declarations()->hasDeclaration($this->declaration)) {
            self::dic()->ctrl()->redirect($this, self::CMD_BACK);

            return;
        }

        self::dic()->tabs()->activateTab(self::TAB_EDIT_DECLARATION);

        $form = self::srSelfDeclaration()->declarations()->factory()->newFormBuilderInstance($this, $this->declaration);

        self::output()->output($form, true);
    }


    /**
     *
     */
    protected function saveDeclaration()/* : void*/
    {
        if (self::srSelfDeclaration()->declarations()->hasDeclaration($this->declaration)) {
            self::dic()->ctrl()->redirect($this, self::CMD_BACK);

            return;
        }

        self::dic()->tabs()->activateTab(self::TAB_EDIT_DECLARATION);

        $form = self::srSelfDeclaration()->declarations()->factory()->newFormBuilderInstance($this, $this->declaration);

        if (!$form->storeForm()) {
            self::output()->output($form, true);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }


    /**
     *
     */
    protected function setTabs()/* : void*/
    {
        self::dic()->tabs()->clearTargets();
    }
}
