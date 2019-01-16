Не проверяем целевую аудиторию, поисковики (google, yandex) и если пользователь уже прошел проверку.
```
Россия
Украина
Беларусь
Латвия
Литва
Туркменистан
Эстония
Киргизия
Казахстан
Молдавия
Узбекистан
Азербайджан
Армения
Грузия
```
```php
function xSpiderBot($name){
	$arr = ['Google' => '/\.googlebot\.com$/i', 'Yandex' => '/\.spider\.yandex\.com$/i'];
	if(strpos($_SERVER['HTTP_USER_AGENT'], $name) !== false){
		return preg_match($arr["$name"], gethostbyaddr($_SERVER['REMOTE_ADDR']));
	}
	return false;
}
```
```php
$xflag = false;
if(!empty($_COOKIE['ani_test'])){
	$xkey = 'secret';
	$xstring = $_COOKIE['ani_test'];	
	$xhash = substr($xstring, 0, 64);
	$xrand = substr($xstring, 64+strlen($_SERVER['REMOTE_ADDR']));
	$xtest = hash('sha256', $_SERVER['REMOTE_ADDR'].$xrand.$xkey);
	if($xhash == $xtest){ 
		$xflag = true;	
	}
}
```

https://developers.google.com/recaptcha/docs/v3<br/>
```
Проверка выполняется автоматически.
Получаем score от 0.0 до 1.0
Если score меньше 0.5 выводим coinhive
```

https://coinhive.com/documentation/captcha
```
Проверка выполняется автоматически.
Необходимо решить несколько хэшей (js xmr miner).
```


Если пользователь прошел проверку - создадим cookie.
```php
$xkey = 'secret';
$xrand = random_int(0, 1000000);
$xhash = hash('sha256', $_SERVER['REMOTE_ADDR'].$xrand.$xkey);
setcookie("ani_test", $xhash.$_SERVER['REMOTE_ADDR'].$xrand, time() + 86400, '/');

// sign[hash(IP.RAND)] IP RAND
// d6f8e5c40add2a6883b4885a0ff391d29a85c630a23bc497b1269250d5b49a66 127.0.0.1 110654
// d6f8e5c40add2a6883b4885a0ff391d29a85c630a23bc497b1269250d5b49a66127.0.0.1110654
```

Demo https://www.anilibria.tv/demo/filter_demo.mp4
