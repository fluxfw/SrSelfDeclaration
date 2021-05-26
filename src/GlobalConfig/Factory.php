<?php

namespace srag\Plugins\SrSelfDeclaration\GlobalConfig;

use ilSrSelfDeclarationPlugin;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\GlobalConfig\Form\FormBuilder;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrSelfDeclaration\GlobalConfig
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
     * @param GlobalConfigCtrl $parent
     * @param GlobalConfig     $global_config
     *
     * @return FormBuilder
     */
    public function newFormBuilderInstance(GlobalConfigCtrl $parent, GlobalConfig $global_config) : FormBuilder
    {
        $form = new FormBuilder($parent, $global_config);

        return $form;
    }


    /**
     * @return GlobalConfig
     */
    public function newInstance() : GlobalConfig
    {
        $global_config = new GlobalConfig();

        return $global_config;
    }
}
