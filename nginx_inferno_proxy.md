```
server {
	listen 80 default_server;
	listen 443 ssl http2 default_server;
	ssl_certificate /etc/nginx/ssl/default.crt;
	ssl_certificate_key /etc/nginx/ssl/default.key;
	return 403;
}

proxy_cache_path /var/www/cache levels=1:2 keys_zone=cache:10m inactive=1d max_size=2000m;

server {
	listen 80;
	listen 443 ssl http2;
	server_name  anilibria.tv www.anilibria.tv api.anilibria.tv;
	ssl_certificate /etc/letsencrypt/live/anilibria.tv/fullchain.pem;
	ssl_certificate_key /etc/letsencrypt/live/anilibria.tv/privkey.pem;
	keepalive_timeout 30;
	access_log off;
	root /var/www/html;
	location ^~ /.well-known/ {
		default_type "text/plain";
	}
	location ~* ^.+\.(jpg|jpeg|gif|png|svg|js|css|ico|bmp|woff)$ {
		proxy_pass https://127.0.0.1;
		proxy_http_version 1.1;
		proxy_set_header Connection "";
		proxy_set_header Host $host;
		proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
			
		proxy_cache            cache;
		proxy_cache_valid      404 302  1m;
		proxy_cache_valid      200      30m;
		proxy_ignore_headers Set-Cookie Expires Cache-Control;
		proxy_cache_use_stale  error timeout invalid_header updating http_500 http_502 http_503 http_504;
		
		proxy_cache_lock on;
		proxy_cache_min_uses 3; # reduce disk load
		proxy_cache_revalidate on;
		
		add_header X-Cache-Status $upstream_cache_status; # show cache status
	}
	location / {
		proxy_pass https://127.0.0.1;
		proxy_http_version 1.1;
		proxy_set_header Connection "";
		proxy_set_header Host $host;
		proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
	}
}
```
