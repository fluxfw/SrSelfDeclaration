<?php

namespace srag\Plugins\SrSelfDeclaration\ObjectConfig;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilLink;
use ilObject;
use ilSrSelfDeclarationPlugin;
use ilUIPluginRouterGUI;
use ilUtil;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class ObjectConfigCtrl
 *
 * @package           srag\Plugins\SrSelfDeclaration\ObjectConfig
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrSelfDeclaration\ObjectConfig\ObjectConfigCtrl: ilUIPluginRouterGUI
 */
class ObjectConfigCtrl
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const CMD_BACK = "back";
    const CMD_EDIT_OBJECT_CONFIG = "editObjectConfig";
    const CMD_UPDATE_OBJECT_CONFIG = "updateObjectConfig";
    const GET_PARAM_REF_ID = "ref_id";
    const LANG_MODULE = "object_config";
    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    const TAB_EDIT_OBJECT_CONFIG = "edit_object_config";
    /**
     * @var ilObject
     */
    protected $obj;
    /**
     * @var int
     */
    protected $obj_ref_id;
    /**
     * @var ObjectConfig
     */
    protected $object_config;


    /**
     * ObjectConfigCtrl constructor
     */
    public function __construct()
    {

    }


    /**
     * @param int $obj_ref_id
     */
    public static function addTabs(int $obj_ref_id)/* : void*/
    {
        if (self::srSelfDeclaration()->objectConfigs()->hasAccess($obj_ref_id, self::dic()->user()->getId())) {
            self::dic()->ctrl()->setParameterByClass(self::class, self::GET_PARAM_REF_ID, $obj_ref_id);

            self::dic()
                ->tabs()
                ->addSubTab(self::TAB_EDIT_OBJECT_CONFIG, self::plugin()->translate("config", self::LANG_MODULE),
                    self::dic()->ctrl()->getLinkTargetByClass([ilUIPluginRouterGUI::class, self::class], self::CMD_EDIT_OBJECT_CONFIG));
        }
    }


    /**
     *
     */
    public function executeCommand()/* : void*/
    {
        $this->obj_ref_id = intval(filter_input(INPUT_GET, self::GET_PARAM_REF_ID));

        if (!self::srSelfDeclaration()->objectConfigs()->hasAccess($this->obj_ref_id, self::dic()->user()->getId())) {
            die();
        }

        $this->obj = self::srSelfDeclaration()->objects()->getObjByRefId($this->obj_ref_id);

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_REF_ID);

        $this->object_config = self::srSelfDeclaration()->objectConfigs()->getObjectConfig($this->obj->getId());

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_BACK:
                    case self::CMD_EDIT_OBJECT_CONFIG:
                    case self::CMD_UPDATE_OBJECT_CONFIG:
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
    protected function back()/* : void*/
    {
        self::dic()->ctrl()->redirectToURL(ilLink::_getLink($this->obj->getRefId()));
    }


    /**
     *
     */
    protected function editObjectConfig()/* : void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_EDIT_OBJECT_CONFIG);

        $form = self::srSelfDeclaration()->objectConfigs()->factory()->newFormBuilderInstance($this, $this->object_config);

        self::output()->output($form, true);
    }


    /**
     *
     */
    protected function setTabs()/* : void*/
    {
        self::dic()->tabs()->clearTargets();

        self::dic()->tabs()->setBackTarget($this->obj->getTitle(), self::dic()->ctrl()->getLinkTarget($this, self::CMD_BACK));

        self::dic()
            ->tabs()
            ->addTab(self::TAB_EDIT_OBJECT_CONFIG, self::plugin()->translate("config", self::LANG_MODULE), self::dic()->ctrl()->getLinkTargetByClass(self::class, self::CMD_EDIT_OBJECT_CONFIG));
    }


    /**
     *
     */
    protected function updateObjectConfig()/* : void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_EDIT_OBJECT_CONFIG);

        $form = self::srSelfDeclaration()->objectConfigs()->factory()->newFormBuilderInstance($this, $this->object_config);

        if (!$form->storeForm()) {
            self::output()->output($form, true);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_OBJECT_CONFIG);
    }
}
