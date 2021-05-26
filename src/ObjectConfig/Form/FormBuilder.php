<?php

namespace srag\Plugins\SrSelfDeclaration\ObjectConfig\Form;

use ilSrSelfDeclarationPlugin;
use srag\CustomInputGUIs\SrSelfDeclaration\FormBuilder\AbstractFormBuilder;
use srag\CustomInputGUIs\SrSelfDeclaration\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\SrSelfDeclaration\TabsInputGUI\MultilangualTabsInputGUI;
use srag\CustomInputGUIs\SrSelfDeclaration\TabsInputGUI\TabsInputGUI;
use srag\CustomInputGUIs\SrSelfDeclaration\TextAreaInputGUI\TextAreaInputGUI;
use srag\Plugins\SrSelfDeclaration\ObjectConfig\ObjectConfig;
use srag\Plugins\SrSelfDeclaration\ObjectConfig\ObjectConfigCtrl;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class FormBuilder
 *
 * @package  srag\Plugins\SrSelfDeclaration\ObjectConfig\Form
 */
class FormBuilder extends AbstractFormBuilder
{

    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var ObjectConfig
     */
    protected $object_config;


    /**
     * @inheritDoc
     *
     * @param ObjectConfigCtrl $parent
     * @param ObjectConfig     $object_config
     */
    public function __construct(ObjectConfigCtrl $parent, ObjectConfig $object_config)
    {
        $this->object_config = $object_config;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [
            ObjectConfigCtrl::CMD_UPDATE_OBJECT_CONFIG => self::plugin()->translate("save", ObjectConfigCtrl::LANG_MODULE)
        ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [
            "enabled"       => $this->object_config->isEnabled(),
            "default_texts" => $this->object_config->getDefaultTexts(),
            "max_effort"    => $this->object_config->getMaxEffort()
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            "enabled"       => self::dic()->ui()->factory()->input()->field()->checkbox(self::plugin()->translate("enabled", ObjectConfigCtrl::LANG_MODULE)),
            "default_texts" => new InputGUIWrapperUIInputComponent(new TabsInputGUI(self::plugin()->translate("default_text", ObjectConfigCtrl::LANG_MODULE))),
            "max_effort"    => self::dic()->ui()->factory()->input()->field()->numeric(self::plugin()->translate("max_effort", ObjectConfigCtrl::LANG_MODULE))
        ];
        MultilangualTabsInputGUI::generateLegacy($fields["default_texts"]->getInput(), [
            new TextAreaInputGUI(self::plugin()->translate("default_text", ObjectConfigCtrl::LANG_MODULE), "default_text")
        ], true);

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::plugin()->translate("config", ObjectConfigCtrl::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data)/* : void*/
    {
        $this->object_config->setEnabled(boolval($data["enabled"]));
        $this->object_config->setDefaultTexts((array) $data["default_texts"]);
        $this->object_config->setMaxEffort(intval($data["max_effort"]));

        self::srSelfDeclaration()->objectConfigs()->storeObjectConfig($this->object_config);
    }
}
