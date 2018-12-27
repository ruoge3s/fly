<?php

namespace app\model;

use core\Model;


/**
 * 设备数据模型
 * Class Device
 * @package model
 */
class Device extends Model
{
    public $id = 0;

    public $vendor = '设备制造商';

    public $model = '设备型号';

    public $ipv4 = '192.168.1.1';

}
