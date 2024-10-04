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
    $containerConfigurator->extension('framework', [
        'router' => [
            'utf8' => true,
        ],
    ]);
    if ('prod' === $containerConfigurator->env()) {
        $containerConfigurator->extension('framework', [
            'router' => [
                'strict_requirements' => null,
            ],
        ]);
    }
};
