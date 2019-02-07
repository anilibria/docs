```
apt-get install pure-ftpd
openssl req -new -newkey rsa:2048 -days 9999 -nodes -x509 -subj /C=EU/ST=Unknown/L=Unknown/O=AniLibria/CN=anilibria.tv -keyout /etc/ssl/private/pure-ftpd.pem -out /etc/ssl/private/pure-ftpd.pem
chmod 0600 /etc/ssl/private/pure-ftpd.pem
chmod 0600 /etc/ssl/private/pure-ftpd.pem
echo yes > /etc/pure-ftpd/conf/ChrootEveryone
echo yes > /etc/pure-ftpd/conf/DontResolve
echo 1 > /etc/pure-ftpd/conf/TLS
/etc/init.d/pure-ftpd restart
```
