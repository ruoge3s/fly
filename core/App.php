<?php

namespace core;

abstract class App
{
    /**
     * 获取处理器的家目录(控制器和command)
     * @return string
     */
    abstract protected function home(): string ;

    /**
     * 获取直接继承当前类的子类的类名
     * @return string
     */
    abstract protected static function restrain(): string;

    public function __construct(array $config = [])
    {
        App::configure($this, $config);
    }

    public static function configure($object, array $data)
    {
        foreach ($data as $k => $v) {
            $object->$k = $v;
        }
    }

    public function load(array $attributes = [])
    {
        $properties = $this->publicProperties();
        foreach ($attributes as $attribute => $value) {
            if (isset($properties[$attribute])) {
                $this->$attribute = $value;
            } else {
                return false;
            }
        }
        return true;
    }


    /**
     * 解析注释
     * @param $text
     * @return array
     * @time 2018-12-22 11:11:50
     */
    protected function parseComment($text)
    {
        $res = preg_replace(['/^\/\*{2}/', '/\*{1}/', '/\/$/'], '', $text);
        $lines = explode("\n", $res);

        $key = 'default';
        $comments = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            if ($line[0] == '@') {
                $lineKV = explode(' ', $line);
                $key = substr($lineKV[0], 1);
                $line = ltrim($line, $lineKV[0] . " ");
            }
            $comments[$key] = isset($comments[$key]) ? array_merge($comments[$key], [$line]): [$line];
        }

        return $comments;
    }

    /**
     * 获取App类
     * @return array
     * @time 2018-12-22 12:18:56
     */
    public function classes()
    {
        $apps = [];
        $list = scandir(BASE_DIR . $this->home());
        foreach ($list as $filename) {
            if (preg_match('/(^[A-Z]\w+)(\.php)$/', $filename, $matches)) {
                $className = dir2namespace($this->home()) . '\\' . $matches[1];
                if (class_exists($className)) {
                    try {
                        $r = new \ReflectionClass($className);
                        if ($r->getParentClass() and $r->getParentClass()->name == static::restrain()) {
                            $apps[$className] = $this->parseComment($r->getDocComment());
                        } else {
                            echo sprintf("Class $className need to extend from %s\n", static::restrain());
                            exit();
                        }
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                        exit();
                    }
                }
            }
        }
        return $apps;
    }

    /**
     * 获取public方法
     * @return array
     * @time 2018-12-22 10:20:53
     */
    public function publicMethods()
    {
        $staticClassMethods =  array_diff(
            get_class_methods(static::class),
            get_class_methods(self::class)
        );

        $publicMethods = [];

        foreach ($staticClassMethods as $method) {
            $method && $publicMethods[$method] = $this->publicMethodComment($method);
        }

        return $publicMethods;
    }

    protected function publicMethodComment($method)
    {
        try {
            $methodObj = new \ReflectionMethod(static::class, $method);
        } catch (\Exception $e) {
            echo __FILE__ . ' ' . $e->getMessage() . "\n";
            exit();
        }

        if ($methodObj->isPublic()) {
            return $this->parseComment($methodObj->getDocComment());
        } else {
            return null;
        }
    }

    /**
     * 获取public属性
     * @return array
     */
    public function publicProperties()
    {
        $publicProperties = [];
        try {
            $rc = new \ReflectionClass(static::class);
            $properties = $rc->getProperties();
            foreach ($properties as $property) {
                if ($property->class != self::class) {
                    $publicProperties[$property->name] = $this->parseComment($property->getDocComment());
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            exit();
        }
        return $publicProperties;
    }
}