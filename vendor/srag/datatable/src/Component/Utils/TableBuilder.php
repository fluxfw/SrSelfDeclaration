<?php

namespace srag\DataTableUI\SrSelfDeclaration\Component\Utils;

use srag\DataTableUI\SrSelfDeclaration\Component\Table;

/**
 * Interface TableBuilder
 *
 * @package srag\DataTableUI\SrSelfDeclaration\Component\Utils
 */
interface TableBuilder
{

    /**
     * @return Table
     */
    public function getTable() : Table;


    /**
     * @return string
     */
    public function render() : string;
}
