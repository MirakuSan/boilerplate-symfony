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

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    if ('dev' === $containerConfigurator->env()) {
        $containerConfigurator->extension('web_profiler', [
            'toolbar' => true,
            'intercept_redirects' => false,
        ]);
        $containerConfigurator->extension('framework', [
            'profiler' => [
                'only_exceptions' => false,
                'collect_serializer_data' => true,
            ],
        ]);
    }
    if ('test' === $containerConfigurator->env()) {
        $containerConfigurator->extension('web_profiler', [
            'toolbar' => false,
            'intercept_redirects' => false,
        ]);
        $containerConfigurator->extension('framework', [
            'profiler' => [
                'collect' => false,
            ],
        ]);
    }
};
