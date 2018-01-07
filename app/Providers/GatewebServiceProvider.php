<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GatewebServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $path=__DIR__."/../../../_class";
        require_once("$path/gateweb/common/framework/Psr4Autoloader.php");
        $loader = new \gateweb\common\framework\Psr4Autoloader;
        $loader->register();
        $loader->addNamespace('gateweb', "$path/gateweb");                  
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
