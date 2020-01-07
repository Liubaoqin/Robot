<?php
/**
 * Created by PhpStorm.
 * User: bobiscool
 * Date: 2019/12/30
 * Time: 11:21
 */

namespace App\Model;

use Hyperf\DbConnection\Db;
class HeadimgModel
{
    protected static $connection = 'robot';
    protected static $tabel = 'headimg';

    //max
    public static function Maxnum()
    {
        return Db::connection(self::$connection)->table(self::$tabel)->max('id');
    }

    //find
    public static function Find($str)
    {
        return Db::connection(self::$connection)->table(self::$tabel)->whereIn('id',$str)->get();
    }
}