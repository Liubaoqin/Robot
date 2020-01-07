<?php
/**
 * Created by PhpStorm.
 * User: bobiscool
 * Date: 2019/12/28
 * Time: 17:28
 */

namespace App\Common;


class Common
{
    //objiect 转 array
    public static function object_array($object)
    {
        return json_decode( json_encode( $object),true);
    }

    //随机数据去重
    public static function Randnum($number,$max)
    {
        $str = range (1,$max);
        shuffle ($str);
        $result = array_slice($str,0,$number);
        return $result;
    }

    //随机数组
    public static function RanArray($data,$max)
    {
        shuffle ($data);
        $result = array_slice($data,0,$max);
        return $result;
    }


    /**
     * 中奖概率算法
     * v表示中奖概率,注意其中的v必须为整数,可以将对应的奖项的v设置成0，即意味着该奖项抽中的几率是0，数组中v的总和（基数），基数越大越能体现概率的准确性。
     * 本例中v的总和为100，那么特等奖 对应的中奖概率就是1%，如果v的总和是10000，那中奖概率就是万分之一。
     *
     * $arr = array(
            array('id'=>1,'name'=>'特等奖','v'=>1),
            array('id'=>2,'name'=>'一等奖','v'=>5),
            array('id'=>3,'name'=>'二等奖','v'=>10),
            array('id'=>4,'name'=>'三等奖','v'=>12),
            array('id'=>5,'name'=>'四等奖','v'=>22),
            array('id'=>6,'name'=>'没中奖','v'=>50)
            );
     */
    public static function get_rand($proArr)
    {
        $result = array();
        $arr = [];
        foreach ($proArr as $key => $val) {
            $arr[$key] = $val['v'];
        }

        // 概率数组的总概率「为100」
        $proSum = array_sum($arr);

        asort($arr);// 根据键值对数组进行升序排序

        // 概率数组循环
        foreach ($arr as $k => $v) {
            $randNum = mt_rand(1, $proSum); // 在 1和 总权重 之间返回随机整数

            if ($randNum <= $v) {
                $result = $proArr[$k];
                break;
            } else {
                $proSum -= $v;
            }
        }
        return $result;
    }
}