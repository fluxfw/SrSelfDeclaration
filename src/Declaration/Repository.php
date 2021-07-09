<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration;

use ilSrSelfDeclarationPlugin;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrSelfDeclaration\Declaration
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
     * @var Declaration[][]
     */
    protected $declarations_of_object = [];
    /**
     * @var Declaration[]
     */
    protected $declarations_of_object_for_user = [];
    /**
     * @var bool[]
     */
    protected $has_access_to_declaration_of_object_for_user = [];
    /**
     * @var bool[]
     */
    protected $has_access_to_declarations_of_object = [];


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
    public function dropTables() : void
    {
        self::dic()->database()->dropTable(Declaration::TABLE_NAME, false);
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
     * @param int $usr_id
     *
     * @return Declaration
     */
    public function getDeclarationOfObjectForUser(int $obj_id, int $usr_id) : Declaration
    {
        $cache_key = $obj_id . "_" . $usr_id;

        if ($this->declarations_of_object_for_user[$cache_key] === null) {
            $this->declarations_of_object_for_user[$cache_key] = Declaration::where(["obj_id" => $obj_id, "usr_id" => $usr_id])->first();

            if ($this->declarations_of_object_for_user[$cache_key] === null) {
                $this->declarations_of_object_for_user[$cache_key] = $this->factory()->newInstance();

                $this->declarations_of_object_for_user[$cache_key]->setObjId($obj_id);

                $this->declarations_of_object_for_user[$cache_key]->setUsrId($usr_id);

                $object_config = self::srSelfDeclaration()->objectConfigs()->getObjectConfig($obj_id);

                $this->declarations_of_object_for_user[$cache_key]->setText($object_config->getDefaultText());

                $this->declarations_of_object_for_user[$cache_key]->setEffort($object_config->getMaxEffort());
            }
        }

        return $this->declarations_of_object_for_user[$cache_key];
    }


    /**
     * @param int $obj_id
     *
     * @return Declaration[]
     */
    public function getDeclarationsOfObject(int $obj_id) : array
    {
        if ($this->declarations_of_object[$obj_id] === null) {
            $this->declarations_of_object[$obj_id] = array_values(Declaration::where(["obj_id" => $obj_id])->get());

            foreach ($this->declarations_of_object[$obj_id] as $declaration) {
                $this->declarations_of_object_for_user[$declaration->getObjId() . "_" . $declaration->getUsrId()] = $declaration;
            }
        }

        return $this->declarations_of_object[$obj_id];
    }


    /**
     * @param int $obj_ref_id
     * @param int $usr_id
     *
     * @return bool
     */
    public function hasAccessToDeclarationOfObjectForUser(int $obj_ref_id, int $usr_id) : bool
    {
        $a = self::srSelfDeclaration()->objects()->supportsObjType($obj_ref_id);
        $b = self::srSelfDeclaration()
            ->objectConfigs()
            ->isEnabled($obj_ref_id);
        $c = self::srSelfDeclaration()->objects()->hasReadAccess($obj_ref_id, $usr_id);
        $d = !self::srSelfDeclaration()->objects()->hasWriteAccess($obj_ref_id, $usr_id);

        $cache_key = $obj_ref_id . "_" . $usr_id;

        if ($this->has_access_to_declaration_of_object_for_user[$cache_key] === null) {
            $this->has_access_to_declaration_of_object_for_user[$cache_key] = (self::srSelfDeclaration()->objects()->supportsObjType($obj_ref_id)
                && self::srSelfDeclaration()
                    ->objectConfigs()
                    ->isEnabled($obj_ref_id)
                && self::srSelfDeclaration()->objects()->hasReadAccess($obj_ref_id, $usr_id)
                && !self::srSelfDeclaration()->objects()->hasWriteAccess($obj_ref_id, $usr_id));
        }

        return $this->has_access_to_declaration_of_object_for_user[$cache_key];
    }


    /**
     * @param int $obj_ref_id
     * @param int $usr_id
     *
     * @return bool
     */
    public function hasAccessToDeclarationsOfObject(int $obj_ref_id, int $usr_id) : bool
    {
        $cache_key = $obj_ref_id . "_" . $usr_id;

        if ($this->has_access_to_declarations_of_object[$cache_key] === null) {
            $this->has_access_to_declarations_of_object[$cache_key] = (self::srSelfDeclaration()->objects()->supportsObjType($obj_ref_id)
                && self::srSelfDeclaration()
                    ->objectConfigs()
                    ->isEnabled($obj_ref_id)
                && self::srSelfDeclaration()->objects()->hasWriteAccess($obj_ref_id, $usr_id));
        }

        return $this->has_access_to_declarations_of_object[$cache_key];
    }


    /**
     * @param Declaration $declaration
     *
     * @return bool
     */
    public function hasDeclaration(Declaration $declaration) : bool
    {
        return (!empty($declaration->getDeclarationId()) && !empty($declaration->getText()));
    }


    /**
     * @internal
     */
    public function installTables() : void
    {
        Declaration::updateDB();
    }


    /**
     * @param Declaration $declaration
     */
    public function storeDeclaration(Declaration $declaration) : void
    {
        $declaration->store();

        $this->declarations_of_object_for_user[$declaration->getObjId() . "_" . $declaration->getUsrId()] = $declaration;
        $this->declarations_of_object = [];
    }
}
