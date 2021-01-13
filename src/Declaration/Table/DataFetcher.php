<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration\Table;

use ilObject;
use ilSrSelfDeclarationPlugin;
use srag\DataTableUI\SrSelfDeclaration\Component\Data\Data;
use srag\DataTableUI\SrSelfDeclaration\Component\Data\Row\RowData;
use srag\DataTableUI\SrSelfDeclaration\Component\Settings\Settings;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Data\Fetcher\AbstractDataFetcher;
use srag\Plugins\SrSelfDeclaration\Declaration\Declaration;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class DataFetcher
 *
 * @package srag\Plugins\SrSelfDeclaration\Declaration\Table
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DataFetcher extends AbstractDataFetcher
{

    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var ilObject
     */
    protected $obj;


    /**
     * @inheritDoc
     *
     * @param ilObject $obj
     */
    public function __construct(ilObject $obj)
    {
        parent::__construct();

        $this->obj = $obj;
    }


    /**
     * @inheritDoc
     */
    public function fetchData(Settings $settings) : Data
    {
        $data = self::srSelfDeclaration()->declarations()->getDeclarationsOfObject($this->obj->getId());

        $max_count = count($data);

        $data = array_slice($data, $settings->getOffset(), $settings->getRowsCount());

        return self::dataTableUI()->data()->data(array_map(function (Declaration $declaration) : RowData {
            return self::dataTableUI()->data()->row()->getter($declaration->getDeclarationId(), $declaration);
        }, $data), $max_count);
    }
}
