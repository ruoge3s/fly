# fly
会飞的框架


## 生产环境安装
/usr/local/bin/composer install --no-dev

## 服务器调整

### 调整并发http请求的能力
vim /etc/sysctl.conf

```ini
net.ipv4.tcp_syncookies = 1
net.ipv4.tcp_tw_reuse = 1
net.ipv4.tcp_tw_recycle = 1
net.ipv4.tcp_fin_timeout = 30
```

### 调整hosts，加快域名解析
