<?php

namespace srag\Plugins\SrSelfDeclaration\ObjectConfig;

use ilSrSelfDeclarationPlugin;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\ObjectConfig\Form\FormBuilder;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrSelfDeclaration\ObjectConfig
 */
final class Factory
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
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
     * @param ObjectConfigCtrl $parent
     * @param ObjectConfig     $object_config
     *
     * @return FormBuilder
     */
    public function newFormBuilderInstance(ObjectConfigCtrl $parent, ObjectConfig $object_config) : FormBuilder
    {
        $form = new FormBuilder($parent, $object_config);

        return $form;
    }


    /**
     * @return ObjectConfig
     */
    public function newInstance() : ObjectConfig
    {
        $object_config = new ObjectConfig();

        return $object_config;
    }
}
