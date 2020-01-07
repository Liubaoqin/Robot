<?php
/**
 * Created by PhpStorm.
 * User: bobiscool
 * Date: 2019/12/31
 * Time: 10:03
 */

namespace App\Common;

use Hyperf\Utils\ApplicationContext;

class Redis
{
    public static function get($str)
    {
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        $data = $redis->get($str);
        if ($data) {
            return unserialize($data);
        }
        return false;
    }

    public static function set($str, $data, $time = null)
    {
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        if($time){
            return $redis->set($str, serialize($data), $time);
        }else{
            return $redis->set($str, serialize($data));
        }
    }

    public static function del($str)
    {
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        return $redis->del($str);
    }

    public static function sAdd($key,$data)
    {
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        return $redis->sAdd($key, serialize($data) );
    }

    public static function sMembers($key)
    {
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        $arr = $redis->sMembers($key);
        foreach ($arr as $k=>$v)
        {
            $arr[$k] = unserialize($v);
        }
        return $arr;
    }

    public static function srem($key,$data)
    {
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        return $redis->srem($key, $data);
    }
}