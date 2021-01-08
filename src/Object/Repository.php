<?php

namespace srag\Plugins\SrSelfDeclaration\Object;

use ilObject;
use ilObjectFactory;
use ilSrSelfDeclarationPlugin;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrSelfDeclaration\Object
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const OBJECT_TYPES = ["crs"];
    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;
    /**
     * @var bool[]
     */
    protected $has_read_access = [];
    /**
     * @var bool[]
     */
    protected $has_write_access = [];
    /**
     * @var ilObject[]
     */
    protected $objs_by_obj_id = [];
    /**
     * @var ilObject[]
     */
    protected $objs_by_ref_id = [];
    /**
     * @var bool[]
     */
    protected $supports_obj_type = [];


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @internal
     */
    public function dropTables()/* : void*/
    {

    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @param int $obj_id
     *
     * @return ilObject|null
     */
    public function getObjById(int $obj_id)/* : ?ilObject*/
    {
        if ($this->objs_by_obj_id[$obj_id] === null) {
            $this->objs_by_obj_id[$obj_id] = ilObjectFactory::getInstanceByObjId($obj_id, false);
        }

        return ($this->objs_by_obj_id[$obj_id] ?: null);
    }


    /**
     * @param int $obj_ref_id
     *
     * @return ilObject|null
     */
    public function getObjByRefId(int $obj_ref_id)/* : ?ilObject*/
    {
        if ($this->objs_by_ref_id[$obj_ref_id] === null) {
            $this->objs_by_ref_id[$obj_ref_id] = ilObjectFactory::getInstanceByRefId($obj_ref_id, false);
            $this->objs_by_obj_id[$this->objs_by_ref_id[$obj_ref_id]->getId()] = $this->objs_by_ref_id[$obj_ref_id];
        }

        return ($this->objs_by_ref_id[$obj_ref_id] ?: null);
    }


    /**
     * @param int $obj_ref_id
     * @param int $usr_id
     *
     * @return bool
     */
    public function hasReadAccess(int $obj_ref_id, int $usr_id) : bool
    {
        $cache_key = $obj_ref_id . "_" . $usr_id;

        if ($this->has_read_access[$cache_key] === null) {
            $this->has_read_access[$cache_key] = self::dic()->access()->checkAccessOfUser($usr_id, "read", "", $obj_ref_id);
        }

        return $this->has_read_access[$cache_key];
    }


    /**
     * @param int $obj_ref_id
     * @param int $usr_id
     *
     * @return bool
     */
    public function hasWriteAccess(int $obj_ref_id, int $usr_id) : bool
    {
        $cache_key = $obj_ref_id . "_" . $usr_id;

        if ($this->has_write_access[$cache_key] === null) {
            $this->has_write_access[$cache_key] = self::dic()->access()->checkAccessOfUser($usr_id, "write", "", $obj_ref_id);
        }

        return $this->has_write_access[$cache_key];
    }


    /**
     * @internal
     */
    public function installTables()/* : void*/
    {

    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function supportsObjType(int $obj_ref_id) : bool
    {
        if ($this->supports_obj_type[$obj_ref_id] === null) {
            $this->supports_obj_type[$obj_ref_id] = in_array(self::dic()->objDataCache()->lookupType(self::dic()->objDataCache()->lookupObjId($obj_ref_id)), self::OBJECT_TYPES);
        }

        return $this->supports_obj_type[$obj_ref_id];
    }
}
