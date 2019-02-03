Установка.
```
apt-get install transmission-daemon
```

Перед тем как редактировать `/etc/transmission-daemon/settings.json` - выключите `transmission`.
```
/etc/init.d/transmission-daemon stop
```

Поменяем папку.
```
# "download-dir": "/var/www/torrent",
mkdir /var/www/torrent/
chown debian-transmission:debian-transmission /var/www/torrent/
```

Поменяем логин и пароль.
```
"rpc-username": "login",
"rpc-password": "passwd",
```

Доступ к rpc `http://IP:9091`
```
"rpc-whitelist": "127.0.0.1",
"rpc-whitelist": "127.0.0.1, 127.0.0.50",
"rpc-whitelist": "*",
```

<hr/>

Nginx proxy.
```
"rpc-bind-address": "127.0.0.1",
"rpc-port": 9091,
```
```
location ~ ^/transmission {
  proxy_pass http://127.0.0.1:9091;
  proxy_pass_header X-Transmission-Session-Id;
  proxy_set_header Host $host;
  proxy_set_header X-Real-IP $remote_addr;
  proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
}
```

<hr/>

Auto upload torrents.
```bash
mkdir /home/torrent
mkdir /home/torrent/tmp
cat <<EOF >/home/torrent/upload.bash
#!/bin/bash
cd /home/torrent/tmp
wget --no-directories --content-disposition --restrict-file-names=nocontrol -e robots=off -A.torrent -r https://www.anilibria.tv/wget_torrents.php
for f in /home/torrent/tmp/*.torrent; do
   transmission-remote --auth transmission:HuHacOmcass2 -a $f
done
rm /home/torrent/tmp/*.torrent
EOF

chmod 750 /home/torrent/upload.bash

cat <<EOF >/etc/cron.d/torrent
SHELL=/bin/sh
PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin
MAILTO=""
HOME=/

10 15 */7 * *   root   /home/torrent/upload.bash >/dev/null 2>&1
EOF

/etc/init.d/crond restart
```

<hr/>

Auto upload and clean old torrents https://habr.com/post/135874/

```php
#!/usr/bin/php
<?php
// apt-get install php-cli php-pear && pear install File_Bittorrent2
// https://pear.php.net/package/File_Bittorrent2
function checkTorrent($hash){
	global $ctx;
	$str = file_get_contents('http://anilibria.tv:2710/scrape?info_hash='.$hash, false, $ctx);
	if (strpos($str, 'filesde5') !== false) {
		return false;
	}else{
		return true;
	}
}

require_once('File/Bittorrent2/Decode.php');
$torrent = new File_Bittorrent2_Decode;
$login = 'transmission';
$passwd = 'HuHacOmcass2';
$ctx = stream_context_create(['http'=> ['timeout' => 5 ]]); // timeout 5s for file_get_contents
$dir = ['/var/lib/transmission-daemon/.config/transmission-daemon/torrents', '/home/torrent/tmp'];
$files = array_slice(scandir($dir[0]), 2); 
foreach($files as $v){
	if(pathinfo("$dir[0]/$v",PATHINFO_EXTENSION) == 'torrent'){
		$info = $torrent->decodeFile("$dir[0]/$v");
		$info_hash = pack('H*',$info["info_hash"]);
		if(!checkTorrent(urlencode($info_hash))){
			echo "Try remove $v ...  ";
			$id = intval(shell_exec("transmission-remote --auth $login:$passwd -t ".escapeshellarg($info['info_hash'])." --info | grep \"Id:\" | sed -e 's/[^0-9]*//g'"));
			if(is_numeric($id) && $id != 0){
				echo "OK!";
				shell_exec("transmission-remote --auth $login:$passwd --torrent $id --remove-and-delete");
			}
			echo "\n";
		}
	}
}

// clean https://habr.com/post/135874/
$bl = false; // true - use blacklist, false - dont use.
$blacklist = explode("\n", file_get_contents('/var/www/anilibria/root/tracker/torrents/blacklist.txt', false, $ctx));
shell_exec("cd $dir[1] && wget -q --no-directories --content-disposition --restrict-file-names=nocontrol -e robots=off -A.torrent -r https://www.anilibria.tv/tracker/torrents/wget_torrents.php");
$files = array_slice(scandir($dir[1]), 2); 
foreach($files as $v){
	if(pathinfo("$dir[1]/$v",PATHINFO_EXTENSION) == 'torrent'){
		$info = $torrent->decodeFile("$dir[1]/$v");
		if($bl && in_array($info['info_hash'], $blacklist)){
			echo "Blacklist {$info['info_hash']} $v\n";
			continue;
		}
		echo "Upload $v\n";
		shell_exec("transmission-remote --auth $login:$passwd -a ".escapeshellarg("$dir[1]/$v"));
	}
}
shell_exec("rm /home/torrent/tmp/*.torrent");
```

<hr/>

<img src="https://img.poiuty.com/img/01/0f214031262d1d2070ecb5d01b9ce101.png">
