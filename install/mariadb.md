Список реп доступен на сайте https://downloads.mariadb.org/mariadb/repositories/

```
apt-get install software-properties-common dirmngr
apt-key adv --recv-keys --keyserver keyserver.ubuntu.com 0xF1656F24C74CD1D8
add-apt-repository 'deb [arch=amd64,i386,ppc64el] http://mirrors.n-ix.net/mariadb/repo/10.3/debian stretch main'

apt-get update
apt-get install mariadb-server
```

Редактируем конфиг.
```
# nano /etc/mysql/my.cnf

## MySQL config
[client]
port = 3306
socket = /var/run/mysqld/mysqld.sock
default-character-set = utf8

[mysqld_safe]
socket = /var/run/mysqld/mysqld.sock
nice = 0
malloc-lib = /usr/lib/x86_64-linux-gnu/libjemalloc.so.1

[mysqld]
user = mysql
pid-file = /var/run/mysqld/mysqld.pid
socket = /var/run/mysqld/mysqld.sock
port = 3306
basedir = /usr
datadir = /var/lib/mysql
tmpdir = /tmp
skip-networking
skip-name-resolve

# XBT Tracker not displaying seeders/leechers
# https://github.com/anilibria/docs/blob/master/install/xbt_tracker.md
sql_mode="ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"

# Other
default-storage-engine = INNODB
character-set-server = utf8
max_connections = 500
wait_timeout = 7200
max_allowed_packet = 16M
skip-external-locking
#memlock
open_files_limit = 16000

# MyISAM settings
key_buffer_size = 256M

# InnoDB settings
innodb_buffer_pool_size = 16G
innodb_buffer_pool_instances = 16
innodb_log_file_size = 2G
innodb_flush_log_at_trx_commit = 2
innodb_log_buffer_size = 16M
innodb_log_files_in_group = 2
innodb_flush_method = O_DIRECT
innodb_thread_concurrency = 16

# Buffer settings
join_buffer_size = 2M

# TMP & memory settings
tmp_table_size = 256M
max_heap_table_size = 256M

# Cache settings
# Try off https://community.centminmod.com/threads/mysqltuner.6779/
query_cache_type = 0 # for OFF
query_cache_size = 0 # to ensure QC is NOT USED

# Slowlog settings
slow_query_log = 1
long_query_time = 5
slow_query_log_file = /var/log/mysql/mariadb-slow.log

#Set General Log
#general_log = on
#general_log_file = /var/log/mysql/full.log

[mysqldump]
# Do not buffer the whole result set in memory before writing it to
# file. Required for dumping very large tables
quick

max_allowed_packet = 32M
default-character-set = utf8

[mysql]
no-auto-rehash
default-character-set = utf8

[isamchk]
key_buffer_size = 8M
sort_buffer_size = 8M
read_buffer = 8M
write_buffer = 8M
default-character-set = utf8

#
# * IMPORTANT: Additional settings that can override those from this file!
#   The files must end with '.cnf', otherwise they'll be ignored.
#
!includedir /etc/mysql/conf.d/
```
