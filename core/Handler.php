<?php

namespace core;

abstract class Handler
{
    protected $config;

    public function __construct()
    {
        $this->config = Config::instance()->get();
    }

    /**
     * 执行处理
     * @return mixed
     */
    abstract public function run();

    /**
     * 获取要调用的类和类方法
     * @return mixed
     */
    abstract protected function cm();
}