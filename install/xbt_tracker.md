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

<img src="https://blog.poiuty.com/wp-content/uploads/2013/07/xbt_users-day.png">

Munin плагины: <a href="https://github.com/icantbelieveitworks/docs/blob/master/lepus/munin/xbt_users">xbt_users</a>, <a href="https://github.com/icantbelieveitworks/docs/blob/master/lepus/munin/xbt_torrents">xbt_torrents</a>.
