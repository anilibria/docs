```
apt-get update
apt-get upgrade
apt-get install ca-certificates apt-transport-https
wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add -
echo "deb https://packages.sury.org/php/ stretch main" | tee /etc/apt/sources.list.d/php.list
apt-get update
apt-get install php7.3-fpm php7.3-cli php7.3-curl php7.3-mysql php7.3-mbstring curl
```
