<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration;

use ilObject;
use ilSrSelfDeclarationPlugin;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Config\Config;
use srag\Plugins\SrSelfDeclaration\Declaration\Block\Block;
use srag\Plugins\SrSelfDeclaration\Declaration\Form\FormBuilder;
use srag\Plugins\SrSelfDeclaration\Declaration\Table\TableBuilder;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrSelfDeclaration\Declaration
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
     * @param int $obj_ref_id
     *
     * @return Block
     */
    public function newBlockInstance(int $obj_ref_id) : Block
    {
        $block = new Block($obj_ref_id);

        return $block;
    }


    /**
     * @param DeclarationCtrl $parent
     * @param Declaration     $declaration
     *
     * @return FormBuilder
     */
    public function newFormBuilderInstance(DeclarationCtrl $parent, Declaration $declaration) : FormBuilder
    {
        $form = new FormBuilder($parent, $declaration);

        return $form;
    }


    /**
     * @return Declaration
     */
    public function newInstance() : Declaration
    {
        $declaration = new Declaration();

        return $declaration;
    }


    /**
     * @param DeclarationsCtrl $parent
     * @param ilObject         $obj
     * @param Config           $config
     *
     * @return TableBuilder
     */
    public function newTableBuilderInstance(DeclarationsCtrl $parent, ilObject $obj, Config $config) : TableBuilder
    {
        $table = new TableBuilder($parent, $obj, $config);

        return $table;
    }
}
