<?php

declare(strict_types=1);

namespace Arlen\Omnicore\Traits;

use Arlen\Omnicore\ClientFactory;

trait Omnicored
{
    /**
     * Get omnicored client factory instance.
     *
     * @return \Arlen\Omnicore\ClientFactory
     */
    public function omnicored() : ClientFactory
    {
        return app('omnicored');
    }
}
