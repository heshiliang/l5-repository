<?php

namespace Prettus\Repository\Helpers;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;


/**
 * Class CacheKeys
 * @package Prettus\Repository\Helpers
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class CacheKeys
{

    /**
     * @var CacheRepository
     */
    protected static $cache = null;


    /**
     * @var array
     */
    protected static $keys = null;

    public function __construct()
    {

    }

    /**
     * @param $group
     * @param $key
     *
     * @return void
     */
    public static function putKey($group, $key)
    {
        self::$cache = app(config('repository.cache.repository', 'cache'));

        self::loadKeys($group);

        if (!in_array($key, self::$keys)) {
            self::$keys[] = $key;
        }
        self::storeKeys($group);
    }

    /**
     * @return array|mixed
     */
    public static function loadKeys($group)
    {
        self::$cache = app(config('repository.cache.repository', 'cache'));

        self::$keys=self::$cache->get($group);

        self::$keys=self::$keys?self::$keys:[];

        return self::$keys;
    }

    /**
     * @return int
     */
    public static function storeKeys($group)
    {
        $cache1 = app('cache');

        return self::$cache->put($group,  self::$keys,config('repository.cache.minutes', 1440));
    }


    /**
     * @param $group
     *
     * @return array|mixed
     */
    public static function getKeys($group)
    {
        self::loadKeys($group);

        return self::$keys;
    }

    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $instance = new static;

        return call_user_func_array([
            $instance,
            $method
        ], $parameters);
    }

    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $instance = new static;

        return call_user_func_array([
            $instance,
            $method
        ], $parameters);
    }
}
