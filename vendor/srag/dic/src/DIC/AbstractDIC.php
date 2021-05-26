<?php

namespace srag\DIC\SrSelfDeclaration\DIC;

use ILIAS\DI\Container;
use srag\DIC\SrSelfDeclaration\Database\DatabaseDetector;
use srag\DIC\SrSelfDeclaration\Database\DatabaseInterface;

/**
 * Class AbstractDIC
 *
 * @package srag\DIC\SrSelfDeclaration\DIC
 */
abstract class AbstractDIC implements DICInterface
{

    /**
     * @var Container
     */
    protected $dic;


    /**
     * @inheritDoc
     */
    public function __construct(Container &$dic)
    {
        $this->dic = &$dic;
    }


    /**
     * @inheritDoc
     */
    public function database() : DatabaseInterface
    {
        return DatabaseDetector::getInstance($this->databaseCore());
    }
}
