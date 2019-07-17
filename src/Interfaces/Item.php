<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

namespace CodersStudio\Cart\Interfaces;

/**
 * Interface Item
 * @package CodersStudio\Cart\Interfaces
 */
interface Item
{
    /**
     * Return product name
     * @return string
     */
    public function getName():string;

    /**
     * Return product price
     * @return float
     */
    public function getPrice():float;

    /**
     * Return additional parameters
     * For example ['img_url' => "/path/to/img"]
     * @return array
     */
    public function getParams():array;
}

