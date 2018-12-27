<?php

namespace core;

class Config extends Single
{
    protected $info = null;

    public function load($info)
    {
        $this->info = $info;
    }

    public function get()
    {
        return $this->info;
    }
}