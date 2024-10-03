<?php
declare(strict_types=1);

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\IsReadonly;
use Arkitect\Expression\ForClasses\IsFinal;
use Arkitect\Expression\ForClasses\IsNotFinal;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\NotHaveDependencyOutsideNamespace;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\Rules\Rule;

return static function (Config $config): void {
    $classSet = ClassSet::fromDir(__DIR__.'/src');
    $rules = [];

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\AlloListe\Domain'))
        ->should(new NotHaveDependencyOutsideNamespace('App\AlloListe\Domain'))
        ->because('we want protect our domain');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Shared\Domain'))
        ->should(new NotHaveDependencyOutsideNamespace('App\Shared\Domain'))
        ->because('we want protect our domain');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces(
            'App\AlloListe\Application\Command',
            'App\AlloListe\Application\Query',
            'App\Shared\Application\Command',
            'App\Shared\Application\Query',
        ))
        ->should(new IsReadonly())
        ->because('Command and Query should be readonly');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces(
            'App\AlloListe\Domain\ValueObject',
            'App\Shared\Domain\ValueObject',
        ))
        ->should(new IsReadonly())
        ->because('Value objects should be readonly');

    $rules[] = Rule::allClasses()
        ->except('App\AlloListe\Domain\Model')
        ->that(new ResideInOneOfTheseNamespaces('App'))
        ->should(new IsFinal())
        ->because('All classes have to be final');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\AlloListe\Domain\Model'))
        ->should(new IsNotFinal())
        ->because('Entities should not be final, because Doctrine cannot work with final classes');

    $config
        ->add($classSet, ...$rules);
};
