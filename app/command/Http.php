<?php

namespace app\command;

use core\Command;
use core\Http as CoreHttp;
use Swoole\Coroutine;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use Swoole\Process;

/**
 * Class HttpServer
 * @describe HTTP服务(协程)
 * @package app\command
 */
class Http extends Command
{
    public $host = 'localhost';

    public $port = 9191;

    public $pidFile = BASE_DIR . 'runtime/server.pid';

    /**
     * @time 2018-12-29 21:37:26
     */
    public function run()
    {
        $httpHandler = new CoreHttp();
        $http = new Server($this->host, $this->port);

        $http->set([
//            'worker_num' => 8,
            'daemonize' => false,
            'log_file' => BASE_DIR . 'runtime/swoole.log',
            'pid_file' => $this->pidFile,
        ]);

        $http->on("start", function (Server $server) {
            echo "{$server->manager_pid} {$server->master_pid} {$server->worker_pid}\n";
            echo date('Y-m-d H:i:s') . " Start coroutine server\n  http://{$server->host}:{$server->port}\n";
        });

        $http->on('shutdown', function (Server $server) {
            echo date('Y-m-d H:i:s') . " Shutdown coroutine server\n";
        });

        $http->on("request", function (Request $q, Response $p) use ($httpHandler) {
            Coroutine::create(function () use ($q, $p, $httpHandler) {
                $p->header("Content-Type", "application/json; charset=UTF-8");
                if (!isset($httpHandler->routers[$q->server['path_info']])) {
                    $q->server['path_info'] = '/error/not-found';
                }
                $data = $httpHandler->parse($q)->run();
                $p->end(json_encode($data, JSON_UNESCAPED_UNICODE));
            });
        });

        $http->start();
    }

    public function close()
    {
        if (is_file($this->pidFile)) {
            $pid = file_get_contents($this->pidFile);
            Process::kill($pid);
            echo "close {$pid}\n";
        }
    }
}


