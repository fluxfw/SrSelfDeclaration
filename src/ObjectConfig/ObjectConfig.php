<?php

namespace srag\Plugins\SrSelfDeclaration\ObjectConfig;

use ActiveRecord;
use arConnector;
use ilObject;
use ilSrSelfDeclarationPlugin;
use srag\CustomInputGUIs\SrSelfDeclaration\TabsInputGUI\MultilangualTabsInputGUI;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class ObjectConfig
 *
 * @package srag\Plugins\SrSelfDeclaration\ObjectConfig
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ObjectConfig extends ActiveRecord
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    const TABLE_NAME = ilSrSelfDeclarationPlugin::PLUGIN_ID . "_config";
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
    protected $config_id;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $default_effort = 0;
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
     */
    protected $obj_id = 0;


    /**
     * ObjectConfig constructor
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
    public function getMaxEffort() : int
    {
        return $this->default_effort;
    }


    /**
     * @return ilObject|null
     */
    public function getObj()/* : ?ilObject*/
    {
        return self::srSelfDeclaration()->objects()->getObjById($this->obj_id);
    }


    /**
     * @return int
     */
    public function getObjId() : int
    {
        return $this->obj_id;
    }


    /**
     * @param int $obj_id
     */
    public function setObjId(int $obj_id)/* : void*/
    {
        $this->obj_id = $obj_id;
    }


    /**
     * @return int
     */
    public function getObjectConfigId() : int
    {
        return $this->config_id;
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
     * @param int $max_effort
     */
    public function setMaxEffort(int $max_effort)/* : void*/
    {
        $this->default_effort = $max_effort;
    }


    /**
     * @param int $object_config_id
     */
    public function setObjectConfigId(int $object_config_id)/* : void*/
    {
        $this->config_id = $object_config_id;
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

            case "config_id":
            case "default_effort":
            case "obj_id":
                return intval($field_value);

            case "default_texts":
                return (array) json_decode($field_value, true);

            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }
}
