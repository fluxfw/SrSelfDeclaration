<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilLink;
use ilObject;
use ilSrSelfDeclarationPlugin;
use ilUIPluginRouterGUI;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\ObjectConfig\ObjectConfig;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class DeclarationsCtrl
 *
 * @package           srag\Plugins\SrSelfDeclaration\Declaration
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrSelfDeclaration\Declaration\DeclarationsCtrl: ilUIPluginRouterGUI
 */
class DeclarationsCtrl
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const CMD_BACK = "back";
    const CMD_LIST_DECLARATIONS = "listDeclarations";
    const GET_PARAM_REF_ID = "ref_id";
    const LANG_MODULE = "declarations";
    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    const TAB_LIST_DECLARATIONS = "list_declarations";
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
     * DeclarationsCtrl constructor
     */
    public function __construct()
    {

    }


    /**
     * @param int $obj_ref_id
     */
    public static function addTabs(int $obj_ref_id)/* : void*/
    {
        if (self::srSelfDeclaration()->declarations()->hasAccessToDeclarationsOfObject($obj_ref_id, self::dic()->user()->getId())) {
            self::dic()->ctrl()->setParameterByClass(self::class, self::GET_PARAM_REF_ID, $obj_ref_id);

            self::dic()
                ->tabs()
                ->addSubTab(self::TAB_LIST_DECLARATIONS, self::plugin()->translate("declarations", self::LANG_MODULE),
                    self::dic()->ctrl()->getLinkTargetByClass([ilUIPluginRouterGUI::class, self::class], self::CMD_LIST_DECLARATIONS));
        }
    }


    /**
     *
     */
    public function executeCommand()/* : void*/
    {
        $this->obj_ref_id = intval(filter_input(INPUT_GET, self::GET_PARAM_REF_ID));

        if (!self::srSelfDeclaration()->declarations()->hasAccessToDeclarationsOfObject($this->obj_ref_id, self::dic()->user()->getId())) {
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
                    case self::CMD_LIST_DECLARATIONS:
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
    protected function listDeclarations()/* : void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_LIST_DECLARATIONS);

        $table = self::srSelfDeclaration()->declarations()->factory()->newTableBuilderInstance($this, $this->obj, $this->object_config);

        self::output()->output($table, true);
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
            ->addTab(self::TAB_LIST_DECLARATIONS, self::plugin()->translate("declarations", self::LANG_MODULE), self::dic()->ctrl()->getLinkTargetByClass(self::class, self::CMD_LIST_DECLARATIONS));
    }
}
