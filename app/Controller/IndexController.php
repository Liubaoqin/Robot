<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;



use App\Common\Common;
use App\Common\Redis;
use App\Model\RoomModel;
use App\Model\UsersModel;
use App\Model\CommentModel;
use App\Model\HeadimgModel;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController()
 */
class IndexController extends AbstractController
{
    public function index()
    {
        return 'this is my Cutie';
    }
}
