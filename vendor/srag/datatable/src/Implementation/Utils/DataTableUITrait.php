<?php

namespace srag\DataTableUI\SrSelfDeclaration\Implementation\Utils;

use srag\DataTableUI\SrSelfDeclaration\Component\Factory as FactoryInterface;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Factory;

/**
 * Trait DataTableUITrait
 *
 * @package srag\DataTableUI\SrSelfDeclaration\Implementation\Utils
 */
trait DataTableUITrait
{

    /**
     * @return FactoryInterface
     */
    protected static function dataTableUI() : FactoryInterface
    {
        return Factory::getInstance();
    }
}
