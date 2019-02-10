Установка.
```
apt-get install munin munin-node spawn-fcgi libcgi-fast-perl curl
apt-get install libfile-readbackwards-perl liblwp-useragent-determined-perl libwww-perl
```

Скачиваем плагины.
```
cd /usr/share/munin/plugins

# mysql
wget https://raw.githubusercontent.com/munin-monitoring/contrib/master/plugins/mysql/mysql_size_ondisk
wget https://raw.githubusercontent.com/munin-monitoring/contrib/master/plugins/mysql/mysql_size_all
wget https://raw.githubusercontent.com/munin-monitoring/contrib/master/plugins/mysql/mysql_connections_per_user
wget https://raw.githubusercontent.com/munin-monitoring/contrib/master/plugins/mysql/mysql_connections
wget https://raw.githubusercontent.com/munin-monitoring/contrib/master/plugins/mysql/mysql-table-size
wget https://raw.githubusercontent.com/munin-monitoring/contrib/master/plugins/mysql/mysql-schema-size
wget https://raw.githubusercontent.com/munin-monitoring/contrib/master/plugins/mysql/mysql_qcache_mem
wget https://raw.githubusercontent.com/munin-monitoring/contrib/master/plugins/mysql/mysql_qcache

# nginx
wget https://raw.githubusercontent.com/munin-monitoring/contrib/master/plugins/nginx/nginx_memory

# xbt_tracker
wget https://raw.githubusercontent.com/anilibria/docs/master/install/munin/xbt_users
wget https://raw.githubusercontent.com/anilibria/docs/master/install/munin/xbt_torrents
```

Выставляем права.
```
chmod -R 755 /usr/share/munin/plugins/*
```

Подключаем.
```
# mysql
ln -s /usr/share/munin/plugins/mysql_bytes /etc/munin/plugins/
ln -s /usr/share/munin/plugins/mysql_connections /etc/munin/plugins/
ln -s /usr/share/munin/plugins/mysql_connections_per_user /etc/munin/plugins/
ln -s /usr/share/munin/plugins/mysql_size_all /etc/munin/plugins/
ln -s /usr/share/munin/plugins/mysql_innodb /etc/munin/plugins/
ln -s /usr/share/munin/plugins/mysql_size_ondisk /etc/munin/plugins/
ln -s /usr/share/munin/plugins/mysql_slowqueries /etc/munin/plugins/
ln -s /usr/share/munin/plugins/mysql_threads /etc/munin/plugins/
ln -s /usr/share/munin/plugins/mysql_queries /etc/munin/plugins/
ln -s /usr/share/munin/plugins/mysql_qcache /etc/munin/plugins/
ln -s /usr/share/munin/plugins/mysql_qcache_mem /etc/munin/plugins/

# nginx
ln -s /usr/share/munin/plugins/nginx_request /etc/munin/plugins/
ln -s /usr/share/munin/plugins/nginx_status /etc/munin/plugins/
ln -s /usr/share/munin/plugins/nginx_memory /etc/munin/plugins/

# xbt_tracker
ln -s /usr/share/munin/plugins/xbt_users /etc/munin/plugins/
ln -s /usr/share/munin/plugins/xbt_torrents /etc/munin/plugins/
```

```
# nano /etc/munin/plugin-conf.d/munin-node
[nginx*]
env.url http://www.anilibria.tv/nginx_status
env.ua nginx-status-verifier/0.1
```

Перезапускаем munin, nginx. Включаем spawn-fcgi.
```
/etc/init.d/nginx restart
/etc/init.d/munin-node restart
spawn-fcgi -s /var/run/munin/fcgi-graph.sock -U www-data -u www-data -g www-data /usr/lib/munin/cgi/munin-cgi-graph

# nano /etc/rc.local
spawn-fcgi -s /var/run/munin/fcgi-graph.sock -U www-data -u www-data -g www-data /usr/lib/munin/cgi/munin-cgi-graph
```

Проверяем статистику https://www.anilibria.tv/munin/

<img src="https://img.poiuty.com/img/bd/968a2fb8e630188af3ec2392aa1a3ebd.png">
