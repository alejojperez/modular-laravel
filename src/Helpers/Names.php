<?php

namespace ModularLaravel\Helpers;

use Illuminate\Support\Stringable;

class Names
{
    public static function domain(): Stringable
    {
        return self::config("modular-laravel.domainFolderName");
    }

    public static function app(): Stringable
    {
        return self::config("modular-laravel.appFolderName");
    }

    public static function config(string $setting): Stringable
    {
        return self::name(config($setting));
    }

    public static function name(string $str): Stringable
    {
        return str($str)->camel()->ucfirst();
    }
}
