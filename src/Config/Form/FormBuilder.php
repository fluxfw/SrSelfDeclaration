<?php

namespace srag\Plugins\SrSelfDeclaration\Config\Form;

use ilSrSelfDeclarationPlugin;
use srag\CustomInputGUIs\SrSelfDeclaration\FormBuilder\AbstractFormBuilder;
use srag\CustomInputGUIs\SrSelfDeclaration\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\SrSelfDeclaration\TabsInputGUI\MultilangualTabsInputGUI;
use srag\CustomInputGUIs\SrSelfDeclaration\TabsInputGUI\TabsInputGUI;
use srag\CustomInputGUIs\SrSelfDeclaration\TextAreaInputGUI\TextAreaInputGUI;
use srag\Plugins\SrSelfDeclaration\Config\Config;
use srag\Plugins\SrSelfDeclaration\Config\ConfigCtrl;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class FormBuilder
 *
 * @package  srag\Plugins\SrSelfDeclaration\Config\Form
 *
 * @author   studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FormBuilder extends AbstractFormBuilder
{

    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var Config
     */
    protected $config;


    /**
     * @inheritDoc
     *
     * @param ConfigCtrl $parent
     * @param Config     $config
     */
    public function __construct(ConfigCtrl $parent, Config $config)
    {
        $this->config = $config;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [
            ConfigCtrl::CMD_UPDATE_CONFIG => self::plugin()->translate("save", ConfigCtrl::LANG_MODULE)
        ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [
            "enabled"       => $this->config->isEnabled(),
            "default_texts" => $this->config->getDefaultTexts(),
            "max_effort"    => $this->config->getMaxEffort()
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            "enabled"       => self::dic()->ui()->factory()->input()->field()->checkbox(self::plugin()->translate("enabled", ConfigCtrl::LANG_MODULE)),
            "default_texts" => new InputGUIWrapperUIInputComponent(new TabsInputGUI(self::plugin()->translate("default_text", ConfigCtrl::LANG_MODULE))),
            "max_effort"    => self::dic()->ui()->factory()->input()->field()->numeric(self::plugin()->translate("max_effort", ConfigCtrl::LANG_MODULE))
        ];
        MultilangualTabsInputGUI::generateLegacy($fields["default_texts"]->getInput(), [
            new  TextAreaInputGUI(self::plugin()->translate("default_text", ConfigCtrl::LANG_MODULE), "default_text")
        ], true);

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::plugin()->translate("config", ConfigCtrl::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data)/* : void*/
    {
        $this->config->setEnabled(boolval($data["enabled"]));
        $this->config->setDefaultTexts((array) $data["default_texts"]);
        $this->config->setMaxEffort(intval($data["max_effort"]));

        self::srSelfDeclaration()->configs()->storeConfig($this->config);
    }
}
