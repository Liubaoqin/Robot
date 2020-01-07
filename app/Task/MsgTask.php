<?php
/**
 * Created by PhpStorm.
 * User: bobiscool
 * Date: 2019/12/27
 * Time: 14:21
 */

namespace App\Task;

use App\Common\Common;
use App\Common\Redis;
use App\Model\RoomModel;
use App\Model\UsersModel;
use App\Model\CommentModel;
use App\Model\HeadimgModel;

class MsgTask
{

    protected static $appId = '1400289044';
    protected static $usersig = "eJwtzEELgkAUBOD-stdCnq7LptAhIepQXjKoo7EveYi67W7WFv33RD3ON8N8WXE4BT0alrIoALYcMylsHd1p5FI11JJ1pnSdmQdW1aXWpFgaxgDRKoE4nhp8azI4uBAiAoBJHTWjSck5yGTeWqqG-2xf*8*2eHJ89Gebtd0C8eX9LdvxS7jROdUcKyv1Mb*u2e8Pdtw1yQ__";
    protected static $in_Room_nubmer = 2;  //单纯进入房间的人数
    protected static $in_Room_speak = 2;   //进入房间说话的人数
    protected static $Init_robot_number = [
        'start' => 150,
        'end'   => 300,
    ];  //每个直播间机器人个数随机区间 150~300
    protected static $random_in_room = [
        'start' => 5,
        'end'   => 10,
    ];  //进入直播间的时间  5~10s
    protected static $random_in_speak = [
        'start' => 10,
        'end'   => 15,
    ];  //直播间说话的时间  10~15s

    //直播间发言
    public function roomSpeak()
    {
        //在线主播
        $row = RoomModel::OnlineRoom();

        foreach ($row as $room) {
            $room = $room->roomID;
            $data = Redis::sMembers('FM_' . $room . '_Muster');


            //随机数组
            $people_Ran = rand(1, self::$in_Room_speak);
            $people = Common::RanArray($data, $people_Ran);

            //随机发言
            $speak = $this->RanSpeak($people_Ran);

            //拼成一个用户
            foreach ($people as $k => $v) {
                $people[$k]['content'] = $speak[$k]['content'];
            }

            //概率
            $proArr = array(
                '0' => array('id' => 1, 'prize' => false, 'v' => 50),
                '1' => array('id' => 2, 'prize' => true, 'v' => 50),
            );

            foreach ($people as $va)
            {
                if (Common::get_rand($proArr)['prize'])
                {
                    var_dump($va);
                    $random = mt_rand(111111, 9999999);
                    $post = [
                        'GroupId' => $room,
                        'Random' => $random,
                        'MsgBody' => [
                            ['MsgType' => 'TIMTextElem',
                                'MsgContent' => ['Text' => json_encode($va)]
                            ]
                        ]
                    ];
                    $url = "https://console.tim.qq.com/v4/group_open_http_svc/send_group_msg?sdkappid=" . self::$appId . "&identifier=administrator&usersig=" . self::$usersig . "&random={$random}&contenttype=json";
                    $client = new \EasySwoole\HttpClient\HttpClient($url);
                    $res = json_decode($client->post(json_encode($post))->getBody(), true);
                    var_dump($res);
                }
            }
        }
    }

    //进入直播间
    public function inRoom()
    {
        //在线主播
        $row = RoomModel::OnlineRoom();

        foreach ($row as $room) {
            $room = $room->roomID;
            $room_Id = 'FM_' . $room . '_List';
            //是否开始了
            $redis_room = Redis::get($room_Id);
            if (!$redis_room) {
                //第一次给进入房间的人数
                $people_init = rand(self::$Init_robot_number['start'], self::$Init_robot_number['end']);
                Redis::set($room_Id, $people_init, 3600);
            }

            $room_incr = 'FM_' . $room . '_Number';

            //机器人数量
            $robot_nubmer = rand(1, self::$in_Room_nubmer);

            $dynamic_people = Redis::get($room_incr);
            if (!$dynamic_people) {
                Redis::set($room_incr, $robot_nubmer);
            } else {
                if ($redis_room > $dynamic_people) {
                    //限制数量
                    Redis::set($room_incr, $dynamic_people + $robot_nubmer);

                    //获取用户数据
                    $userdata = $this->getUset($robot_nubmer);

                    //发送入场信息
                    foreach ($userdata as $va)
                    {
                        $random = mt_rand(111111, 9999999);
                        $content = ['type' => 'robot', 'data' => '', 'nickname' => $va['name'], 'headimg' => $va['img']];

                        Redis::sAdd('FM_' . $room . '_Muster', $content);

                        $post = [
                            'GroupId' => $room,
                            'Random' => $random,
                            'MsgBody' => [
                                ['MsgType' => 'TIMTextElem',
                                    'MsgContent' => ['Text' => json_encode($content)]
                                ]
                            ]
                        ];
                        $url = "https://console.tim.qq.com/v4/group_open_http_svc/send_group_msg?sdkappid=" . self::$appId . "&identifier=administrator&usersig=" . self::$usersig . "&random={$random}&contenttype=json";
                        $client = new \EasySwoole\HttpClient\HttpClient($url);
                        $res = json_decode($client->post(json_encode($post))->getBody(), true);
                        var_dump($res);
                    }

                }
            }
        }

    }

    public function getUset( $robot_nubmer )
    {
        //用户最大的值
        $max_user = UsersModel::Maxnum();
        //随机取用户的id
        $user_str = Common::Randnum($robot_nubmer, $max_user);
        //查询用户
        $user_arr = Common::object_array( UsersModel::Find($user_str) );

        //图片最大的值
        $max_img = HeadimgModel::Maxnum();
        //随机取图片的id
        $img_str = Common::Randnum($robot_nubmer, $max_img);
        //查询图片
        $img_arr = Common::object_array( HeadimgModel::Find( $img_str) );

        //拼成一个用户
        foreach ($user_arr as $k => $v)
        {
            $user_arr[$k]['img'] = $img_arr[$k]['img'];
        }
        return $user_arr;
    }

    public function RanSpeak($robot_nubmer)
    {
        //评论最大的值
        $max_com = CommentModel::Maxnum();
        //随机取评论的id
        $com_str = Common::Randnum($robot_nubmer, $max_com);
        //查询评论
        return Common::object_array( CommentModel::Find($com_str) );
    }

    public function inRoomTimer()
    {
        $str = rand(self::$random_in_room['start'],self::$random_in_room['end']);
        echo $str;
        \Swoole\Timer::after($str*1000, function(){
            echo "inRoomTimer====timeout\n";
            $this->inRoom();
            $this->inRoomTimer();
        });
    }

    public function roomSpeakTimer()
    {
        $str =rand(self::$random_in_speak['start'],self::$random_in_speak['end']);
        echo $str;
        \Swoole\Timer::after($str*1000, function(){
            echo "roomSpeakTimer========timeout\n";
            $this->roomSpeak();
            $this->roomSpeakTimer();
        });
    }

    public function init()
    {
        $this->inRoomTimer();
        \Swoole\Timer::after(2000, function(){
            echo "init========timeout\n";
            $this->roomSpeakTimer();
        });
        echo 'this is MainServerStartListener Listener';
    }

}