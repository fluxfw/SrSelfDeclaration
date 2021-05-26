<?php

namespace srag\DataTableUI\SrSelfDeclaration\Implementation\Column;

use srag\DataTableUI\SrSelfDeclaration\Component\Column\Column as ColumnInterface;
use srag\DataTableUI\SrSelfDeclaration\Component\Column\Factory as FactoryInterface;
use srag\DataTableUI\SrSelfDeclaration\Component\Column\Formatter\Factory as FormatterFactoryInterface;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Column\Formatter\Factory as FormatterFactory;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrSelfDeclaration\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\SrSelfDeclaration\Implementation\Column
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
    public function column(string $key, string $title) : ColumnInterface
    {
        return new Column($key, $title);
    }


    /**
     * @inheritDoc
     */
    public function formatter() : FormatterFactoryInterface
    {
        return FormatterFactory::getInstance();
    }
}
