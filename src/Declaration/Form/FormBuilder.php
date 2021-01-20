<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration\Form;

use Closure;
use ILIAS\UI\Implementation\Component\Input\Field\Group;
use ilNonEditableValueGUI;
use ilSrSelfDeclarationPlugin;
use srag\CustomInputGUIs\SrSelfDeclaration\FormBuilder\AbstractFormBuilder;
use srag\CustomInputGUIs\SrSelfDeclaration\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\Plugins\SrSelfDeclaration\Declaration\Declaration;
use srag\Plugins\SrSelfDeclaration\Declaration\DeclarationCtrl;
use srag\Plugins\SrSelfDeclaration\ObjectConfig\ObjectConfigCtrl;
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
            "text"          => $this->declaration->getText(),
            "max_effort"    => $this->declaration->getMaxEffort(),
            "effort"        => $this->declaration->getEffort(),
            "effort_reason" => $this->declaration->getEffortReason()
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            "text"          => self::dic()->ui()->factory()->input()->field()->textarea(self::plugin()->translate("text", DeclarationCtrl::LANG_MODULE))->withRequired(true),
            "max_effort"    => new InputGUIWrapperUIInputComponent(new ilNonEditableValueGUI(self::plugin()->translate("max_effort", ObjectConfigCtrl::LANG_MODULE))),
            "effort"        => self::dic()->ui()->factory()->input()->field()->numeric(self::plugin()->translate("effort", DeclarationCtrl::LANG_MODULE))->withRequired(true),
            "effort_reason" => self::dic()->ui()->factory()->input()->field()->textarea(self::plugin()->translate("effort_reason", DeclarationCtrl::LANG_MODULE))->withByline(self::plugin()
                ->translate("effort_reason_info", DeclarationCtrl::LANG_MODULE))
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
        $this->declaration->setEffort(intval($data["effort"]));
        $this->declaration->setEffortReason(strval($data["effort_reason"]));

        self::srSelfDeclaration()->declarations()->storeDeclaration($this->declaration);
    }


    /**
     * @inheritDoc
     */
    protected function validateData(array $data) : bool
    {
        $ok = true;

        $inputs = $this->form->getInputs()["form"]->getInputs();

        if (empty(intval($data["effort"]))) {
            $inputs["effort"] = $inputs["effort"]->withError(self::dic()->language()->txt("msg_input_is_required"));

            $ok = false;
        }

        if (intval($data["effort"]) !== $this->declaration->getMaxEffort()) {
            if (intval($data["effort"]) !== $this->declaration->getMaxEffort()) {
                $inputs["effort"] = $inputs["effort"]->withError(self::dic()->language()->txt("form_msg_value_too_high"));

                $ok = false;
            }

            if (empty(strval($data["effort_reason"]))) {
                $inputs["effort_reason"] = $inputs["effort_reason"]->withError(self::dic()->language()->txt("msg_input_is_required"));

                $ok = false;
            }
        }

        Closure::bind(function (array $inputs)/* : void*/ {
            $this->inputs = $inputs;
        }, $this->form->getInputs()["form"], Group::class)($inputs);

        return $ok;
    }
}
