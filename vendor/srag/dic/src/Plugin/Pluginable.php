<?php

namespace srag\DIC\SrSelfDeclaration\Plugin;

/**
 * Interface Pluginable
 *
 * @package srag\DIC\SrSelfDeclaration\Plugin
 */
interface Pluginable
{

    /**
     * @return PluginInterface
     */
    public function getPlugin() : PluginInterface;


    /**
     * @param PluginInterface $plugin
     *
     * @return static
     */
    public function withPlugin(PluginInterface $plugin)/*: static*/ ;
}
