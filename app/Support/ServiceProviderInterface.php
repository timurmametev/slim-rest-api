<?php

declare(strict_types=1);

namespace App\Support;

use UltraLite\Container\Container;

interface ServiceProviderInterface
{
    public function register(Container $container);
}