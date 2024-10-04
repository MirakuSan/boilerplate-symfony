<?php

declare(strict_types=1);

/*
 * This file is part of the Boilerplate Symfony project.
 *
 * Copyright (c) 2024-present MirakuSan
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routingConfigurator): void {
    if ('dev' === $routingConfigurator->env()) {
        $routingConfigurator->import('@WebProfilerBundle/Resources/config/routing/wdt.xml')
            ->prefix('/_wdt')
        ;
        $routingConfigurator->import('@WebProfilerBundle/Resources/config/routing/profiler.xml')
            ->prefix('/_profiler')
        ;
    }
};
