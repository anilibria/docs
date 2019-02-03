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

DKIM + SPF запись + яндекс почта.

```
apt-get install opendkim-tools
mkdir /etc/exim4/dkim
opendkim-genkey -D /etc/exim4/dkim/ -d anilibria.tv -s mail
cd /etc/exim4/dkim/
mv mail.private example.com.key
chown -R Debian-exim:Debian-exim /etc/exim4/dkim/
chmod 640 /etc/exim4/dkim/*
```

В конфиг `/etc/exim4/exim4.conf.template` или если он разделен,<br/>
то `/etc/exim4/conf.d/transport/30_exim4-config_remote_smtp` добавляем

```
DKIM_DOMAIN = ${lc:${domain:$h_from:}}
DKIM_KEY_FILE = /etc/exim4/dkim/DKIM_DOMAIN.key
DKIM_PRIVATE_KEY = ${if exists{DKIM_KEY_FILE}{DKIM_KEY_FILE}{0}}
DKIM_SELECTOR = mail
```

```
TXT    mail._domainkey.anilibria.tv  /etc/exim4/dkim/mail.txt
TXT    anilibria.tv                  v=spf1 ip4:37.1.217.18 include:_spf.yandex.net ~all
MX     anilibria.tv                  mx.yandex.net  10
CNAME  mail.anilibria.tv             domain.mail.yandex.net
```

```
/etc/init.d/exim4 restart
```
