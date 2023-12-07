<?php

namespace PrionDevelopment\Helper;

/**
 * This file is part of Prion Development's Helper Package,
 * a package filled with traits and model extensions to reduce replicate code.
 *
 * @license MIT
 * @company Prion Development
 * @package Helper
 */

class Helper
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new instance.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }
}
