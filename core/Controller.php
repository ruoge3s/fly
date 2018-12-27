<?php

namespace core;

class Controller extends App
{
    protected function home(): string
    {
        return 'app/controller';
    }

    protected static function restrain(): string
    {
        return static::class;
    }

    public function execute($m)
    {
        if (method_exists($this, $m)) {
            return $this->$m();
        } else {
            return ['方法不存在'];
        }
    }

}

