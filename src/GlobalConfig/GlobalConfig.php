<?php

namespace srag\Plugins\SrSelfDeclaration\GlobalConfig;

use ActiveRecord;
use arConnector;
use ilSrSelfDeclarationPlugin;
use srag\CustomInputGUIs\SrSelfDeclaration\TabsInputGUI\MultilangualTabsInputGUI;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class GlobalConfig
 *
 * @package srag\Plugins\SrSelfDeclaration\GlobalConfig
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class GlobalConfig extends ActiveRecord
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    const TABLE_NAME = ilSrSelfDeclarationPlugin::PLUGIN_ID . "_glbl_cnfg";
    /**
     * @var array
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $default_texts = [];
    /**
     * @var bool
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       1
     * @con_is_notnull   true
     */
    protected $enabled = false;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     * @con_sequence     true
     */
    protected $global_config_id;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $max_effort = 0;


    /**
     * GlobalConfig constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @inheritDoc
     */
    public function getConnectorContainerName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @param string|null $lang_key
     * @param bool        $use_default_if_not_set
     *
     * @return string
     */
    public function getDefaultText(/*?*/ string $lang_key = null, bool $use_default_if_not_set = true) : string
    {
        return strval(MultilangualTabsInputGUI::getValueForLang($this->default_texts, $lang_key, "default_text", $use_default_if_not_set));
    }


    /**
     * @return array
     */
    public function getDefaultTexts() : array
    {
        return $this->default_texts;
    }


    /**
     * @param array $default_texts
     */
    public function setDefaultTexts(array $default_texts)/* : void*/
    {
        $this->default_texts = $default_texts;
    }


    /**
     * @return int
     */
    public function getGlobalConfigId() : int
    {
        return $this->global_config_id;
    }


    /**
     * @param int $global_config_id
     */
    public function setGlobalConfigId(int $global_config_id)/* : void*/
    {
        $this->global_config_id = $global_config_id;
    }


    /**
     * @return int
     */
    public function getMaxEffort() : int
    {
        return $this->max_effort;
    }


    /**
     * @param int $max_effort
     */
    public function setMaxEffort(int $max_effort)/* : void*/
    {
        $this->max_effort = $max_effort;
    }


    /**
     * @return bool
     */
    public function isEnabled() : bool
    {
        return $this->enabled;
    }


    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)/* : void*/
    {
        $this->enabled = $enabled;
    }


    /**
     * @param string $default_text
     * @param string $lang_key
     */
    public function setDefaultText(string $default_text, string $lang_key)/* : void*/
    {
        MultilangualTabsInputGUI::setValueForLang($this->default_texts, $default_text, $lang_key, "default_text");
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case "enabled":
                return ($field_value ? 1 : 0);

            case "default_texts":
                return json_encode($field_value);

            default:
                return parent::sleep($field_name);
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "enabled":
                return boolval($field_value);

            case "global_config_id":
            case "max_effort":
                return intval($field_value);

            case "default_texts":
                return (array) json_decode($field_value, true);

            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }
}
