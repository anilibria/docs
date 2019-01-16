```
apt-get install sphinxsearch
mkdir /etc/sphinxsearch/dicts
wget http://sphinxsearch.com/files/dicts/en.pak -O /etc/sphinxsearch/dicts/en.pak
wget http://sphinxsearch.com/files/dicts/ru.pak -O /etc/sphinxsearch/dicts/ru.pak 
```

```
# nano /etc/sphinxsearch/sphinx.conf

common {
    lemmatizer_base = /etc/sphinxsearch/dicts
}


source mysql {
    type = mysql
    sql_host = localhost
    sql_user = user
    sql_pass = secret
    sql_db = base

    # Sphinx return empty result on cyrillic query
    # http://sphinxsearch.com/forum/view.html?id=11176
    sql_query_pre = SET NAMES utf8

    sql_query_range = select min(id), max(id) from `page`
    sql_range_step = 2048

    sql_query = select id, name, ename, genre, voice, season, year from `page` where id >= $start and id <= $end
}

index anilibria {
    source = mysql
    
    # Cats => Cat
    # https://habr.com/post/147745/ 
    morphology = stem_enru, soundex
 
    ondisk_attrs=1
    min_word_len = 3
    min_infix_len = 3
      
    path = /var/lib/sphinxsearch/data/anilibria
}

searchd {
    listen			= localhost:9312
    listen			= localhost:9306:mysql41
    log = /var/log/sphinxsearch/searchd.log
    query_log = /var/log/sphinxsearch/query.log
    pid_file = /var/run/sphinxsearch/searchd.pid
}
```

```
# nano /etc/cron.d/sphinxsearch

* * * * * sphinxsearch [ -x /usr/bin/indexer ] && /usr/bin/indexer --quiet --rotate --config /etc/sphinxsearch/sphinx.conf --all
```

```
/etc/init.d/sphinxsearch start
```

<hr/>

https://github.com/anilibria/anilibria/blob/master/test/sphinx.php
