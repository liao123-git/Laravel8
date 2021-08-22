<?php


namespace App\Servers;


class BaseServers
{
    protected static $instance;

    /**
     * @return static
     * */
    public static function getInstance()
    {
        if (static::$instance instanceof static) {
            return static::$instance;
        }
        static::$instance = new static();
        return static::$instance;
    }

    private function __clone()
    {
    }

    private function __construct()
    {
    }
}
