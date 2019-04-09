FROM centos:7.6.1810

MAINTAINER ruoge3s@gmail.com

ENV PACKAGE_HOME /home/soft
ENV PHP_DIR /usr/local/php72

RUN rpm --import /etc/pki/rpm-gpg/RPM-GPG-KEY-CentOS-7
RUN yum update -y
# 安装下载工具及编译所需的工具
RUN yum install -y gcc autoconf glibc-headers gcc-c++ wget
# 安装PHP所需的库文件
RUN yum install -y libxml2 libxml2-devel openssl openssl-devel libcurl libcurl-devel libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel pcre pcre-devel zlib zlib-devel

# 安装PHP所需的re2c
RUN wget -P ${PACKAGE_HOME} -q https://datapacket.dl.sourceforge.net/project/re2c/0.13.6/re2c-0.13.6.tar.gz \
&& tar -zxf ${PACKAGE_HOME}/re2c-0.13.6.tar.gz -C ${PACKAGE_HOME} \
&& cd ${PACKAGE_HOME}/re2c-0.13.6 && ./configure && make && make install

RUN rm -rf ${PACKAGE_HOME}/re2c-0.13.6.tar.gz && rm -rf ${PACKAGE_HOME}/re2c-0.13.6

# 安装PHP7所需的bison
RUN wget -P ${PACKAGE_HOME} -q http://ftp.gnu.org/gnu/bison/bison-2.4.1.tar.gz \
&& tar -zxf ${PACKAGE_HOME}/bison-2.4.1.tar.gz -C ${PACKAGE_HOME} \
&& cd ${PACKAGE_HOME}/bison-2.4.1 && ./configure && make && make install

RUN rm -rf ${PACKAGE_HOME}/bison-2.4.1.tar.gz && rm -rf ${PACKAGE_HOME}/bison-2.4.1

# 安装PHP
RUN wget -P ${PACKAGE_HOME} -q http://jp2.php.net/distributions/php-7.2.14.tar.xz \
&& tar -xJf ${PACKAGE_HOME}/php-7.2.14.tar.xz -C ${PACKAGE_HOME} \
&& cd ${PACKAGE_HOME}/php-7.2.14 && ./configure --prefix=${PHP_DIR} \
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
--with-pear \
--enable-bcmath \
--enable-soap \
--enable-zip \
--enable-mbstring \
--enable-sockets \
--enable-opcache \
--enable-pcntl \
--enable-simplexml \
--enable-xml \
--disable-fileinfo \
--disable-rpath \
&& make && make install \
&& cp php.ini-development ${PHP_DIR}/lib/php.ini

# 保留php压缩包，以备安装扩展使用
RUN rm -rf ${PACKAGE_HOME}/php-7.2.14

# 把PHP加入环境变量
ENV PATH $PATH:${PHP_DIR}/bin:

# 安装swoole扩展
RUN wget -P ${PACKAGE_HOME} -q https://github.com/swoole/swoole-src/archive/v4.2.12.tar.gz \
&& tar -zxf ${PACKAGE_HOME}/v4.2.12.tar.gz -C ${PACKAGE_HOME} \
&& cd ${PACKAGE_HOME}/swoole-src-4.2.12/ && phpize && ./configure && make && make install \
&& sed -i '920a extension=swoole.so\n' ${PHP_DIR}/lib/php.ini

# 安装hredis
RUN wget -P ${PACKAGE_HOME} -q https://github.com/redis/hiredis/archive/v0.14.0.tar.gz \
&& tar -zxf ${PACKAGE_HOME}/v0.14.0.tar.gz -C ${PACKAGE_HOME} \
&& cd ${PACKAGE_HOME}/hiredis-0.14.0/ && make && make install

# 安装composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
&& php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
&& php -r "unlink('composer-setup.php');"

EXPOSE 9000

