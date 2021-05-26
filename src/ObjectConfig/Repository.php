<?php

namespace srag\Plugins\SrSelfDeclaration\ObjectConfig;

use ilSrSelfDeclarationPlugin;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrSelfDeclaration\ObjectConfig
 */
final class Repository
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;
    /**
     * @var bool[]
     */
    protected $has_access = [];
    /**
     * @var bool[]
     */
    protected $is_enabled = [];
    /**
     * @var ObjectConfig[]
     */
    protected $object_configs = [];


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
        self::dic()->database()->dropTable(ObjectConfig::TABLE_NAME, false);
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
     * @return ObjectConfig
     */
    public function getObjectConfig(int $obj_id) : ObjectConfig
    {
        if ($this->object_configs[$obj_id] === null) {
            $this->object_configs[$obj_id] = ObjectConfig::where(["obj_id" => $obj_id])->first();

            if ($this->object_configs[$obj_id] === null) {
                $this->object_configs[$obj_id] = $this->factory()->newInstance();

                $this->object_configs[$obj_id]->setObjId($obj_id);

                $global_config = self::srSelfDeclaration()->globalConfig()->getGlobalConfig();

                $this->object_configs[$obj_id]->setEnabled($global_config->isEnabled());

                $this->object_configs[$obj_id]->setDefaultTexts($global_config->getDefaultTexts());

                $this->object_configs[$obj_id]->setMaxEffort($global_config->getMaxEffort());
            }
        }

        return $this->object_configs[$obj_id];
    }


    /**
     * @param int $obj_ref_id
     * @param int $usr_id
     *
     * @return bool
     */
    public function hasAccess(int $obj_ref_id, int $usr_id) : bool
    {
        $cache_key = $obj_ref_id . "_" . $usr_id;

        if ($this->has_access[$cache_key] === null) {
            $this->has_access[$cache_key] = (self::srSelfDeclaration()->objects()->supportsObjType($obj_ref_id) && self::srSelfDeclaration()->objects()->hasWriteAccess($obj_ref_id, $usr_id));
        }

        return $this->has_access[$cache_key];
    }


    /**
     * @internal
     */
    public function installTables()/* : void*/
    {
        ObjectConfig::updateDB();
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function isEnabled(int $obj_ref_id) : bool
    {
        if ($this->is_enabled[$obj_ref_id] === null) {
            $this->is_enabled[$obj_ref_id] = $this->getObjectConfig(self::dic()->objDataCache()->lookupObjId($obj_ref_id))->isEnabled();
        }

        return $this->is_enabled[$obj_ref_id];
    }


    /**
     * @param ObjectConfig $object_config
     */
    public function storeObjectConfig(ObjectConfig $object_config)/* : void*/
    {
        $object_config->store();

        $this->object_configs[$object_config->getObjId()] = $object_config;
        $this->is_enabled = [];
    }
}
