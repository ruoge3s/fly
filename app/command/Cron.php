<?php

namespace app\command;

use core\Command;

/**
 * Class Cron
 * @describe 定时任务管理
 * @package app\command
 */
class Cron extends Command
{
    /**
     * 分时日月周
     * @var array
     */
    public $rule = ['*/1', '6-23', '*', '*', '*'];

    public $flock = true;

    public function generate()
    {

    }

    public function cmd()
    {

    }

    protected function flock()
    {

    }
}
