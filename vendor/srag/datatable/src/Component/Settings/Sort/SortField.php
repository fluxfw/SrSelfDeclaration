<?php

namespace srag\DataTableUI\SrSelfDeclaration\Component\Settings\Sort;

use JsonSerializable;
use stdClass;

/**
 * Interface SortField
 *
 * @package srag\DataTableUI\SrSelfDeclaration\Component\Settings\Sort
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface SortField extends JsonSerializable
{

    /**
     * @var int
     */
    const SORT_DIRECTION_DOWN = 2;
    /**
     * @var int
     */
    const SORT_DIRECTION_UP = 1;


    /**
     * @return string
     */
    public function getSortField() : string;


    /**
     * @return int
     */
    public function getSortFieldDirection() : int;


    /**
     * @inheritDoc
     *
     * @return stdClass
     */
    public function jsonSerialize() : stdClass;


    /**
     * @param string $sort_field
     *
     * @return self
     */
    public function withSortField(string $sort_field) : self;


    /**
     * @param int $sort_field_direction
     *
     * @return self
     */
    public function withSortFieldDirection(int $sort_field_direction) : self;
}