# PhoneBook <small>(test-task)</small>

## Описание
Тестовое задание. Используются рекомендации PSR-2, PSR-3, PSR-4, PSR-7, PSR-11, PSR-15.

## Расширения
Расширение | Версия     | Документация
---------- | ---------- | --------------
PHP        | v7.2+      | http://php.net/docs.php
MySql      | v5.7+      | https://dev.mysql.com/doc
Node.js    | v12.0+     | https://nodejs.org/en/docs

## Установка
1. Создаем файл **.env** `cp .env.example .env`, указываем в нем значения для подключения к БД
1. Даем права на папку **storage** `chmod -R 777 storage`
1. Устанавливаем зависимости composer `composer install`
1. Запсукаем миграции `php console migrations:migrate`
1. Устанавливаем зависимости npm `yarn install`
1. Запускаем сборщик `yarn prod`

## Nginx config (пример)
```$xslt
server {
    listen 80;
    listen [::]:80;

    server_name phone-book.ru;
    root /var/www/phone-book/public;
    index index.php;

    location / {
         try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_pass php-upstream;
        fastcgi_index index.php;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 600;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```
