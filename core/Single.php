<?php

namespace core;

/**
 * Class Single
 * @package core
 */
class Single
{
    protected static $instance = null;

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}
