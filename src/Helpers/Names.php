<?php

namespace ModularLaravel\Helpers;

use Illuminate\Support\Stringable;

class Names
{
    public static function domain(): Stringable
    {
        return self::name("modular-laravel.domainFolderName");
    }

    public static function app(): Stringable
    {
        return self::name("modular-laravel.appFolderName");
    }

    /**
     * @return Stringable
     */
    protected static function name(string $setting): Stringable
    {
        return str(config($setting))->camel()->ucfirst();
    }
}
