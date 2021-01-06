<?php

namespace srag\Plugins\SrSelfDeclaration\Utils;

use srag\Plugins\SrSelfDeclaration\Repository;

/**
 * Trait SrSelfDeclarationTrait
 *
 * @package srag\Plugins\SrSelfDeclaration\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
