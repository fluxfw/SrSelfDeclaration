<?php

namespace srag\Plugins\SrSelfDeclaration\GlobalConfig;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilSrSelfDeclarationPlugin;
use ilUtil;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class GlobalConfigCtrl
 *
 * @package           srag\Plugins\SrSelfDeclaration\GlobalConfig
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrSelfDeclaration\GlobalConfig\GlobalConfigCtrl: ilSrSelfDeclarationConfigGUI
 */
class GlobalConfigCtrl
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const CMD_EDIT_GLOBAL_CONFIG = "editGlobalConfig";
    const CMD_UPDATE_GLOBAL_CONFIG = "updateGlobalConfig";
    const LANG_MODULE = "global_config";
    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    const TAB_EDIT_GLOBAL_CONFIG = "edit_global_config";
    /**
     * @var GlobalConfig
     */
    protected $global_config;


    /**
     * GlobalConfigCtrl constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public static function addTabs() : void
    {
        if (self::srSelfDeclaration()->globalConfig()->hasAccess(self::dic()->user()->getId())) {
            self::dic()
                ->tabs()
                ->addTab(self::TAB_EDIT_GLOBAL_CONFIG, self::plugin()->translate("config", self::LANG_MODULE),
                    self::dic()->ctrl()->getLinkTargetByClass(self::class, self::CMD_EDIT_GLOBAL_CONFIG));
        }
    }


    /**
     *
     */
    public function executeCommand() : void
    {
        if (!self::srSelfDeclaration()->globalConfig()->hasAccess(self::dic()->user()->getId())) {
            die();
        }

        $this->global_config = self::srSelfDeclaration()->globalConfig()->getGlobalConfig();

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_EDIT_GLOBAL_CONFIG:
                    case self::CMD_UPDATE_GLOBAL_CONFIG:
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
    protected function editGlobalConfig() : void
    {
        self::dic()->tabs()->activateTab(self::TAB_EDIT_GLOBAL_CONFIG);

        $form = self::srSelfDeclaration()->globalConfig()->factory()->newFormBuilderInstance($this, $this->global_config);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function setTabs() : void
    {

    }


    /**
     *
     */
    protected function updateGlobalConfig() : void
    {
        self::dic()->tabs()->activateTab(self::TAB_EDIT_GLOBAL_CONFIG);

        $form = self::srSelfDeclaration()->globalConfig()->factory()->newFormBuilderInstance($this, $this->global_config);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_GLOBAL_CONFIG);
    }
}
