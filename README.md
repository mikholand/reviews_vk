# reviews_vk
Отзывы из VK

Для работы необходимо переименовать папку "vk_null" в "vk" и подставить свои значения вместо "xxx".

Работа еще не закончена.

На данном этапе:
- подключена БД
- введены все параметры для API vk
- реализована проверка на нового пользователя
- если пользователя не существует в БД, то он добавляется
- реализована проверка на существование поста
- если поста нет в БД, то он добавляется
- реализована проверка на существование фоток в таблице attachments
- если фоток с поста нет в БД, то они добавляется
- вывод 10 рандомных постов
