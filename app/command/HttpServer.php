<?php

namespace app\command;

use core\Command;
use core\Controller;
use core\Http;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class HttpServer extends Command
{
    public $host = 'localhost';

    public $port = 80;

    public function run()
    {
        $httpHandler = new Http();
        $http = new Server($this->host, $this->port);

        $http->on("start", function (Server $server) {
            echo "Swoole http server is started at http://{$this->host}:{$this->port}\n";
        });

        $http->on("request", function (Request $request, Response $response) use ($httpHandler) {
            if (isset($request->header['content-type']) && $request->header['content-type'] == "application/json") {
                $data = $httpHandler->parse($request)->run();
            } else {
                $data = ['message' => 'The Request Header must be application/json'];
            }
            $response->header("Content-Type", "application/json");
            $response->end(json_encode($data));
        });

        $http->start();
    }
}


