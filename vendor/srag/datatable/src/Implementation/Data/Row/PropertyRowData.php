<?php

namespace srag\DataTableUI\SrSelfDeclaration\Implementation\Data\Row;

/**
 * Class PropertyRowData
 *
 * @package srag\DataTableUI\SrSelfDeclaration\Implementation\Data\Row
 */
class PropertyRowData extends AbstractRowData
{

    /**
     * @inheritDoc
     */
    public function __invoke(string $key)
    {
        return $this->getOriginalData()->{$key};
    }
}
