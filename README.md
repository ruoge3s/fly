# Fly
会飞的框架

## 生产环境安装
```bash
composer install --no-dev
```

> composer安装会自动复制.env文件和生成runtime目录

## 使用说明

### HTTP服务

> 只能使用响应json数据

#### 1 创建controller
```php
namespace app\controller;

use core\Controller;
use core\traits\Message;

class Index extends Controller
{
    use Message;

    /**
     * @route /index
     * @return array
     */
    public function index()
    {
        return self::success('欢迎使用Fly.');
    }

    public function hello()
    {
        return self::success('hello! Fly.');
    }

    /**
     * @route /tk
     * @return array
     */
    public function thankYou()
    {
        return self::success('Thank you! Fly.');
    }
}

```

#### 2 使用路由
```php
# 注解路由,在注释中添加 @route 路由信息即可
# 如下例子,在浏览器中输入 localhost/index即可访问
/**
 * @route /index
 * @return array
 */
public function index()
{
    return self::success('欢迎使用Fly.');
}
```

#### 3 http请求

### 控制台命令

#### 1 创建command

#### 2 使用命令

### docker环境运行

```bash
docker run -d --rm --name fly -v $(pwd)/fly:/var/www -p 8081:9000 fly:1.1 php bin/fly Multi:process

docker run -it --rm --name fly -v $(pwd)/fly:/var/www -p 8081:9000 fly:1.1 php bin/fly Multi:process
```
