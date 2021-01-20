<?php

namespace srag\Plugins\SrSelfDeclaration\GlobalConfig;

use ilSrSelfDeclarationPlugin;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrSelfDeclaration\GlobalConfig
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
     * @var GlobalConfig|null
     */
    protected $global_config = null;
    /**
     * @var bool[]
     */
    protected $has_access = [];


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
        self::dic()->database()->dropTable(GlobalConfig::TABLE_NAME, false);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @return GlobalConfig
     */
    public function getGlobalConfig() : GlobalConfig
    {
        if ($this->global_config === null) {
            $this->global_config = GlobalConfig::first();

            if ($this->global_config === null) {
                $this->global_config = $this->factory()->newInstance();
            }
        }

        return $this->global_config;
    }


    /**
     * @param int $usr_id
     *
     * @return bool
     */
    public function hasAccess(int $usr_id) : bool
    {
        if ($this->has_access[$usr_id] === null) {
            $this->has_access[$usr_id] = self::srSelfDeclaration()->objects()->hasWriteAccess(31, $usr_id);
        }

        return $this->has_access[$usr_id];
    }


    /**
     * @internal
     */
    public function installTables()/* : void*/
    {
        GlobalConfig::updateDB();
    }


    /**
     * @param GlobalConfig $global_config
     */
    public function storeGlobalConfig(GlobalConfig $global_config)/* : void*/
    {
        $global_config->store();

        $this->global_config = $global_config;
    }
}
