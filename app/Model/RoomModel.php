<?php
/**
 * Created by PhpStorm.
 * User: bobiscool
 * Date: 2019/12/27
 * Time: 15:59
 */

namespace App\Model;


use Hyperf\DbConnection\Db;

class RoomModel
{
    protected static $connection = 'user';
    protected static $tables = 'Column';

    //在线房间
    public static function OnlineRoom()
    {
        return Db::connection(self::$connection)->table(self::$tables)->where('live_status',1)->get(['live_status','roomID']);
    }
}