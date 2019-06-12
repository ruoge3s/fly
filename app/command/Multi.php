<?php
namespace app\command;

use core\Command;
use Swoole\Process\Pool;

/**
 * Class Multi
 * @describe 添加多进程任务示例
 * @package app\command
 */
class Multi extends Command
{
    public $workNum = 5;

    public function process()
    {
        $pool = new Pool($this->workNum);

        $pool->on("WorkerStart", function (Pool $pool, $workerId) {
            $running = true;
            pcntl_signal(SIGTERM, function () use (&$running, $workerId) {
                echo "#{$workerId}  收到退出信号，正在终止进程...\n";
                $running = false;
            });
            echo "Worker#{$workerId} is started\n";
            while ($running) {
                $msg = "#{$workerId} " . date('Y-m-d H:i:s');
                sleep(rand(3, 10)); // 模拟业务处理
                echo "$msg\n";
                pcntl_signal_dispatch();
            }
        });

        $pool->on("WorkerStop", function ($pool, $workerId) {
            echo "Worker#{$workerId} is stopped\n";
        });

        $pool->start();
    }
}