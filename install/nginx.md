```
apt-get install nginx-extras certbot
```

Получаем сертификат. Можно указать дополнительные поддомены `-d one.anilibria.tv`, `-d two.anilibria.tv`

```
certbot certonly --webroot -w /var/www/html -d anilibria.tv -d www.anilibria.tv -m admin@anilibria.tv --agree-tos
```
