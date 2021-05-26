<?php

namespace srag\DataTableUI\SrSelfDeclaration\Implementation\Column\Formatter;

use srag\DataTableUI\SrSelfDeclaration\Component\Column\Formatter\Formatter;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrSelfDeclaration\DICTrait;

/**
 * Class AbstractFormatter
 *
 * @package srag\DataTableUI\SrSelfDeclaration\Implementation\Column\Formatter
 */
abstract class AbstractFormatter implements Formatter
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * AbstractFormatter constructor
     */
    public function __construct()
    {

    }
}
