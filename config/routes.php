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
    $routingConfigurator->import(
        [
            'path' => '../src/Controller/',
            'namespace' => 'App\Controller',
        ],
        'attribute',
    );
};
