<?php

namespace srag\Plugins\SrSelfDeclaration;

use ilSrSelfDeclarationPlugin;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Declaration\Repository as DeclarationsRepository;
use srag\Plugins\SrSelfDeclaration\Object\Repository as ObjectsRepository;
use srag\Plugins\SrSelfDeclaration\ObjectConfig\Repository as ObjectConfigsRepository;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrSelfDeclaration
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
     * @return DeclarationsRepository
     */
    public function declarations() : DeclarationsRepository
    {
        return DeclarationsRepository::getInstance();
    }


    /**
     *
     */
    public function dropTables()/*:void*/
    {
        $this->declarations()->dropTables();
        $this->objectConfigs()->dropTables();
        $this->objects()->dropTables();
    }


    /**
     *
     */
    public function installTables()/*:void*/
    {
        $this->declarations()->installTables();
        $this->objectConfigs()->installTables();
        $this->objects()->installTables();
    }


    /**
     * @return ObjectConfigsRepository
     */
    public function objectConfigs() : ObjectConfigsRepository
    {
        return ObjectConfigsRepository::getInstance();
    }


    /**
     * @return ObjectsRepository
     */
    public function objects() : ObjectsRepository
    {
        return ObjectsRepository::getInstance();
    }
}
