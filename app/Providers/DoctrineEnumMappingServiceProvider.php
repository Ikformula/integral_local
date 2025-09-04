<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\MySqlPlatform;

class DoctrineEnumMappingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $doctrinePlatform = app('db')->connection()->getDoctrineConnection()->getDatabasePlatform();

        if ($doctrinePlatform instanceof MySqlPlatform) {
            Type::addType('enum', 'Doctrine\DBAL\Types\StringType');
            $doctrinePlatform->registerDoctrineTypeMapping('enum', 'string');
        }
    }
}
