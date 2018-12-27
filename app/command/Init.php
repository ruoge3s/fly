<?php

namespace app\command;

use app\logic\NormalConsumer;
use core\Command;
use app\model\AdSlot;
use app\model\Device;
use Swoole\Coroutine\Http\Client;
use Swoole\Coroutine\Redis;
use Swoole\Timer;

/**
 * Class Init
 * @describe 初始化相关
 * @package app
 */
class Init extends Command
{
    public $db;

    public function show()
    {
        print_r($this->db);
    }

    public function test()
    {
        mkdir('./runtime');
    }
}

