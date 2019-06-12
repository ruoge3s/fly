<?php

# 未知异常
define('UNKNOWN_ERROR', -1);

# 正常请求
define('OK', 0);

# HTTP 请求相关异常 2000;
define('HTTP_METHOD_ERROR', 2001);  # 请求方式错误
define('HTTP_PARAMETER_ERROR', 2002);   # 请求参数必填错误

## 签名相关 5000 ～ 5100
define('SIGNATURE_ORIGIN_ERROR', 5001); # 原始签名缺失
define('SIGNATURE_TIMESTAMP_ERROR', 5002); # 签名时间戳错误
define('SIGNATURE_VALIDATE_ERROR', 5003); # 签名校验失败

## 设备相关 5200 ～ 5299
define('DEVICE_NOT_EXISTS', 5201); # 查询设备不存在

## 广告相关 5300 ～ 5399
define('NO_AD', 5301);  # 无广告
