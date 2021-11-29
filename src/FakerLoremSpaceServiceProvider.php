<?php

namespace Walirazzaq\FakerLoremSpace;

use Faker\Generator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FakerLoremSpaceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name('faker-lorem-space');
    }

    public function bootingPackage()
    {
        $this->app->resolving(Generator::class, function ($faker, $app) {
            $faker->addProvider(new FakerLoremSpace($faker));
        });
    }
}
