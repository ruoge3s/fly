<?php

namespace app\command;

use core\Command;

/**
 * Class Init
 * @describe 初始化相关
 * @package app
 */
class Init extends Command
{
    /**
     * 配置的db信息
     * @var array
     */
    public $db;

    /**
     * @describe 输出配置中的db信息
     */
    public function show()
    {
        print_r($this->db);
    }
}

