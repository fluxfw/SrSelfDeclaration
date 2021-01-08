<?php

namespace srag\DataTableUI\SrSelfDeclaration\Implementation\Data;

use srag\DataTableUI\SrSelfDeclaration\Component\Data\Data as DataInterface;
use srag\DataTableUI\SrSelfDeclaration\Component\Data\Factory as FactoryInterface;
use srag\DataTableUI\SrSelfDeclaration\Component\Data\Fetcher\Factory as FetcherFactoryInterface;
use srag\DataTableUI\SrSelfDeclaration\Component\Data\Row\Factory as RowFactoryInterface;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Data\Fetcher\Factory as FetcherFactory;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Data\Row\Factory as RowFactory;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrSelfDeclaration\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\SrSelfDeclaration\Implementation\Data
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Factory implements FactoryInterface
{

    use DICTrait;
    use DataTableUITrait;

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
     * @inheritDoc
     */
    public function data(array $data, int $max_count) : DataInterface
    {
        return new Data($data, $max_count);
    }


    /**
     * @inheritDoc
     */
    public function fetcher() : FetcherFactoryInterface
    {
        return FetcherFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function row() : RowFactoryInterface
    {
        return RowFactory::getInstance();
    }
}
