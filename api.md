##### Примечание.
* Тип поля указывается в [ ]
* **?** – Обозначает Nullable или необязательное поле

Посмотреть примеры ответов - https://test.anilibria.tv/test.php

##### Базовая модель ответа.
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
 
##### Релизы
URL
```
<host>/public/api/index.php
```

Все параметры запроса
```
query, id, code, filter, rm, page, perPage
```
* query [string] – Что именно нужно вывести. 
 * release - Вывод одного релизы, доступно [id, code, filter, rm]
 * list – Вывод списка всех релизов, есть пагинация, доступно [filter, rm, page, perPage]
 * info - Вывод необходимы релизов, доступно [id, filter, rm]
* id [int/string]**?** – "id" релиза, обязателен для [info, release*]
* code [string]**?** – "code" релиза, обазателен для [release*]
* filter [string]**?** – Фильтрация выводимых полей, нужно передать строку с полями через сепаратор (пример: "id, names, description").
* rm [string]**?** – Если передать это поле, фильтр будет ИСКЛЮЧАТЬ поля
* page [int/string]**?** – Номер страницы для списка релизов
* perPage [int/string]**?** – Кол-во релизов на одну страницу

Примеры (не реальный body запроса)
```
Релиз
{"query":"release"} – будет ошибка 400, т.к. нет id и code
{"query":"release","id":"120211111"} – будет ошибка, 404
{"query":"release","id":"1202"} – релиз по id
{"query":"release","code":"sakurako-san-no-ashimoto-ni-wa-shitai-ga-umatteiru"} – релиз по code

Нужные релизы
{"query":"info","id":"1202, 473"} - выведет два релиза, если найдёт
{"query":"info","id":"1202, 473","filter":"description,torrent"} - будут только поля descriptin и torrent
{"query":"info","id":"1202, 473","filter":"description,torrent","rm":""} - исключит поля descriptin и torrent

Список релизов
{"query":"list","page":"1","perPage":"3"} - выведет 1 страницу с 3 релизами
```

###### Модель релиза
```
{  
    "id":1202,
    "code":"sakurako-san-no-ashimoto-ni-wa-shitai-ga-umatteiru",
    "names":[  
        "Труп под ногами Сакурако",
        "Sakurako-san no Ashimoto ni wa Shitai ga Umatteiru"
    ],
    "series":"1-12",
    "poster":"/upload/release/270x390/default.jpg",
    "posterFull":"/upload/release/350x500/default.jpg",
    "favorite": МОДЕЛЬ ИЗБРАННОГО В РЕЛИЗЕ,
    "last":"1202",
    "moon":"https://streamguard.cc/serial/f9f3c92e182de8c722ed0c13e8087558/iframe?nocontrols_translations=1",
    "status":"2",
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
    "description":"Рассказ данного аниме-сериала повествует нам о парнишке Шотаро Татеваки - ученика старшей школы города Ашикава. Там он встречает таинственную девушку Сакурако Куджо, которая обожает коллекционировать, изучать, да и вообще просто смотреть на всевозможные кости. Заинтересовавшись ей, наш главный герой решил составить ей компанию, ну и узнать, сколько у неё скелетов в шкафу в буквальном и небуквальном смысле.",
    "blockedInfo": МОДЕЛЬ БЛОКИРОВКИ,
    "playlist":[ МОДЕЛЬ СЕРИИ ],
    "torrents":[ МОДЕЛЬ ТОРРЕНТА]
}
```
Все поля Nullable, т.к. можно убрать их фильтром
* id[int]**?** – ID релиза
* code[string]**?** – Код релиза, используется для создания ссылки
* names[array[string]]**?** – Список названий релиза. Пока-что максимум 2 названия может быть. Первое - Русское, второе - Английское.
* series[string]**?** – Кол-во серий в релизе, используется при выводе списка релизов
* poster[string]**?** – Относительны url на постер для списка
* posterFull[string]**?** – Относительны url на постер для детального окна
* favorite[object]**?** – "Модель избранного в релизе"
* last[???]**?** – По идеи должен быть timestamp последнего обновления релиза, но пока-что выводится его id и пока непонятно какого типа будет
* moon[string]**?** – Ссылка на веб-плеер
* status[string]**?** – Статус релиза (пока-что выводится просто цифра)
* type[string]**?** – Типа релиза
* genres[array[string]]**?** – Список жанров
* voices[array[string]]**?** – Список людей, которые озвучивали релиз
* year[string]**?** – Год выпуска релиза
* day[string]**?** – День недели, когда выходят новые серии
* description[string]**?** – Описание релиза, может содержать html код
* blockedInfo[object]**?** – "Модель блокировки"
* playlist[array[object]]**?** – Список из "Модель серии"
* torrents[array[object]]**?** – Список из "Модель торрента"
###### Модель серии
```
{  
    "id":12,
    "title":"Серия 12",
    "sd":"https://de3.anilibria.tv/videos/ts/1202/0012-sd/playlist.m3u8",
    "hd":"https://de3.anilibria.tv/videos/ts/1202/0012/playlist.m3u8"
}
```
* id[int] – ID серии, по сути это номер серии
* title[string] – Название для отображения в списке серий 
* sd[string] – Ссылка на плейлист для онлайн плеера
* hd[string] – Ссылка на плейлист для онлайн плеера
* srcSd[string]**?** - Ссылка на файл для скачивания (пока этого нет)
* srcHd[string]**?** - Ссылка на файл для скачивания (пока этого нет)
###### Модель торрента
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
* id[int] – ID торрента
* hash[string] – Хеш
* leechers[int] – Скачивающие 
* seeders[int] – Раздающие
* completed[int] – Возможно кол-во загрузок торрента
* quality[string] – Качество
* series[string] – Кол-во серий
* size[long] – Размер файлов торрента, в байтах
* url[string] – относительный путь до торрента. Вид ссылки может меняться
###### Модель блокировки
```
{  
    "blocked":false,
    "reason":null
}
```
* blocked[boolean] – Релиз заблокирован (по авторскому праву или еще что, разные ситуации бывают)
* reason[string]**?** – Причина блокировки
###### Модель избранного в релизе
```
{  
    "rating":421,
    "added":false
}
``` 
* rating[int] – Рейтинг релиза (кол-во пользователей, которые добавили его в избранное)
* added[boolean] – Флаг того, что добавлен релиз в твоё избранное
