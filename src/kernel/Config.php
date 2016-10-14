<?php
/**
 * Created by PhpStorm.
 * User: Tayna
 * Date: 13.10.2016
 * Time: 23:48
 */

namespace kernel;

/**
 * Class Config
 * pattern Singleton
 * @package kernel
 */
class Config
{
    CONST CONFIG_FILE = '/../../config/config.ini';

    /** @var array*/
    private static $config;

    /**
     * @return array
     */
    public static function getInstance()
    {
        if (null === static::$config) {
            static::$config = parse_ini_file(__DIR__ . self::CONFIG_FILE);
        }
        return static::$config;
    }
}