server {
	listen 80 default_server;
	listen [::]:80 default_server;
	server_name _;
	return 301 https://$host$request_uri;
}

server {
	listen 443 ssl default_server;
	listen [::]:443 ssl default_server;

	root /home/pi/nursery/public;

	index index.php index.html index.htm index.nginx-debian.html;

	server_name nursery.leafdebrief.com;

	location / {
		try_files $uri /index.php$is_args$args;
	}

	location /home {
		alias /home/pi/nursery/public/home;
		try_files $uri $uri/ /home/index.html;
	}
	
	location ~ \.php$ {
		include snippets/fastcgi-php.conf;
		fastcgi_pass unix:/run/php/php7.3-fpm.sock;
	}

	location ~ /\.ht {
		deny all;
	}

	location ~/live {
		proxy_set_header X-Real-IP  $remote_addr;
		proxy_set_header X-Forwarded-For $remote_addr;
		proxy_set_header Host $host;
		proxy_pass http://localhost:8081;
	}
}
