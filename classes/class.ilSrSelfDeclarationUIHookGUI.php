<?php

use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Config\ConfigCtrl;
use srag\Plugins\SrSelfDeclaration\Declaration\DeclarationsCtrl;
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

    const COMPONENT_CONTAINER = "Services/Container";
    const GET_PARAM_REF_ID = "ref_id";
    const GET_PARAM_TARGET = "target";
    const PART_RIGHT_COLUMN = "right_column";
    const PAR_SUB_TABS = "sub_tabs";
    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;


    /**
     * ilSrSelfDeclarationUIHookGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function getHTML(/*string*/ $a_comp, /*string*/ $a_part, $a_par = []) : array
    {
        if ($a_comp === self::COMPONENT_CONTAINER && $a_part === self::PART_RIGHT_COLUMN) {
            return [
                "mode" => self::PREPEND,
                "html" => self::output()->getHTML(self::srSelfDeclaration()->declarations()->factory()->newBlockInstance($this->getRefId()))
            ];
        }

        return parent::getHTML($a_comp, $a_part, $a_par);
    }


    /**
     * @inheritDoc
     */
    public function modifyGUI(/*string*/ $a_comp, /*string*/ $a_part, /*array*/ $a_par = [])/*: void*/
    {
        if ($a_part === self::PAR_SUB_TABS) {
            if ((in_array(self::dic()->ctrl()->getCmdClass(), array_map("strtolower", [ilObjCourseGUI::class])) && self::dic()->ctrl()->getCmd() === "edit")
            ) {
                ConfigCtrl::addTabs($this->getRefId());
            }

            if (self::dic()->ctrl()->getCmdClass() === strtolower(ilCourseMembershipGUI::class)
                || self::dic()->ctrl()->getCmdClass() === strtolower(ilCourseParticipantsGroupsGUI::class)
                || self::dic()->ctrl()->getCmdClass() === strtolower(ilUsersGalleryGUI::class)
                || self::dic()->ctrl()->getCmdClass() === strtolower(ilMemberExportGUI::class)
            ) {
                DeclarationsCtrl::addTabs($this->getRefId());
            }
        }
    }


    /**
     * @return int|null
     */
    protected function getRefId()/* : ?int*/
    {
        $obj_ref_id = filter_input(INPUT_GET, self::GET_PARAM_REF_ID);

        if ($obj_ref_id === null) {
            $param_target = filter_input(INPUT_GET, self::GET_PARAM_TARGET);

            $obj_ref_id = explode("_", $param_target)[1];
        }

        $obj_ref_id = intval($obj_ref_id);

        if ($obj_ref_id > 0) {
            return $obj_ref_id;
        } else {
            return null;
        }
    }
}
