<?php

	// Вместо xxx подставить свои значения

	// Универсальные настройки
	$v = "v=5.85&";		// Версия API
	$access_token = "access_token=xxx";



	// Настройки запроса wall.get
	$urlWallGet = "https://api.vk.com/method/wall.get?";				// Начало запроса wall.get
	$owner_id = "owner_id=-xxx&";																// id группы, со стены которой необходимо получить записи
	$offset = "offset=xxx&";																		// Смещение от 0 (0-прикрепленная запись)
	$count = "count=xxx&";																			// Количество записей, которое необходимо получить (макс. 100)

	$wallGet = $urlWallGet.$owner_id.$offset.$count.$v.$access_token;
	$wallGet = json_decode(file_get_contents($wallGet), true);

	$wallGet = $wallGet['response']['items']['0'];							// Сокращение массива до нужного размера
	$attachments = $wallGet['attachments'];											// Приложения к записи на стене (фото)
	$text = $wallGet['text'];																		// Текст записи
	$signer_id = $wallGet['signer_id'];													// id пользователя, который находится в подписи к записи



	// Настройки запроса users.get
	$urlUsersGet = "https://api.vk.com/method/users.get?";			// Начало запроса users.get
	$user_id = "user_ids=".$signer_id."&";											// id пользователя, который находится в подписи к записи
	$fields = "fields=photo_100&";															// Фильтр для получения URL аватара пользователя

	$userGet = $urlUsersGet.$user_id.$fields.$v.$access_token;
	$userGet = json_decode(file_get_contents($userGet), true);

	$userGet = $userGet['response']['0'];												// Сокращение массива до нужного размера
	$user = $userGet['first_name']." ".$userGet['last_name'];		// Имя Фамилия
	$user_avatar = $userGet['photo_100'];												// URL аватара пользователя

?>
