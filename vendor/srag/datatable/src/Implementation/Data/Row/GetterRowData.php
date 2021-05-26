<?php

namespace srag\DataTableUI\SrSelfDeclaration\Implementation\Data\Row;

use srag\CustomInputGUIs\SrSelfDeclaration\PropertyFormGUI\Items\Items;

/**
 * Class GetterRowData
 *
 * @package srag\DataTableUI\SrSelfDeclaration\Implementation\Data\Row
 */
class GetterRowData extends AbstractRowData
{

    /**
     * @inheritDoc
     */
    public function __invoke(string $key)
    {
        return Items::getter($this->getOriginalData(), $key);
    }
}
