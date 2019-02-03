Установка.
```
apt-get install memcached
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
