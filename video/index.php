<?php
$cache = new Memcached();;
$cache->addServer('/tmp/memcached.socket', 0) or die('memcache not work');
$bl = $cache->get('anilibria_bl');
$id = false;
$path = '/var/www/video/ftp/videos/mp4/';

$hosts = [
    'de1' => '10',
    'de2' => '10',
    'de3' => '10',
    'de4' => '10',
    'de5' => '10',
    'de6' => '10',
    'de7' => '10',
    'de8' => '10',
];

function anilibria_getHost(){
	global $hosts; $host = [];
	foreach($hosts as $key => $val){
		$host = array_merge($host, array_fill(0, $val, $key));
	}
	shuffle($host);
	if(count($host) == 1){
		return $host[0].".anilibria.tv";
	}
	return $host[random_int(0, count($host) - 1)].".anilibria.tv";
}

function testCDN($arr){	
	global $cache;
	foreach($arr as $host => $val){
		if($host == 'x') continue;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://$host.anilibria.tv/videos/dont_delete_this_fucking_file.ts");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$result = curl_exec($ch);
		curl_close($ch);
		$bl = $cache->get('anilibria_bl');
		if(!empty($bl)){
			$bl = json_decode($bl, TRUE);
			foreach($bl as $h => $v){ // clean if host not exist
				if(!array_key_exists($h, $arr)){
					unset($bl[$h]);
				}
			}
		}else{
			$bl = [];
		}
		if($result && strpos($result, '200 OK') !== false){
			unset($bl[$host]);
		}else{
			$bl[$host] = $host;
		}
		$cache->set('anilibria_bl', json_encode($bl), 0);
	}
	return $bl = $cache->get('anilibria_bl');
}

if(isset($_GET['testCDN'])){
	die(testCDN($hosts));
}

if(!empty($bl)){
	$bl = json_decode($bl, true);
	foreach($hosts as $h => $v){
		if(in_array($h, $bl)){
			unset($hosts[$h]);
		}
	}
	if(empty($hosts)){ // if all cdn down
		die('all hosts down');
	}
}

if(isset($_GET['id'])){
	$id = intval($_GET['id']);	
}

if(!$id){
	die('403');
}

if(!file_exists($path.$id)){
    die('404');
}

$i = 0;
$links = [];
$episodeNum = 0;
$host = anilibria_getHost();
$files = glob("/var/www/video/ftp/videos/ts/{$id}/*");
foreach($files as $file){
	if(is_dir($file)){
		if(strpos($file, '-sd') !== false){
			continue;
		}
		$file = str_replace('/var/www/video/ftp/', '', $file);
		preg_match('/\/\d+\/0*(\d+)/', $file, $matches);
		$i++;
		$episodeNum = intval($matches[1]);
		$links[$episodeNum] = [
			'file' => str_replace(['ts', 'videos/'], ['mp4', ''], $file),
			'hd' => '//'.$host.'/'.$file.'/playlist.m3u8',
			'sd' => '//'.$host.'/'.$file.'-sd/playlist.m3u8',
			'new' => "[720p]//$host/$file/playlist.m3u8,[480p]//$host/$file-sd/playlist.m3u8",
			'new2' => "[720p]//{host}/$file/playlist.m3u8,[480p]//{host}/$file-sd/playlist.m3u8",
		];
	}
}
$links['updated'] = true;
if(isset($_GET['v2'])){
	$links['online'] = $hosts;
}
echo json_encode(array_reverse($links, true));
