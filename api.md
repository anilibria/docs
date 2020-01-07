### Примечание.
* Тип поля указывается в [ ]
* **?** – Обозначает Nullable или необязательное поле

Оглавление:
* ["Отправка запросов"](#user-content-отправка-запросов)
* ["Авторизация"](#user-content-авторизация)
* ["Релизы"](#user-content-релизы)
* ["Лента"](#user-content-лента)
* ["Расписание"](#user-content-расписание)
* ["Случайный релиз"](#user-content-случайный-релиз)
* ["Избранное"](#user-content-избранное)
* ["Жанры"](#user-content-жанры)
* ["Года"](#user-content-года)
* ["Каталог"](#user-content-каталог)
* ["Поиск по названию"](#user-content-поиск-по-названию)
* ["YouTube"](#user-content-youtube)
* ["Пользователь"](#user-content-пользователь)
* ["Комментарии ВКонтакте"](#user-content-комментарии-вконтакте)
* ["Модели данных"](#user-content-модели-данных)
	* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
	* ["Модель пагинации"](#user-content-модель-пагинации)
	* ["Модель релиза"](#user-content-модель-релиза)
	* ["Модель серии"](#user-content-модель-серии)
	* ["Модель торрента"](#user-content-модель-торрента)
	* ["Модель блокировки"](#user-content-модель-релиза)
	* ["Модель избранного в релизе"](#user-content-модель-избранного-в-релизе)
	* ["Модель ленты"](#user-content-модель-ленты)
	* ["Модель расписания"](#user-content-модель-расписания)
	* ["Модель случайного релиза"](#user-content-модель-случайного-релиза)
	* ["Модель жанра"](#user-content-модель-жанра)
	* ["Модель года"](#user-content-модель-года)
	* ["Модель пользователя"](#user-content-пользователя)
	* ["Модель YouTube"](#user-content-модель-youtube)
	* ["Модель комментариев ВКонтакте"](#user-content-модель-комментариев-вконтакте)


### Отправка запросов
Для отправки запросов нужно использовать не JSON файл, а форму. В противном случае, Вы получите ["Базовую модель ответа"](#user-content-базовая-модель-ответа).

Пример отправки запроса ["Случайный релиз"](#user-content-случайный-релиз) на языке программирования Go
```
resp, err := http.PostForm("https://www.anilibria.tv/public/api/index.php",
    url.Values{"query": {"random_release"}})
```
Пример отправки запроса ["Релизы"](#user-content-релизы) на языке программирования Go
```
resp, err := http.PostForm("https://www.anilibria.tv/public/api/index.php",
    url.Values{"query": {"release"}, "code": {"rdg-red-data-girl"}})
```


### Авторизация
Примечания:
* Авторизация может пропасть в любой момент, т.к. время жизни сессии ограничено

	
##### Залогиниться
URL
```
<host>/public/login.php
```

Все параметры запроса
```
mail, passwd
```
* mail [string] – Логин или электронная почта от аккаунта
* passwd [string] – Пароль от аккаунта

<br>Пример запроса
```
{"mail":"testuser", "passwd":"testpass"}
{"mail":"testuser@test.user", "passwd":"testpass"}
Ответ: Тело не важно
В хедере будет кука PHPSESSID - её нужно сохранить и использовать в следующих запросах
```

##### Разлогиниться
URL
```
<host>/public/logout.php
```

Все параметры запроса
```
Нет
```

<br>Пример запроса
```
{}
Ответ: Тело не важно
В хедере кука PHPSESSID должна быть со значением "deleted" – удаляем её из клиента
```

### Релизы
URL
```
<host>/public/api/index.php
```

Все параметры запроса
```
query, id, code, filter, rm, page, perPage
```
* query [string] – Что именно нужно вывести. 
	* "release" - Вывод одного релизы, доступно [id, code, filter, rm]
	* "list" – Вывод списка всех релизов, есть пагинация, доступно [filter, rm, page, perPage]
	* "info" - Вывод необходимы релизов, доступно [id, filter, rm]
* id [int/string]**?** – "id" релиза, обязателен для [info, release*]
* code [string]**?** – "code" релиза, обазателен для [release*]
* filter [string]**?** – Фильтрация выводимых полей, нужно передать строку с полями через сепаратор (пример: "id, names, description").
* rm [string]**?** – Если передать это поле, фильтр будет ИСКЛЮЧАТЬ поля
* page [int/string]**?** – Номер страницы для списка релизов
* perPage [int/string]**?** – Кол-во релизов на одну страницу

Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель пагинации"](#user-content-модель-пагинации)
* ["Модель релиза"](#user-content-модель-релиза)
* ["Модель серии"](#user-content-модель-серии)
* ["Модель торрента"](#user-content-модель-торрента)
* ["Модель блокировки"](#user-content-модель-релиза)
* ["Модель избранного в релизе"](#user-content-модель-избранного-в-релизе)

<br>Примеры запросов:

Релиз
```
{"query":"release"} – будет ошибка 400, т.к. нет id и code
{"query":"release","id":"120211111"} – будет ошибка, 404
{"query":"release","id":"1202"} – релиз по id
{"query":"release","code":"sakurako-san-no-ashimoto-ni-wa-shitai-ga-umatteiru"} – релиз по code
Ответ: 
{
    "status": true|false,
    "data": {МОДЕЛЬ РЕЛИЗА}, 
    "error": null|{}
}
```

<br>Нужные релизы
```
{"query":"info","id":"1202, 473"} - выведет два релиза, если найдёт
{"query":"info","id":"1202, 473","filter":"description,torrent"} - будут только поля descriptin и torrent
{"query":"info","id":"1202, 473","filter":"description,torrent","rm":""} - исключит поля descriptin и torrent
Ответ: 
{
    "status": true,
    "data": [МОДЕЛИ РЕЛИЗА], 
    "error": null
}
```

<br>Список релизов
```
{"query":"list","page":"1","perPage":"3"} - выведет 1 страницу с 3 релизами
Ответ: 
{
    "status": true,
    "data": {
        "items": [МОДЕЛИ РЕЛИЗА], 
        "pagination": {МОДЕЛЬ ПАГИНАЦИИ} 
    }, 
    "error": null
}
```

### Лента
URL
```
<host>/public/api/index.php
```
Параметры запроса
```
query, id, action, filter, rm, page, perPage
```
* query [string] = "feed"
* filter [string]**?** – Фильтрация выводимых полей, нужно передать строку с полями через сепаратор (пример: "id, names, description").
* rm [string]**?** – Если передать это поле, фильтр будет ИСКЛЮЧАТЬ поля
* page [int/string]**?** – Номер страницы для списка релизов
* perPage [int/string]**?** – Кол-во релизов на одну страницу

Примечания:
* filter действует только на поля в модели релиза

Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель ленты"](#user-content-модель-ленты)
* ["Модель релиза"](#user-content-модель-релиза)
* ["Модель YouTube"](#user-content-модель-youtube)

<br>Пример запроса:
```
{{"query":"feed","page":"1","perPage":"3"} - выведет 1 страницу с 3 элементами ленты
Ответ: 
{
    "status": true,
    "data": [МОДЕЛИ ЛЕНТЫ], 
    "error": null
}
```

### Расписание
URL
```
<host>/public/api/index.php
```
Параметры запроса
```
query, id, action, filter, rm, page, perPage
```
* query [string] = "schedule"
* filter [string]**?** – Фильтрация выводимых полей, нужно передать строку с полями через сепаратор (пример: "id, names, description").
* rm [string]**?** – Если передать это поле, фильтр будет ИСКЛЮЧАТЬ поля


Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель расписания"](#user-content-модель-расписания)
* ["Модель релиза"](#user-content-модель-релиза)

<br>Пример запроса:
```
{"query":"schedule"}
Ответ: 
{
    "status": true,
    "data": [МОДЕЛИ РАСПИСАНИЯ], 
    "error": null
}
```

### Случайный релиз
URL
```
<host>/public/api/index.php
```
Параметры запроса
```
query, id, action, filter, rm, page, perPage
```
* query [string] = "random_release"


Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель случайного релиза"](#user-content-модель-случайного-релиза)

<br>Пример запроса:
```
{"query":"random_release"}
Ответ: 
{
    "status": true,
    "data": {МОДЕЛЬ СЛУЧАЙНОГО РЕЛИЗА}, 
    "error": null
}
```

### Избранное
URL
```
<host>/public/api/index.php
```
Параметры запроса
```
query, id, action, filter, rm, page, perPage
```
* query [string] = "favorites"
* id [string]**?** – ID релиза, который нужно добавить/удалить 
* action [string]**?** – Действие, которое нужно выполнить
	* "delete" – Удалить
	* "add" – Добавить
* filter [string]**?** – Фильтрация выводимых полей, нужно передать строку с полями через сепаратор (пример: "id, names, description").
* rm [string]**?** – Если передать это поле, фильтр будет ИСКЛЮЧАТЬ поля
* page [int/string]**?** – Номер страницы для списка релизов
* perPage [int/string]**?** – Кол-во релизов на одну страницу

Примечания:
* Необходима авторизация
* action и id обязательные поля при удалении/добавлении
* Если релиз уже был добавлен или уже был удалён из списка избранного, то вернётся ошибка

Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель пагинации"](#user-content-модель-пагинации)
* ["Модель релиза"](#user-content-модель-релиза)
* ["Модель серии"](#user-content-модель-серии)
* ["Модель торрента"](#user-content-модель-торрента)
* ["Модель блокировки"](#user-content-модель-релиза)
* ["Модель избранного в релизе"](#user-content-модель-избранного-в-релизе)

<br>Примеры запросов

Список избранного
```
{"query":"favorites","filter":"torrents","rm":""}
{"query":"favorites","page":"4"}
Ответ: 
{
    "status": true,
    "data": [МОДЕЛИ РЕЛИЗОВ], 
    "error": null
}
```
<br>Действие с избранным
```
{"query":"favorites","action":"add"} - Ошибка, не передан id
{"query":"favorites","id":"4","action":"add"}
{"query":"favorites","id":"4","action":"delete"}
Ответ: 
{
    "status": true,
    "data": {МОДЕЛЬ РЕЛИЗА}, 
    "error": null
}
```

### Жанры
URL
```
<host>/public/api/index.php
```
Параметры запроса
```
query
```
* query [string] = "genres"

Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель жанра"](#user-content-модель-жанра)

<br>Пример запроса
```
{"query":"genres"}
Ответ: 
{
    "status": true,
    "data": [МОДЕЛИ ЖАНРА], 
    "error": null
}
```

### Года
URL
```
<host>/public/api/index.php
```
Параметры запроса
```
query
```
* query [string] = "years"

Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель года"](#user-content-модель-года)

<br>Пример запроса
```
{"query":"years"}
Ответ: 
{
    "status": true,
    "data": [МОДЕЛИ ГОДА], 
    "error": null
}
```

### Каталог
URL
```
<host>/public/api/index.php
```

Все параметры запроса
```
query, search, xpage, sort, filter, rm, page, perPage
```
* query [string] = "catalog"
* search [object]**?** = {genre:"genre1,genre2,genre3", year:"2017,2018"}
	* поле genre [string] – Список жанров разделенных через запятую
	* поле year [string] – Список годов разделенных через запятую
* xpage [string]**?** – Где искать
	* "catalog" – В каталоге
* sort [string]**?** – Сортировка
	* "1" – По популярности
	* "2" – По новизне
* filter [string]**?** – Фильтрация выводимых полей, нужно передать строку с полями через сепаратор (пример: "id, names, description").
* rm [string]**?** – Если передать это поле, фильтр будет ИСКЛЮЧАТЬ поля
* page [int/string]**?** – Номер страницы для списка релизов
* perPage [int/string]**?** – Кол-во релизов на одну страницу

Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель пагинации"](#user-content-модель-пагинации)
* ["Модель релиза"](#user-content-модель-релиза)
* ["Модель серии"](#user-content-модель-серии)
* ["Модель торрента"](#user-content-модель-торрента)
* ["Модель блокировки"](#user-content-модель-релиза)
* ["Модель избранного в релизе"](#user-content-модель-избранного-в-релизе)

<br>Пример запроса
```
{"query":"catalog","page":"1","search":{"genre":"","year":"2019"},"xpage":"catalog","sort":"2"} - выведет 1 страницу с 3 релизами за 2019 года с сортировкой по новизне
Ответ: 
{
    "status": true,
    "data": {
        "items": [МОДЕЛИ РЕЛИЗА], 
        "pagination": {МОДЕЛЬ ПАГИНАЦИИ} 
    }, 
    "error": null
}
```

### Поиск по названию
URL
```
<host>/public/api/index.php
```

Все параметры запроса
```
query, search, filter, rm, page, perPage
```
* query [string] = "search"
* search [string] – Название релиза
* filter [string]**?** – Фильтрация выводимых полей, нужно передать строку с полями через сепаратор (пример: "id, names, description").
* rm [string]**?** – Если передать это поле, фильтр будет ИСКЛЮЧАТЬ поля
* page [int/string]**?** – Номер страницы для списка релизов
* perPage [int/string]**?** – Кол-во релизов на одну страницу

Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель пагинации"](#user-content-модель-пагинации)
* ["Модель релиза"](#user-content-модель-релиза)
* ["Модель серии"](#user-content-модель-серии)
* ["Модель торрента"](#user-content-модель-торрента)
* ["Модель блокировки"](#user-content-модель-релиза)
* ["Модель избранного в релизе"](#user-content-модель-избранного-в-релизе)
* ["Модель комментариев ВКонтакте"](#user-content-модель-комментариев-вконтакте)

<br>Пример запроса
```
{"query":"search","search":"boruto"}
Ответ: 
{
    "status": true,
    "data": {
        "items": [МОДЕЛИ РЕЛИЗА], 
        "pagination": {МОДЕЛЬ ПАГИНАЦИИ} 
    }, 
    "error": null
}
```

### YouTube
URL
```
<host>/public/api/index.php
```
Параметры запроса
```
query, page, perPage
```
* query [string] = "youtube"
* page [int/string]**?** – Номер страницы для списка релизов
* perPage [int/string]**?** – Кол-во релизов на одну страницу

Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель пагинации"](#user-content-модель-пагинации)
* ["Модель YouTube"](#user-content-модель-youtube)

<br>Пример запроса
```
{"query":"youtube"}
Ответ: 
{
    "status": true,
    "data": [МОДЕЛИ YOUTUBE], 
    "error": null
}
```

### Пользователь
URL
```
<host>/public/api/index.php
```
Параметры запроса
```
query
```
* query [string] = "user"

Примечания:
* Необходима авторизация

Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель пользователя"](#user-content-пользователя)

<br>Пример запроса
```
{"query":"user"}
Ответ: 
{
    "status": true,
    "data": {МОДЕЛЬ ПОЛЬЗОВАТЕЛЯ}, 
    "error": null
}
```

### Комментарии ВКонтакте
URL
```
<host>/public/api/index.php
```
Параметры запроса
```
query
```
* query [string] = "vkcomments"

Используемые модели:
* ["Базовая модель ответа"](#user-content-базовая-модель-ответа)
* ["Модель комментариев ВКонтакте"](#user-content-модель-комментариев-вконтакте)

<br>Пример запроса
```
{"query":"vkcomments"}
Ответ: 
{
    "status": true,
    "data": {МОДЕЛЬ КОММЕНТАРИЕВ ВКОНТАКТЕ}, 
    "error": null
}
```

---
### Модели данных

##### Базовая модель ответа
```
{
    "status": false,
    "data": null, 
    "error": {
        "code": 400,
        "message": null,
        "description": null
    }
}
```
* status [boolean] – true если запрос успешно выполнился, false - если произошла ошибка
* data [object/array/any]**?** – Нужные данные
* error [object]**?** – Объект ошибка
	* code [int] – Код ошибки, http код, либо специальный
	* message [string]**?** – Сообщение ошибки
	* description [string]**?** – Дополнительная информация, описание ошибки
 
##### Модель пагинации
```
{  
    "page":0,
    "perPage":3,
    "allPages":211,
    "allItems":634
}
```
* page [int] – Текущая страница
* perPage [int] – Кол-во элементов на страница
* allPages [int] – Кол-во всех страниц
* allItems [int] – Кол-во всех элементов

##### Модель релиза
```
{  
    "id":1202,
    "code":"sakurako-san-no-ashimoto-ni-wa-shitai-ga-umatteiru",
    "names":[  
        "Труп под ногами Сакурако",
        "Sakurako-san no Ashimoto ni wa Shitai ga Umatteiru"
    ],
    "series":"1-12",
    "poster":"/upload/release/350x500/default.jpg",
    "favorite": МОДЕЛЬ ИЗБРАННОГО В РЕЛИЗЕ,
    "last":"1202",
    "moon":"https://streamguard.cc/serial/f9f3c92e182de8c722ed0c13e8087558/iframe?nocontrols_translations=1",
    "status":"Завершен",
    "type":"ТВ (>12 эп.), 25 мин.",
    "genres":[  
        "приключения",
        "мистика",
        "детектив"
    ],
    "voices":[  
        "Mikrobelka",
        "HectoR",
        "Aemi"
    ],
    "year":"0",
    "day":"1",
    "description":"Описание релиза <a href='#'>которое может содержать html</a>",
    "blockedInfo": МОДЕЛЬ БЛОКИРОВКИ,
    "playlist":[ МОДЕЛЬ СЕРИИ ],
    "torrents":[ МОДЕЛЬ ТОРРЕНТА]
}
```
Все поля Nullable, т.к. можно убрать их фильтром
* id [int]**?** – ID релиза
* code [string]**?** – Код релиза, используется для создания ссылки
* names [array[string]]**?** – Список названий релиза. Пока-что максимум 2 названия может быть. Первое - Русское, второе - Английское.
* series [string]**?** – Кол-во серий в релизе, используется при выводе списка релизов
* poster [string]**?** – Относительны url на постер для списка
* favorite [object]**?** – ["Модель избранного в релизе"](#user-content-модель-избранного-в-релизе)
* last [???]**?** – По идеи должен быть timestamp последнего обновления релиза, но пока-что выводится его id и пока непонятно какого типа будет
* moon [string]**?** – Ссылка на веб-плеер
* status [string]**?** – Статус релиза текстом
* type [string]**?** – Типа релиза
* genres [array[string]]**?** – Список жанров
* voices [array[string]]**?** – Список людей, которые озвучивали релиз
* year [string]**?** – Год выпуска релиза
* day [string]**?** – День недели, когда выходят новые серии
* description [string]**?** – Описание релиза, может содержать html код
* blockedInfo [object]**?** – ["Модель блокировки"](#user-content-модель-релиза)
* playlist [array[object]]**?** – Список из ["Модель серии"](#user-content-модель-серии)
* torrents [array[object]]**?** – Список из ["Модель торрента"](#user-content-модель-торрента)
* url - `<host>/release/ + code + .html` (пример: `https://www.anilibria.tv/release/sakurako-san-no-ashimoto-ni-wa-shitai-ga-umatteiru.html`)

##### Модель серии
```
{  
    "id":1,
    "title":"Серия 1",
    "sd":"https:\/\/host.anilibria.tv\/videos\/ts\/0000\/0001-sd\/playlist.m3u8",
    "hd":"https:\/\/host.anilibria.tv\/videos\/ts\/0000\/0001\/playlist.m3u8",
    "fullhd":"https:\/\/host.anilibria.tv\/videos\/ts\/0000\/0001-hd\/playlist.m3u8",
    "srcSd":"https:\/\/host.anilibria.tv\/get\/somestring\/somenumber\/mp4\/0000\/0001-sd.mp4?download=Release Name-1-sd.mp4",
    "srcHd":"https:\/\/host.anilibria.tv\/get\/somestring\/somenumber\/mp4\/0000\/0001.mp4?download=Release Name-1-hd.mp4"
}
```
* id [int] – ID серии, по сути это номер серии
* title [string] – Название для отображения в списке серий 
* sd [string] – Ссылка на SD плейлист для онлайн плеера
* hd [string] – Ссылка на HD плейлист для онлайн плеера
* fullhd [string]**?** – Ссылка на FullHD плейлист для онлайн плеера (поле опциональное)
* srcSd [string]**?** - Ссылка на SD файл для скачивания
* srcHd [string]**?** - Ссылка на HD файл для скачивания

##### Модель торрента
```
{  
    "id":977,
    "hash":"99b8dff0ce599c463f84ce23896fc285c892cfad",
    "leechers":0,
    "seeders":0,
    "completed":3845,
    "quality":"HDTV-Rip 720p",
    "series":"1-12",
    "size":3938641843,
    "url":"/upload/torrents/977.torrent"
}
```
* id [int] – ID торрента
* hash [string] – Хеш
* leechers [int] – Скачивающие 
* seeders [int] – Раздающие
* completed [int] – Возможно кол-во загрузок торрента
* quality [string] – Качество
* series [string] – Кол-во серий
* size [long] – Размер файлов торрента, в байтах
* url [string] – относительный путь до торрента. Вид ссылки может меняться

##### Модель блокировки
```
{  
    "blocked":false,
    "reason":null
}
```
* blocked [boolean] – Релиз заблокирован (по авторскому праву или еще что, разные ситуации бывают)
* reason [string]**?** – Причина блокировки

##### Модель избранного в релизе
```
{  
    "rating":421,
    "added":false
}
``` 
* rating [int] – Рейтинг релиза (кол-во пользователей, которые добавили его в избранное)
* added [boolean] – Флаг того, что добавлен релиз в твоё избранное

##### Модель ленты
```
{  
    "release": {МОДЕЛЬ РЕЛИЗА},
    "youtube": {МОДЕЛЬ YOUTUBE}
}
``` 
* release [object]**?** – ["Модель релиза"](#user-content-модель-релиза)
* youtube [object]**?** – ["Модель YouTube"](#user-content-модель-youtube)

##### Модель расписания
```
{  
    "day": "1",
    "items": [МОДЕЛИ РЕЛИЗА]
}
``` 
* day [string] – День недели. (1 - понедельник, 7 - воскресенье).
* items [array[object]] – Массив ["Модель релиза"](#user-content-модель-релиза)

##### Модель случайного релиза
```
{  
    "code": "boruto-naruto-next-generations"
}
``` 
* code [string] – Код релиза

##### Модель жанра
```
"genre1"
```
* Просто строка

##### Модель года
```
"2019"
```
* Просто строка

##### Модель пользователя
```
{  
    "id":0,
    "login":"testuser",
    "avatar":"/upload/avatars/img.jpg"
}
``` 
* id [int] – ID пользователя
* login [string] – Логин пользователя
* avatar [string] – Ссылка на аватар пользователя

##### Модель YouTube
```
{  
    "id":64,
    "title":"С АНИДАБОМ ЧТО-ТО НЕ ТАК \/ +СТРАЙК КАНАЛУ | ЛЛН",
    "image":"\/upload\/youtube\/a73cc011.jpg",
    "vid":"zeQtOtNad7o",
    "views":19966,
    "comments":690,
    "timestamp":1549102230
}
``` 
* id [int] – ID айтема
* title [string] – Заголовок
* image [string] – Ссылка на превью видео
* vid [string] – ID на youtube
* views [int] – Кол-во просмотров на  youtube
* comments [int] – Кол-во комментариев на  youtube
* timestamp [int] – Timestamp создания айтема в секундах

##### Модель комментариев ВКонтакте
```
{  
    "baseUrl":"https://dev.anilibria.tv/",
    "script":"<div id="vk_comments"></div><script type="text/javascript" src="https://vk.com/js/api/openapi.js?160" async onload="VK.init({apiId: 6822494, onlyWidgets: true}); VK.Widgets.Comments(\'vk_comments\', {limit: 8, attach: false});" ></script>"
}
``` 
* baseUrl [string] – Базовый урл для webview, чтобы виджет думал, что он на реальном сайте
* script [string] – Необходимый HTML для работы виджета комментариев

