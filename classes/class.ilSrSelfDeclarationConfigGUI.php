<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DevTools\SrSelfDeclaration\DevToolsCtrl;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\GlobalConfig\GlobalConfigCtrl;

/**
 * Class ilSrSelfDeclarationConfigGUI
 *
 * @ilCtrl_isCalledBy srag\DevTools\SrSelfDeclaration\DevToolsCtrl: ilSrSelfDeclarationConfigGUI
 */
class ilSrSelfDeclarationConfigGUI extends ilPluginConfigGUI
{

    use DICTrait;

    const CMD_CONFIGURE = "configure";
    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;


    /**
     * ilSrSelfDeclarationConfigGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function performCommand(/*string*/ $cmd) : void
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(DevToolsCtrl::class):
                self::dic()->ctrl()->forwardCommand(new DevToolsCtrl($this, self::plugin()));
                break;

            case strtolower(GlobalConfigCtrl::class):
                self::dic()->ctrl()->forwardCommand(new GlobalConfigCtrl());
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_CONFIGURE:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function configure() : void
    {
        self::dic()->ctrl()->redirectByClass(GlobalConfigCtrl::class, GlobalConfigCtrl::CMD_EDIT_GLOBAL_CONFIG);
    }


    /**
     *
     */
    protected function setTabs() : void
    {
        GlobalConfigCtrl::addTabs();

        DevToolsCtrl::addTabs(self::plugin());

        self::dic()->locator()->addItem(ilSrSelfDeclarationPlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTarget($this, self::CMD_CONFIGURE));
    }
}
