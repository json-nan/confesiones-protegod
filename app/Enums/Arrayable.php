<?php

namespace App\Enums;

trait Arrayable
{
    public static function names()
    {
        return array_column(self::cases(), 'name');
    }

    public static function values()
    {
        return array_column(self::cases(), 'value');
    }

    public static function array()
    {
        return array_combine(self::values(), self::names());
    }
}
