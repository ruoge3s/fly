FROM centos:7.6.1810

MAINTAINER ruoge3s@qq.com

RUN rpm --import /etc/pki/rpm-gpg/RPM-GPG-KEY-CentOS-7
RUN yum update -y

# 安装下载工具及编译所需的工具
RUN yum install -y gcc autoconf glibc-headers gcc-c++ wget unzip

# 安装PHP所需的库文件
RUN yum install -y libxml2 libxml2-devel openssl openssl-devel libcurl libcurl-devel libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel pcre pcre-devel zlib zlib-devel

ENV SRC_DIR /usr/src

# 编译安装PHP所需的re2c
ENV RE2C 1.1.1
COPY container/re2c-$RE2C.tar.gz $SRC_DIR
RUN tar -zxvf $SRC_DIR/re2c-$RE2C.tar.gz -C $SRC_DIR \
&& cd $SRC_DIR/re2c-$RE2C \
&& ./configure && make && make install

# 安装PHP7所需的bison
ENV BISON bison-2.4.1
COPY container/$BISON.tar.gz $SRC_DIR
RUN tar -zxf $SRC_DIR/$BISON.tar.gz -C $SRC_DIR \
&& cd $SRC_DIR/$BISON && ./configure && make && make install

# 安装PHP 可以根据自己实际情况进行配置
ENV PHP php-7.3.6
ENV PHP_PATH /usr/local/php
COPY container/$PHP.zip $SRC_DIR
RUN unzip $SRC_DIR/$PHP.zip -d $SRC_DIR \
&& cd $SRC_DIR/php-src-$PHP \
&& ./buildconf --force \
&& ./configure --prefix=$PHP_PATH \
--with-mysqli \
--with-pdo-mysql \
--with-jpeg-dir \
--with-png-dir \
--with-iconv-dir \
--with-freetype-dir \
--with-zlib \
--with-libxml-dir \
--with-gd \
--with-openssl \
--with-mhash \
--with-curl \
--without-pear \
--with-fpm-user=nobody \
--with-fpm-group=nobody \
--enable-bcmath \
--enable-soap \
--enable-zip \
--enable-mbstring \
--enable-sockets \
--enable-opcache \
--enable-pcntl \
--enable-simplexml \
--enable-xml \
--enable-fileinfo \
--enable-rpath \
&& make && make install \
&& cp php.ini-development $PHP_PATH/lib/php.ini

# 把PHP加入环境变量
ENV PATH $PATH:$PHP_PATH/bin:$PHP_PATH/sbin/

# 安装swoole扩展
ENV SWOOLE 4.3.4
COPY container/v$SWOOLE.zip $SRC_DIR
RUN unzip $SRC_DIR/swoole-src-$SWOOLE.zip -d $SRC_DIR \
&& cd $SRC_DIR/swoole-src-$SWOOLE/ && phpize && ./configure && make && make install \
&& sed -i '920a extension=swoole.so\n' $PHP_PATH/lib/php.ini

# 安装hredis
ENV HREDIS 0.14.0
COPY container/hiredis-$HREDIS.tar.gz $SRC_DIR
RUN tar -zxf $SRC_DIR/hiredis-$HREDIS.tar.gz -C $SRC_DIR \
&& cd $SRC_DIR/hiredis-$HREDIS/ && make && make install

# 安装composer
COPY container/composer-1.8.5.phar $SRC_DIR
RUN mv $SRC_DIR/composer-1.8.5.phar /usr/local/bin/composer

# 删除下载的安装包
RUN rm -rf $SRC_DIR/*

WORKDIR /var/www

EXPOSE 9000

CMD php
