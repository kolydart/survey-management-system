<?php

$path_relative = __DIR__.'/../../../_class/gateweb/laravel/serviceProviders/GatewebServiceProvider.php';
$path_absolute = '/var/www/_class/gateweb/laravel/serviceProviders/GatewebServiceProvider.php';

if (file_exists($path_relative)) {
    require $path_relative;
} elseif (file_exists($path_absolute)) {
    require $path_absolute;
} else {
    throw new \Exception('Could not load library. Error PmzLtGX1IhEzvyvr', 500);
}
