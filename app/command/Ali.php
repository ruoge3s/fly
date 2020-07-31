<?php
declare(strict_types=1);

namespace app\command;

use core\Command;
use core\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use OSS\Core\OssException;
use OSS\OssClient;

/**
 * Class Ali
 * 阿里云服务测试
 * @package app\command
 */
class Ali extends Command
{
    protected $name = '4671d150f8fe7ef7b8e7f4b5b5e20e76.jpg';
    /**
     * oss upload test
     */
    public function out()
    {
        $config = Config::instance()->get('ali.oss');
        try {
            $ossClient = new OssClient($config['akid'], $config['secret'], $config['endpoint']);
            $ossClient->uploadFile($config['bucket']['default'], $this->name, BASE_DIR . '/storage/' . $this->name);
            print_r('Success');
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }

    /**
     * oss image is exits
     */
    public function oiie()
    {
        $config = Config::instance()->get('ali.oss');
        try {
            $ossClient = new OssClient($config['akid'], $config['secret'], $config['endpoint']);
            $res = $ossClient->doesObjectExist($config['bucket']['default'], $this->name);
            var_dump($res);
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }

    /**
     * 获取阿里云ACM配置
     * @throws GuzzleException
     */
    public function acm()
    {
        $config = Config::instance()->get('ali.acm');

        // curl acm.aliyun.com:8080/diamond-server/diamond

        $ip = '139.196.135.144';

        $client = new Client([
            'base_uri'  => "$ip:8080",
            'timeout'   => 35
        ]);

        $ts = round(microtime(true) * 1000);

        $signStr = "{$config['namespace']}+{$config['group']}+{$ts}";

        $response = $client->get('/diamond-server/config.co', [
            'headers'   => [
                'Spas-AccessKey'    => $config['access_key'],
                'timeStamp'         => $ts,
                'Spas-Signature'    => base64_encode(hash_hmac('sha1', $signStr, $config['secret_key'],true)),
            ],
            'query'      => [
                "tenant"    => $config['namespace'],
                "dataId"    => $config['data_id'],
                "group"     => $config['group'],
            ]
        ]);

        $c = $response->getBody()->getContents();

        echo iconv("GB2312//IGNORE", "UTF-8", $c);
    }
}
