<?php

namespace core;

class Model
{
    public function __construct(Array $data = [])
    {
        App::configure($this, $data);
    }

    protected function set()
    {

    }

    protected function get()
    {

    }
}
