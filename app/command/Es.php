<?php

namespace app\command;

use core\Command;
use core\Config;
use core\Log;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class Es extends Command
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(array $config = [])
    {
        $config = Config::instance()->get('es');

        parent::__construct($config);
        $this->client = ClientBuilder::create()
            ->setHosts($config['hosts'])
            ->setLogger(Log::instance()->getLogger())
            ->build();
    }


    public function test()
    {
        $params = [
            'index' => 'my_index',
            'id'    => 'my_id',
            'body'  => ['testField' => 'abc']
        ];

        $response = $this->client->index($params);
        print_r($response);
    }
}
