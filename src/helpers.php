<?php

declare(strict_types=1);

use Arlen\Omnicore\ClientFactory;

if (! function_exists('omnicored')) {
    /**
     * Get omnicored client instance by name.
     *
     * @return \Arlen\Omnicore\ClientFactory
     */
    function omnicored() : ClientFactory
    {
        return app('omnicored');
    }
}
