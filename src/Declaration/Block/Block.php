<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration\Block;

use ilBlockGUI;
use ilObject;
use ilSrSelfDeclarationPlugin;
use ilUIPluginRouterGUI;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Declaration\Declaration;
use srag\Plugins\SrSelfDeclaration\Declaration\DeclarationCtrl;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Block
 *
 * @package srag\Plugins\SrSelfDeclaration\Declaration\Block
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Block extends ilBlockGUI
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
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
     * DeclarationBlock constructor
     *
     * @param int $obj_ref_id
     */
    public function __construct(int $obj_ref_id)
    {
        parent::__construct();

        $this->obj_ref_id = $obj_ref_id;
    }


    /**
     * @inheritDoc
     */
    public function fillDataSection()/*: void*/
    {
        $this->setDataSection($this->getDeclaration());
    }


    /**
     * @inheritDoc
     */
    public function getBlockType() : string
    {
        return ilSrSelfDeclarationPlugin::PLUGIN_ID;
    }


    /**
     * @inheritDoc
     */
    public function getHTML() : string
    {
        if (self::srSelfDeclaration()->declarations()->hasAccessToDeclarationOfObjectForUser($this->obj_ref_id, self::dic()->user()->getId())) {
            $this->obj = self::srSelfDeclaration()->objects()->getObjByRefId($this->obj_ref_id);

            $this->declaration = self::srSelfDeclaration()->declarations()->getDeclarationOfObjectForUser($this->obj->getId(), self::dic()->user()->getId());

            $this->initBlock();

            return parent::getHTML();
        } else {
            return "";
        }
    }


    /**
     * @return string
     */
    protected function getDeclaration() : string
    {
        $output = [
            nl2br(implode("\n", array_map("htmlspecialchars", explode("\n", $this->declaration->getText()))), false)
        ];

        if (!self::srSelfDeclaration()->declarations()->hasDeclaration($this->declaration)) {
            $output[] = "<br>";

            self::dic()->ctrl()->setParameterByClass(DeclarationCtrl::class, DeclarationCtrl::GET_PARAM_REF_ID, $this->obj->getRefId());

            $output[] = self::dic()->ui()->factory()->button()->standard(self::plugin()->translate("set", DeclarationCtrl::LANG_MODULE),
                self::dic()->ctrl()->getLinkTargetByClass([ilUIPluginRouterGUI::class, DeclarationCtrl::class], DeclarationCtrl::CMD_EDIT_DECLARATION));
        }

        return self::output()->getHTML($output);
    }


    /**
     * @inheritDoc
     */
    protected function getLegacyContent() : string
    {
        return $this->getDeclaration();
    }


    /**
     *
     */
    protected function initBlock()/*: void*/
    {
        $this->setTitle(self::plugin()->translate("declaration", DeclarationCtrl::LANG_MODULE));

        if (self::version()->is6()) {
            $this->new_rendering = true;
        }
    }


    /**
     * @inheritDoc
     */
    protected function isRepositoryObject() : bool
    {
        return false;
    }
}
