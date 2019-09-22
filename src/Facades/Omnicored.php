<?php

declare(strict_types=1);

namespace Arlen\Omnicore\Facades;

use Illuminate\Support\Facades\Facade;

class Omnicored extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'omnicored';
    }
}
