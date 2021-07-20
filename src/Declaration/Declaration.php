<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration;

use ActiveRecord;
use arConnector;
use ilObject;
use ilObjUser;
use ilSrSelfDeclarationPlugin;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\ObjectConfig\ObjectConfig;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Declaration
 *
 * @package srag\Plugins\SrSelfDeclaration\Declaration
 */
class Declaration extends ActiveRecord
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    const TABLE_NAME = ilSrSelfDeclarationPlugin::PLUGIN_ID . "_decl";
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
    protected $declaration_id;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $effort = 0;
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $effort_reason = "";
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
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $text = "";
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $usr_id = 0;


    /**
     * Declaration constructor
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
     * @return int
     */
    public function getDeclarationId() : int
    {
        return $this->declaration_id;
    }


    /**
     * @param int $declaration_id
     */
    public function setDeclarationId(int $declaration_id) : void
    {
        $this->declaration_id = $declaration_id;
    }


    /**
     * @param string|null $lang_key
     * @param bool        $use_default_if_not_set
     *
     * @return string
     */
    public function getDefaultText(/*?*/ string $lang_key = null, bool $use_default_if_not_set = true) : string
    {
        return $this->getObjectConfig()->getDefaultText($lang_key = null, $use_default_if_not_set);
    }


    /**
     * @return int
     */
    public function getEffort() : int
    {
        return $this->effort;
    }


    /**
     * @param int $effort
     */
    public function setEffort(int $effort) : void
    {
        $this->effort = $effort;
    }


    /**
     * @return string
     */
    public function getEffortReason() : string
    {
        return $this->effort_reason;
    }


    /**
     * @param string $effort_reason
     */
    public function setEffortReason(string $effort_reason) : void
    {
        $this->effort_reason = $effort_reason;
    }


    /**
     * @return int
     */
    public function getMaxEffort() : int
    {
        return $this->getObjectConfig()->getMaxEffort();
    }


    /**
     * @return ilObject|null
     */
    public function getObj() : ?ilObject
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
    public function setObjId(int $obj_id) : void
    {
        $this->obj_id = $obj_id;
    }


    /**
     * @return ObjectConfig
     */
    public function getObjectConfig() : ObjectConfig
    {
        return self::srSelfDeclaration()->objectConfigs()->getObjectConfig($this->obj_id);
    }


    /**
     * @return string
     */
    public function getText() : string
    {
        return $this->text;
    }


    /**
     * @param string $text
     */
    public function setText(string $text) : void
    {
        $this->text = $text;
    }


    /**
     * @return ilObjUser|null
     */
    public function getUsr() : ?ilObjUser
    {
        return self::srSelfDeclaration()->objects()->getObjById($this->usr_id);
    }


    /**
     * @return int
     */
    public function getUsrId() : int
    {
        return $this->usr_id;
    }


    /**
     * @param int $usr_id
     */
    public function setUsrId(int $usr_id) : void
    {
        $this->usr_id = $usr_id;
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
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
            case "declaration_id":
            case "effort":
            case "obj_id":
            case "usr_id":
                return intval($field_value);

            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }
}
