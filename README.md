Для запуска должен быть установлен docker, docker-compose а так же утилита make

Команды: <br>
make ud <br>
make php # зайдет в контейнер php-fpm <br>
php bin/console make:migration # если задаст вопрос ввести yes и нажать энтер <br>
php bin/console d:s:d --forse  # если задаст вопрос ввести yes и нажать энтер <br>
php bin/console d:s:c  # если задаст вопрос ввести yes и нажать энтер <br>
php bin/console d:m:m  # если задаст вопрос ввести yes и нажать энтер <br>
php bin/console d:f:l  # если задаст вопрос ввести yes и нажать энтер <br>

Последняя команда создаст в БД пользователя Администратор
логин: admin@gpc.ru
пароль: 123456

