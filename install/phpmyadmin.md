```
apt-get --no-install-recommends install phpmyadmin
```

Доступен по ссылке https://www.anilibria.tv/phpmyadmin/

<hr/>

No activity within 1440 seconds; please log in again error.

```
# nano /etc/phpmyadmin/config.inc.php
$cfg['LoginCookieValidity'] = 7200;
```

<hr/>

Если phpmyadmin <a href="https://packages.debian.org/stretch/phpmyadmin">4.6.6</a> и php > 7.1 
```
Warning in ./libraries/plugin_interface.lib.php#551
 count(): Parameter must be an array or an object that implements Countable
```

```php
if ($options != null && count($options) > 0) {
```

<a href="https://medium.com/@chaloemphonthipkasorn/%E0%B9%81%E0%B8%81%E0%B9%89-bug-phpmyadmin-php7-2-ubuntu-16-04-92b287090b01">Fix</a>
 ```php
if ($options != null && count((array)$options) > 0) {
 ```
