<?php
/**
 * Created by PhpStorm.
 * User: bobiscool
 * Date: 2020/1/6
 * Time: 16:52
 */

namespace App\Event;


use App\Task\MsgTask;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\OnStart;


/**
 * @Listener()
 */
class MainServerStartListener implements ListenerInterface
{
    public function listen(): array
    {
        // TODO: Implement listen() method.
        return [
            OnStart::class
        ];

    }

    public function process(object $event)
    {
        // TODO: Implement process() method.
        (new MsgTask())->init();
    }
}