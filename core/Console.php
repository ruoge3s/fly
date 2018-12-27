<?php

namespace core;

/**
 * Class Console
 * @package core
 */
class Console extends Handler
{

    protected $argv = null;

    /**
     * @param $data
     * @return $this
     */
    public function setArgv(array $data)
    {
        $this->argv = $data;
        return $this;
    }

    /**
     * run
     */
    public function run()
    {
        list($cn, $m) = $this->cm();
        if ($cn) {
            $className = '\\app\\command\\' . $cn;
            if (class_exists($className)) {
                $class = new $className($this->config);
                if ($class instanceof Command) {
                    $class->execute($m);
                    return;
                }
            }
        }
        (new Command($this->config))->cmdTips();
        return;
    }

    /**
     * 获取 class 和 method
     * @return array
     */
    protected function cm()
    {
        $class = isset($this->argv[1]) ? $this->argv[1] : '';
        $method = isset($this->argv[2]) ? $this->argv[2] : '';
        return [$class, $method];
    }

    public function parse()
    {
        $assignMap = [];
        if (count($this->argv) > 3) {
            $kvs = array_slice($this->argv, 3);
            foreach ($kvs as $kv) {
                if (preg_match('/(^[a-zA-Z_][0-9a-zA-Z_]*)=(.*)/', $kv, $ms)) {
                    $assignMap[$ms[1]] = $ms[2];
                } else {
                    exit("Class Attribute name isn`t correct!!!\n");
                }
            }
        }

        return $assignMap;
    }
}