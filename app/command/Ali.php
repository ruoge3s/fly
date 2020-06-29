<?php
declare(strict_types=1);

namespace app\command;

use core\Command;
use core\Config;
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
}
