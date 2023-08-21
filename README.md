Для запуска должен быть установлен docker, docker-compose а так же утилита make

Команды: <br>
make ud <br>

<p>Далее команды для первого запуска - пересоздаётся БД, создаётся схема, генерятся тестовые данные.</p>
make php # зайдет в контейнер php-fpm <br>
php bin/console make:migration # если задаст вопрос ввести yes и нажать энтер <br>
php bin/console d:s:d --forse  # если задаст вопрос ввести yes и нажать энтер <br>
php bin/console d:s:c  # если задаст вопрос ввести yes и нажать энтер <br>
php bin/console d:m:m  # если задаст вопрос ввести yes и нажать энтер <br>
php bin/console d:f:l  # если задаст вопрос ввести yes и нажать энтер <br>

Последняя команда создаст в БД пользователя Администратор
логин: admin@gpc.ru
пароль: 123456


<h1>API дока</h1>
<p>
20.08.2023:<br>
Получить коллекции:<br>
GET <br>
/api1/spa/get-collections
</p>
<p>
Start version:<br>
Получить филиалы для коллекции:<br>
GET <br>
/api1/spa/get-filials/collections/{collection_id}
</p>
<p>
Получить филиалы для коллекции:<br>
GET <br>
/api1/spa/get-filials/collections/{collection_id}
</p>
<p>
Получить услуги для филиала:<br>
GET <br>
/api1/spa/get-services/filial/{filial_id}
</p>
<p>
Сгенерировать данные для календаря<br>
(Получить список специалистов и занятий):<br>
GET <br>
/api1/spa/get-calendar/filials/{filial_id}/services/{service_id} <br>
- вернёт расписания на текущую дату.<br>
Для получения на любую дату, добавить GET параметры вида : <br>
?year=2023&month=08&day=22
</p>

<h2>Сохранение</h2>
Отправить заполненуб форму и оформить запись: <br>
POST<br>
/api1/crm/boocking/createcard  --> {{ path('app_api1_crm_boocking_createcard') }}<br>
<br>
fetch( "/api1/crm/boocking/createcard", {<br>
<div style="margin-left: 10px;">
    method: 'POST',<br>
    headers: {<br>
    'Content-Type': 'application/json;charset=utf-8'<br>
    },<br>
body: newData <br>
</div>
})<br>
<br>
let newData = JSON.stringify(data) <br>
<br>

let data = {<br>
date: requestDate,                  ---> calenadar.date_string  ("09.07.2023")<br>
time: el.dataset.timestart,         ---> параметр элемента с датой в календаре<br>
spec: el.dataset.specid,            ---> параметр элемента с датой в календаре<br>
filial: "{{ filSer.filial }}",      ---> id филиала <br>
service: "{{ filSer.service.id }}"   ---> id услуги <br>
}<br>