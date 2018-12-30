<?php

namespace app\command;

use core\Command;
use core\Http;
use Swoole\Coroutine;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

/**
 * Class HttpServer
 * @describe HTTP服务(协程)
 * @package app\command
 */
class HttpServer extends Command
{
    public $host = 'localhost';

    public $port = 80;

    /**
     * @time 2018-12-29 21:37:26
     */
    public function run()
    {
        $httpHandler = new Http();
        $http = new Server($this->host, $this->port);

        $http->on("start", function (Server $server) {
            echo "协程HTTP服务器\nhttp://{$this->host}:{$this->port}\n";
        });

        $http->on("request", function (Request $request, Response $response) use ($httpHandler) {
            Coroutine::create(function () use ($request, $response, $httpHandler) {
                if (isset($request->header['content-type']) && $request->header['content-type'] == "application/json") {
                    $data = $httpHandler->parse($request)->run();
                    $response->header("Content-Type", "application/json");
                    $response->end(json_encode($data));
                } else {
                    $response->status(400);
                    $response->end();
                }
            });
        });

        $http->start();
    }
}


