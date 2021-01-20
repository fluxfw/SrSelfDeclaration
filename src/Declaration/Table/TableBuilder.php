<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration\Table;

use ilObject;
use ilSrSelfDeclarationPlugin;
use ilUserDefinedFields;
use ilUserProfile;
use srag\DataTableUI\SrSelfDeclaration\Component\Table;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Utils\AbstractTableBuilder;
use srag\Plugins\SrSelfDeclaration\Declaration\DeclarationsCtrl;
use srag\Plugins\SrSelfDeclaration\ObjectConfig\ObjectConfig;
use srag\Plugins\SrSelfDeclaration\ObjectConfig\ObjectConfigCtrl;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class TableBuilder
 *
 * @package srag\Plugins\SrSelfDeclaration\Declaration\Table
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TableBuilder extends AbstractTableBuilder
{

    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var ilObject
     */
    protected $obj;
    /**
     * @var ObjectConfig
     */
    protected $object_config;


    /**
     * @inheritDoc
     *
     * @param DeclarationsCtrl $parent
     * @param ilObject         $obj
     * @param ObjectConfig     $object_config
     */
    public function __construct(DeclarationsCtrl $parent, ilObject $obj, ObjectConfig $object_config)
    {
        parent::__construct($parent);

        $this->obj = $obj;
        $this->object_config = $object_config;
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        self::dic()->ui()->mainTemplate()->setRightContent(self::output()->getHTML(self::dic()->ui()->factory()->listing()->descriptive(array_filter(array_map(function ($value) : string {
            return nl2br(implode("\n", array_map("htmlspecialchars", explode("\n", strval($value)))), false);
        }, [
            self::plugin()->translate("default_text", ObjectConfigCtrl::LANG_MODULE) => $this->object_config->getDefaultText(),
            self::plugin()->translate("max_effort", ObjectConfigCtrl::LANG_MODULE)   => $this->object_config->getMaxEffort()
        ])))));

        return parent::render();
    }


    /**
     * @inheritDoc
     */
    protected function buildTable() : Table
    {
        $columns = [];

        foreach ((new ilUserProfile())->getStandardFields() as $key => $field) {
            if ($field["required_fix_value"] || self::dic()->settings()->get("usr_settings_" . ($this->obj->getType() === "grp" ? "group" : "course") . "_export_" . $key)) {
                $columns[] = self::dataTableUI()
                    ->column()
                    ->column($key, self::dic()->language()->txt($key))
                    ->withSortable(false)
                    ->withSelectable(!$field["required_fix_value"])
                    ->withFormatter(self::dataTableUI()->column()->formatter()->chainGetter(["usr", $field["method"]]));
            }
        }

        foreach (ilUserDefinedFields::_getInstance()->{"get" . ($this->obj->getType() === "grp" ? "Group" : "Course") . "ExportableFields"}() as $field) {
            $columns[] = self::dataTableUI()->column()->column($field["field_id"], $field["field_name"])->withSortable(false)->withFormatter(self::dataTableUI()
                ->column()
                ->formatter()
                ->chainGetter(["usr", "userDefinedData", "f_" . $field["field_id"]]));
        }

        $columns[] = self::dataTableUI()->column()->column("text",
            self::plugin()->translate("text", DeclarationsCtrl::LANG_MODULE))->withSortable(false)->withSelectable(false)->withFormatter(self::dataTableUI()->column()->formatter()->multiline());

        $columns[] = self::dataTableUI()->column()->column("effort",
            self::plugin()->translate("effort", DeclarationsCtrl::LANG_MODULE))->withSortable(false)->withSelectable(false);

        $columns[] = self::dataTableUI()->column()->column("effort_reason",
            self::plugin()->translate("effort_reason", DeclarationsCtrl::LANG_MODULE))->withSortable(false)->withSelectable(false)->withFormatter(self::dataTableUI()
            ->column()
            ->formatter()
            ->multiline());

        $table = self::dataTableUI()->table(ilSrSelfDeclarationPlugin::PLUGIN_ID . "_declarations",
            self::dic()->ctrl()->getLinkTarget($this->parent, DeclarationsCtrl::CMD_LIST_DECLARATIONS, "", false, false),
            self::plugin()->translate("declarations", DeclarationsCtrl::LANG_MODULE), $columns, new DataFetcher($this->obj))->withPlugin(self::plugin());

        return $table;
    }
}
