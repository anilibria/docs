```
apt-get install exim4
dpkg-reconfigure exim4-config

> internet site; mail is send and received directly using SMTP
> anilibria.tv
> 127.0.0.1
> anilibria.tv
> empty
> empty
> No
> mbox format in /var/mail/
> No
> empty
```

Выключим ipv6. Открываем `/etc/exim4/exim4.conf.template` добавляем сразу перед `begin acl` добавляем.
```
disable_ipv6 = true
```


```
/etc/init.d/exim4 restart
```
