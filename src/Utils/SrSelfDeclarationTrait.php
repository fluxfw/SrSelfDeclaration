<?php

namespace srag\Plugins\SrSelfDeclaration\Utils;

use srag\Plugins\SrSelfDeclaration\Repository;

/**
 * Trait SrSelfDeclarationTrait
 *
 * @package srag\Plugins\SrSelfDeclaration\Utils
 */
trait SrSelfDeclarationTrait
{

    /**
     * @return Repository
     */
    protected static function srSelfDeclaration() : Repository
    {
        return Repository::getInstance();
    }
}
