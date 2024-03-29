<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\DI\Container;
use srag\CustomInputGUIs\SrSelfDeclaration\Loader\CustomInputGUIsLoaderDetector;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Utils\DataTableUITrait;
use srag\DevTools\SrSelfDeclaration\DevToolsCtrl;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;
use srag\RemovePluginDataConfirm\SrSelfDeclaration\PluginUninstallTrait;

/**
 * Class ilSrSelfDeclarationPlugin
 */
class ilSrSelfDeclarationPlugin extends ilUserInterfaceHookPlugin
{

    use PluginUninstallTrait;
    use SrSelfDeclarationTrait;
    use DataTableUITrait;

    const PLUGIN_CLASS_NAME = self::class;
    const PLUGIN_ID = "srselfdeclr";
    const PLUGIN_NAME = "SrSelfDeclaration";
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * ilSrSelfDeclarationPlugin constructor
     */
    public function __construct()
    {
        parent::__construct();
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
     * @inheritDoc
     */
    public function exchangeUIRendererAfterInitialization(Container $dic) : Closure
    {
        return CustomInputGUIsLoaderDetector::exchangeUIRendererAfterInitialization();
    }


    /**
     * @inheritDoc
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @inheritDoc
     */
    public function updateLanguages(/*?array*/ $a_lang_keys = null) : void
    {
        parent::updateLanguages($a_lang_keys);

        $this->installRemovePluginDataConfirmLanguages();

        self::dataTableUI()->installLanguages(self::plugin());

        DevToolsCtrl::installLanguages(self::plugin());
    }


    /**
     * @inheritDoc
     */
    protected function deleteData() : void
    {
        self::srSelfDeclaration()->dropTables();
    }


    /**
     * @inheritDoc
     */
    protected function shouldUseOneUpdateStepOnly() : bool
    {
        return true;
    }
}
