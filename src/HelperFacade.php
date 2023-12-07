<?php

namespace PrionDevelopment\Helper;

/**
 * This file is part of Prion Development's Helper Package,
 *  a package filled with traits and model extensions to reduce replicate code.
 *
 * @license MIT
 * @company Prion Development
 * @package Helper
 */

use Illuminate\Support\Facades\Facade;

class HelperFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'helper';
    }
}
