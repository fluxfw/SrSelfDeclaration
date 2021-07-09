<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration\Block;

use ilBlockGUI;
use ilObject;
use ilSrSelfDeclarationPlugin;
use ilUIPluginRouterGUI;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Declaration\Declaration;
use srag\Plugins\SrSelfDeclaration\Declaration\DeclarationCtrl;
use srag\Plugins\SrSelfDeclaration\ObjectConfig\ObjectConfigCtrl;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Block
 *
 * @package srag\Plugins\SrSelfDeclaration\Declaration\Block
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
        $fields = [];
        $buttons = [];

        if (self::srSelfDeclaration()->declarations()->hasDeclaration($this->declaration)) {
            $fields[self::plugin()->translate("text", DeclarationCtrl::LANG_MODULE)] = $this->declaration->getText();

            if ($this->declaration->getMaxEffort() !== $this->declaration->getEffort()) {
                $fields[self::plugin()->translate("max_effort", ObjectConfigCtrl::LANG_MODULE)] = $this->declaration->getMaxEffort();
            }

            $fields[self::plugin()->translate("effort", DeclarationCtrl::LANG_MODULE)] = $this->declaration->getEffort();

            $fields[self::plugin()->translate("effort_reason", DeclarationCtrl::LANG_MODULE)] = $this->declaration->getEffortReason();
        } else {
            $fields[self::plugin()->translate("default_text", ObjectConfigCtrl::LANG_MODULE)] = $this->declaration->getDefaultText();

            $fields[self::plugin()->translate("max_effort", ObjectConfigCtrl::LANG_MODULE)] = $this->declaration->getMaxEffort();

            self::dic()->ctrl()->setParameterByClass(DeclarationCtrl::class, DeclarationCtrl::GET_PARAM_REF_ID, $this->obj->getRefId());
            $buttons[] = self::dic()->ui()->factory()->button()->standard(self::plugin()->translate("fill", DeclarationCtrl::LANG_MODULE),
                self::dic()->ctrl()->getLinkTargetByClass([ilUIPluginRouterGUI::class, DeclarationCtrl::class], DeclarationCtrl::CMD_FILL_DECLARATION));
        }

        return self::output()->getHTML([
            self::dic()->ui()->factory()->listing()->descriptive(array_filter(array_map(function ($value) : string {
                return nl2br(implode("\n", array_map("htmlspecialchars", explode("\n", strval($value)))), false);
            }, $fields))),
            $buttons
        ]);
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

        $this->new_rendering = true;
    }


    /**
     * @inheritDoc
     */
    protected function isRepositoryObject() : bool
    {
        return false;
    }
}
