<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration\Form;

use ilSrSelfDeclarationPlugin;
use srag\CustomInputGUIs\SrSelfDeclaration\FormBuilder\AbstractFormBuilder;
use srag\Plugins\SrSelfDeclaration\Declaration\Declaration;
use srag\Plugins\SrSelfDeclaration\Declaration\DeclarationCtrl;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class FormBuilder
 *
 * @package  srag\Plugins\SrSelfDeclaration\Declaration\Form
 *
 * @author   studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FormBuilder extends AbstractFormBuilder
{

    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var Declaration
     */
    protected $declaration;


    /**
     * @inheritDoc
     *
     * @param DeclarationCtrl $parent
     * @param Declaration     $declaration
     */
    public function __construct(DeclarationCtrl $parent, Declaration $declaration)
    {
        $this->declaration = $declaration;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [
            DeclarationCtrl::CMD_SAVE_DECLARATION => self::plugin()->translate("save", DeclarationCtrl::LANG_MODULE),
            DeclarationCtrl::CMD_BACK             => self::plugin()->translate("cancel", DeclarationCtrl::LANG_MODULE),
        ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [
            "text" => $this->declaration->getText()
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            "text" => self::dic()->ui()->factory()->input()->field()->textarea(self::plugin()->translate("text", DeclarationCtrl::LANG_MODULE))->withRequired(true),
        ];

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::plugin()->translate("declaration", DeclarationCtrl::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data)/* : void*/
    {
        $this->declaration->setText(strval($data["text"]));

        self::srSelfDeclaration()->declarations()->storeDeclaration($this->declaration);
    }
}
