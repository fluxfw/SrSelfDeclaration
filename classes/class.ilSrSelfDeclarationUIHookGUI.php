<?php

use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class ilSrSelfDeclarationUIHookGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrSelfDeclarationUIHookGUI extends ilUIHookPluginGUI
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;


    /**
     * ilSrSelfDeclarationUIHookGUI constructor
     */
    public function __construct()
    {

    }
}
