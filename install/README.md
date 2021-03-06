Все действия выполняются на Debian 9 minimal 64 bit.<br/>
Конфиг сервера: 2x E5-2620 v4, 64GB DDR4, 2x 480GB SSD.<br/>

```
apt-get update
apt-get upgrade
apt-get install htop bwm-ng mc lsof fail2ban iotop sysstat net-tools curl

cat <<EOF >/etc/rc.local
#!/bin/sh -e
#
# rc.local
#
# This script is executed at the end of each multiuser runlevel.
# Make sure that the script will "exit 0" on success or any other
# value on error.
#
# In order to enable or disable this script just change the execution
# bits.
#
# By default this script does nothing.

exit 0
EOF
chmod +x /etc/rc.local
systemctl daemon-reload
systemctl start rc-local
systemctl status rc-local
```

1. <a href="https://github.com/anilibria/docs/blob/master/install/memcached.md">Memcached</a>
2. <a href="https://github.com/anilibria/docs/blob/master/install/mariadb.md">MariaDB</a>
3. <a href="https://github.com/anilibria/docs/blob/master/install/xbt_tracker.md">XBT Tracker</a>
```
# adduser anilibria
# nano /etc/ssh/sshd_config

# override default of no subsystems
Subsystem	sftp	/usr/lib/openssh/sftp-server

# Disable ssh and sftp for users
DenyUsers anilibria

# systemctl restart sshd
```

4. <a href="https://github.com/anilibria/docs/blob/master/install/php73.md">PHP 7.3</a>
5. <a href="https://github.com/anilibria/docs/blob/master/install/nginx.md">Nginx</a>

```
mkdir /var/www/anilibria
mkdir /var/www/anilibria/logs
mkdir /var/www/anilibria/root
usermod -a -G www-data anilibria
chown -R anilibria:www-data /var/www/anilibria
usermod -m -d /var/www/anilibria anilibria
```

6. <a href="https://github.com/anilibria/docs/blob/master/install/phpmyadmin.md">phpMyAdmin</a>
7. <a href="https://github.com/anilibria/docs/blob/master/install/sphinx.md">Sphinx</a>
8. <a href="https://github.com/anilibria/docs/blob/master/install/munin.md">Munin</a>
9. <a href="https://github.com/anilibria/docs/blob/master/install/fail2ban.md">Fail2Ban</a>
10. <a href="https://github.com/anilibria/docs/blob/master/install/autoupdate.md">AutoUpdate</a>
11. <a href="https://github.com/anilibria/docs/blob/master/install/timezone.md">Timezone</a>
12. KernelCare
13. Onion
14. <a href="https://github.com/anilibria/docs/blob/master/install/sysctl.md">Sysctl</a>
15. <a href="https://github.com/anilibria/docs/blob/master/install/exim.md">Exim</a>
16. <a href="https://github.com/anilibria/docs/blob/master/install/pure-ftpd.md">Pure-FTPd</a>
17. ntpd
