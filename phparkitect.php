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

use Arkitect\ClassSet;
use Arkitect\CLI\Config;

return static function (Config $config): void {
    $classSet = ClassSet::fromDir(__DIR__.'/src');

    $config
        ->add($classSet)
    ;
};
