<?php

namespace srag\DataTableUI\SrSelfDeclaration\Component\Format\Browser\Filter;

use srag\CustomInputGUIs\SrSelfDeclaration\FormBuilder\FormBuilder;
use srag\DataTableUI\SrSelfDeclaration\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\SrSelfDeclaration\Component\Settings\Settings;
use srag\DataTableUI\SrSelfDeclaration\Component\Table;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrSelfDeclaration\Component\Format\Browser\Filter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param BrowserFormat $parent
     * @param Table         $component
     * @param Settings      $settings
     *
     * @return FormBuilder
     */
    public function formBuilder(BrowserFormat $parent, Table $component, Settings $settings) : FormBuilder;
}
