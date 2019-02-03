```
apt-get install nginx-extras certbot
mkdir /etc/nginx/ssl/
openssl dhparam -out /etc/nginx/ssl/dhparam.pem 2048
chown www-data:www-data /etc/nginx/ssl/dhparam.pem
chmod 400 /etc/nginx/ssl/dhparam.pem
```

Получаем сертификат. Можно указать дополнительные поддомены `-d one.anilibria.tv`, `-d two.anilibria.tv`

```
certbot certonly --webroot -w /var/www/html -d anilibria.tv -d www.anilibria.tv -m admin@anilibria.tv --agree-tos
```

Обновляем конфиг.
```
wget -O /etc/nginx/nginx.conf https://raw.githubusercontent.com/anilibria/docs/master/install/conf/nginx.conf
```
