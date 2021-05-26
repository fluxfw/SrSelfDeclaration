<?php

namespace srag\Plugins\SrSelfDeclaration\GlobalConfig\Form;

use ilSrSelfDeclarationPlugin;
use srag\CustomInputGUIs\SrSelfDeclaration\FormBuilder\AbstractFormBuilder;
use srag\CustomInputGUIs\SrSelfDeclaration\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\SrSelfDeclaration\TabsInputGUI\MultilangualTabsInputGUI;
use srag\CustomInputGUIs\SrSelfDeclaration\TabsInputGUI\TabsInputGUI;
use srag\CustomInputGUIs\SrSelfDeclaration\TextAreaInputGUI\TextAreaInputGUI;
use srag\Plugins\SrSelfDeclaration\GlobalConfig\GlobalConfig;
use srag\Plugins\SrSelfDeclaration\GlobalConfig\GlobalConfigCtrl;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class FormBuilder
 *
 * @package  srag\Plugins\SrSelfDeclaration\GlobalConfig\Form
 */
class FormBuilder extends AbstractFormBuilder
{

    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var GlobalConfig
     */
    protected $global_config;


    /**
     * @inheritDoc
     *
     * @param GlobalConfigCtrl $parent
     * @param GlobalConfig     $global_config
     */
    public function __construct(GlobalConfigCtrl $parent, GlobalConfig $global_config)
    {
        $this->global_config = $global_config;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [
            GlobalConfigCtrl::CMD_UPDATE_GLOBAL_CONFIG => self::plugin()->translate("save", GlobalConfigCtrl::LANG_MODULE)
        ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [
            "enabled"       => $this->global_config->isEnabled(),
            "default_texts" => $this->global_config->getDefaultTexts(),
            "max_effort"    => $this->global_config->getMaxEffort()
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            "enabled"       => self::dic()->ui()->factory()->input()->field()->checkbox(self::plugin()->translate("enabled", GlobalConfigCtrl::LANG_MODULE)),
            "default_texts" => new InputGUIWrapperUIInputComponent(new TabsInputGUI(self::plugin()->translate("default_text", GlobalConfigCtrl::LANG_MODULE))),
            "max_effort"    => self::dic()->ui()->factory()->input()->field()->numeric(self::plugin()->translate("max_effort", GlobalConfigCtrl::LANG_MODULE))
        ];
        MultilangualTabsInputGUI::generateLegacy($fields["default_texts"]->getInput(), [
            new TextAreaInputGUI(self::plugin()->translate("default_text", GlobalConfigCtrl::LANG_MODULE), "default_text")
        ], true);

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::plugin()->translate("config", GlobalConfigCtrl::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data)/* : void*/
    {
        $this->global_config->setEnabled(boolval($data["enabled"]));
        $this->global_config->setDefaultTexts((array) $data["default_texts"]);
        $this->global_config->setMaxEffort(intval($data["max_effort"]));

        self::srSelfDeclaration()->globalConfig()->storeGlobalConfig($this->global_config);
    }
}
