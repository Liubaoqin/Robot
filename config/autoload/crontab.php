<?php
/**
 * Created by PhpStorm.
 * User: bobiscool
 * Date: 2019/12/27
 * Time: 12:42
 */
use Hyperf\Crontab\Crontab;
return [
    'enable' => false,
    // 通过配置文件定义的定时任务
    'crontab' => [
        (new Crontab())->setName('Msg')->setRule('*/20 * * * * *')->setCallback([App\Task\MsgTask::class, 'execute'])->setMemo('每5秒给开播的房间发送信息'),
    ],
];