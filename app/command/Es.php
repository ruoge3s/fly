<?php

namespace app\command;

use core\Command;
use core\Config;
use core\Log;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

/**
 * Class Es
 * @docs https://www.elastic.co/guide/cn/elasticsearch/php/current/_quickstart.html
 * @package app\command
 */
class Es extends Command
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $es = Config::instance()->get('es');
        $this->client = ClientBuilder::create()
            ->setHosts($es['hosts'])
            ->setLogger(Log::instance()->getLogger())
            ->build();
    }


    public function test()
    {
        $params = [
            'index' => 'my_index',
            'id'    => 'my_id',
            'body'  => ['testField' => 'ccd']
        ];

        $response = $this->client->index($params);
        print_r($response);
    }

    public function getIndex()
    {
        $res = $this->client->get([
            'index' => 'my_index',
            'id'    => 'my_id'
        ]);

        print_r($res);
    }
}
