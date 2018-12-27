<?php

namespace core;

class App
{
    public static function configure($object, array $data)
    {
        foreach ($data as $k => $v) {
            $object->$k = $v;
        }
    }
}