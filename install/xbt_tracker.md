Устанавливаем пакеты.
```
apt-get install cmake default-libmysqlclient-dev libmariadb-dev-compat g++ git libboost-dev make zlib1g-dev
```

Скачиваем, компилируем.
```
git clone https://github.com/OlafvdSpek/xbt.git
cd xbt/Tracker
cmake .
make
cp xbt_tracker.conf.default xbt_tracker.conf
```

Создаем пользователя. Перемещаем файлы.
```
adduser --disabled-login xbt
mkdir /home/xbt/bin
cp xbt_tracker /home/xbt/bin
cp xbt_tracker.conf /home/xbt/bin
chown -R xbt:xbt /home/xbt/bin
chmod 700 /home/xbt/bin/xbt_tracker.conf
```

Редактируем конфиг. <a href="https://github.com/OlafvdSpek/xbt/blob/master/Tracker/xbt_tracker.sql">SQL таблицы</a> уже есть в базе.
```
# nano /home/xbt/bin/xbt_tracker.conf
mysql_host = localhost
mysql_user = xbt
mysql_password = secret
mysql_database = anilibria
```

Добавляем в автозагрузку.
```
# nano /root/xbt.bash
#!/bin/bash -x
sleep 60
su - xbt -c "cd /home/xbt/bin && ./xbt_tracker > /dev/null 2>/dev/null &"

# chmod 755 /root/xbt.bash

# nano /etc/rc.local
/root/xbt.bash &
```

Запускаем.
```
su - xbt -c "cd /home/xbt/bin && ./xbt_tracker > /dev/null 2>/dev/null &"
```

Проверяем http://anilibria.tv:2710/st
```
peers	7242
seeders	6159	85 %
leechers	1083	14 %
torrents	708
accepted tcp	8909520	12 /s
slow tcp	7987727	10 /s
rejected tcp	0
accept errors	0
received udp	232422	0 /s
sent udp	232422	0 /s
announced	5099797	57 %
announced http	5003668	98 %
announced udp	96129	1 %
scraped full	0
scraped multi	265767
scraped	646429	7 %
scraped http	635775	98 %
scraped udp	10654	1 %
up time	1.2 weeks
anonymous announce	1
anonymous scrape	1
auto register	0
full scrape	0
read config time	167 / 180
clean up time	18 / 60
read db files time	6 / 10
read db users time	5 / 10
write db files time	1 / 10
write db users time	4 / 10
```

<hr/>

Оптимальное значение `announce_interval`? Меньше интервал, больше запросов на сервер.<br/>
Если клиент не отправил запрос, сервер удаляет его из списка. Установим `announce_interval 60`.<br/>
Количество пиров падает до `~30`. Думаю, хорошее значение `900~1800`.<br/>

Munin плагины: <a href="https://github.com/icantbelieveitworks/docs/blob/master/lepus/munin/xbt_users">xbt_users</a>, <a href="https://github.com/icantbelieveitworks/docs/blob/master/lepus/munin/xbt_torrents">xbt_torrents</a>.

<hr/>

После обновления MariaDB (5.5 => 10.2) трекер перестал обновлять seeders/leechers.<br/>
Посмотрел <a href="https://github.com/OlafvdSpek/xbt/blob/e00c8416ffb90d23374b7e1909f6d15d5f685e62/Tracker/server.cpp#L296-L349">код</a>, проверил, что xbt подключается к базе и выполняет запросы.

Включил логирование всех запросов.
```
# nano /etc/my/my.cnf
general_log = on
general_log_file= /var/log/mysql/full.log
```

Выполняю запрос, получаю ошибку.
```
insert into xbt_files (leechers, seeders, completed, fid) values (54,108,1692,3299),(39,53,1375,3297),(1,51,301,3295),(6,35,618,3287),(18,26,3376,3271),(4,39,3687,3241),(12,64,6650,3217),(12,42,4857,3212),(19,71,7624,3207),(8,78,5786,3198),(7,32,3443,3175),(8,40,5437,2777),(7,31,826,3292),(4,8,1758,2395),(9,38,15151,1999),(9,19,3673,759),(1,7,5813,2013),(1,12,3170,2417),(0,3,1485,1331),(3,38,2659,3195),(3,8,1600,1046),(1,10,3939,987),(6,36,6547,2403),(2,10,9300,1691),(3,17,641,3236),(4,15,7386,974),(3,18,1530,3250),(1,16,245,3242),(0,49,1603,3130),(2,13,1335,835),(17,47,3594,3238),(3,7,4232,1356),(4,10,2936,1780),(3,39,1496,3243),(0,69,1000,3272),(2,13,4959,1714),(1,72,950,3276),(1,14,7164,983),(12,34,1691,3237),(41,84,1422,3296),(24,40,5354,3279) on duplicate key update  leechers = values(leechers),  seeders = values(seeders),  completed = values(completed),  mtime = unix_timestamp()
 
Field 'info_hash' doesn't have a default value
```

Проверяю sql mode.
```
MariaDB [(none)]> SELECT @@sql_mode;
+-------------------------------------------------------------------------------------------+
| @@sql_mode                                                                                |
+-------------------------------------------------------------------------------------------+
| STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION |
+-------------------------------------------------------------------------------------------+
```

Выключаю STRICT_TRANS_TABLES.
```
# nano /etc/my/my.cnf
[mysqld]
...
sql_mode="ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
 
# /etc/init.d/mysql restart
```
