```
apt-get update
apt-get install unattended-upgrades cron-apt
dpkg-reconfigure unattended-upgrades
```
Появится окно => выбираем Yes.<br/>

<img src="https://poiuty.com/img/cd/f43270f8c10bddeda23f96b206c40dcd.png">

Запустим автообновление в тестовом режиме.
```
# unattended-upgrade --debug --dry-run
...
Allowed origins are: ['origin=Debian,codename=jessie,label=Debian-Security']
...
```
Логи можно посмотреть в файле /var/log/unattended-upgrades/unattended-upgrades.log
