
# Вкратце

## Юзал дефолтный yii фреймворк, некоторые вещи рудиментально остались. 

## Контроллер для получения всех оповещений и отправке по хттп
/controllers/NotificationController.php
На базовом уровне всё что было в требованиях делает


## Модель сущности
/models/Notification.php

## Консьюмер
Консьюмера решил сделать через команду, её контроллер  commands/RabbitConsumerController.php
Практики использовал не оч хорошие, но времени у меня особо не было.

### Если нужно запустить потыкать, docker-compose up
### В коментах старался  отписывать почему сделал так как сделал
