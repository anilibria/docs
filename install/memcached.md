Установка.
```
apt-get install memcached php-memcache
```


Редактируем `/etc/memcached.conf`
```
-d
logfile /var/log/memcached.log
-m 256
-u nobody
-s /tmp/memcached.socket
-a 0777
```

Перезагружаем.
```
/etc/init.d/memcached restart
```

Сохраняем <a href="http://php.net/manual/ru/memcached.sessions.php">php сессии в memcached</a>. Редактируем `/etc/php5/fpm/pool.d/anilibria.conf`
```
php_admin_value[session.save_handler] = memcache
php_admin_value[session.save_path] = "unix:///tmp/memcached.socket:0"
```
