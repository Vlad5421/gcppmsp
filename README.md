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
Сгенерировать данные для календаря:<br>
GET <br>
/api1/spa/get-calendar/filials/{filial_id}/services/{service_id}
</p>