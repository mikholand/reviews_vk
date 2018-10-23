<?php

	define('ROOT', dirname(__FILE__));
	include_once (ROOT.'/vk/db.php');
	include_once (ROOT.'/vk/vk_params.php');


	if ((array_key_exists('signer_id', $wallGet)) and ($signer_id!=0)) {	// Проверка: содержит ли массив ключ (подпись к записе)
		echo "Добавление пользователя и поста в таблицы";
	} else {
		die("Error!");
	}

	echo "<br>";

	$db = db::getConnection();

	// Проверка: существует ли данный пользователь в таблице? Если нет, то добавить.
	$user_id = intval($userGet['id']);
	$request = $db->query('SELECT count(id) FROM users WHERE user_id ='. $user_id);
	$checkUser = $request->fetch();

	if ($checkUser['count(id)']==0) {
		// Добавление пользователя в таблицу users
		$addUser = $db->prepare('INSERT INTO `users` (`id`, `user_id`, `first_name`, `last_name`, `photo_100`) VALUES (NULL, ?, ?, ?, ?);');
		$addUser->execute(array($userGet['id'], $userGet['first_name'], $userGet['last_name'], $userGet['photo_100']));
	} else {
		echo "Такой пользователь существует"; //После тестов удалить
	}

	// Проверка: существует ли данный пост в таблице? Если нет, то добавить.
	$post_id = intval($wallGet['id']);
	$request = $db->query('SELECT count(post_id) FROM posts WHERE post_id ='. $post_id);
	$checkPost = $request->fetch();

	if ($checkPost['count(post_id)']==0) {
		// Добавление поста в таблицу posts
		$addPost = $db->prepare('INSERT INTO `posts` (`id`, `post_id`, `signer_id`, `date`, `text`) VALUES (NULL, ?, ?, ?, ?);');
		$date += 10800;
		$addPost->execute(array($wallGet['id'], $wallGet['signer_id'], $date, $wallGet['text']));
	} else {
		echo "Такой пост существует"; //После тестов удалить
	}

	// Проверка: добавлялись ли данные фото в таблицу? Если нет, то добавить.
	$request = $db->query('SELECT count(id) FROM attachments WHERE post_id ='. $post_id);
	$checkAttachments = $request->fetch();

	if ($checkAttachments['count(id)']==0) {
		// Добавление ссылок на фотки в таблицу "attachments"
		foreach ($attachments as &$value) {
			$small_foto = $value['photo']['sizes']['3']['url'];
			$big_foto = $value['photo']['sizes']['8']['url'];
			$addAttachments = $db->prepare('INSERT INTO `attachments` (`id`, `post_id`, `small_foto`, `big_foto`) VALUES (NULL, ?, ?, ?);');
			$addAttachments->execute(array($wallGet['id'], $small_foto, $big_foto));
		}
	} else {
		echo "Такие фото существуют"; //После тестов удалить
	}


	// Вывод постов
	$request = $db->query('SELECT count(id) FROM posts'); // Подсчитываем количество постов
	$checkPosts = $request->fetch();
	$array = range(1, $checkPosts['count(id)']);	// Заполняем массив количеством постов
	shuffle($array); // Перемешиваем массив
	//$posts = range(1, $checkPosts['count(id)']);

	foreach (array_slice($array, 0, 10) as $id)		//Оставляем только 10 записей и выводим в этом цикле
	{
		// Выборка данных о посте и пользователе
		$request = $db->query('SELECT posts.date, posts.text, users.user_id, users.first_name, users.last_name, 
																				users.photo_100, "attachments" 
																		FROM posts, users
																		WHERE posts.signer_id = users.user_id 
																			and posts.id ='. $id);
		$selectAll = $request->fetchAll();

		// Добавление к выборке приложений
		$request = $db->query('SELECT attachments.small_foto, attachments.big_foto
																		FROM posts, attachments 
																		WHERE posts.post_id = attachments.post_id 
																			and posts.id ='. $id);
		$selectAll['0']['attachments'] = $request->fetchAll();

		$array[] = $selectAll['0']; // Заполняем массив постами
	}
	$array = array_slice($array, 6, 10); // Избавляемся от лишних ячеек в массиве (6 - временное число, заменить потом на 10)

	// Test
	echo "<pre>";
	print_r($array);
	echo "</pre>";

	echo "<br>";
	echo "<img src=".$user_avatar." style=\"margin-top: 0px;\">";
	echo "<br>";
	echo "<h3>".$user."</h3>";
	echo "<br>";
	echo gmdate("d.m.Y\ в H:i\ ", $date);
	echo "<br>";
	echo $text;
	echo "<br>";

?>
