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
	$request->setFetchMode(PDO::FETCH_ASSOC);
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
	$request = $db->query('SELECT count(id) FROM posts WHERE id ='. $post_id);
	$request->setFetchMode(PDO::FETCH_ASSOC);
	$checkPost = $request->fetch();

	if ($checkPost['count(id)']==0) {
		// Добавление пользователя в таблицу users
		$addPost = $db->prepare('INSERT INTO `posts` (`id`, `signer_id`, `date`, `text`) VALUES (?, ?, ?, ?);');
		$date += 10800;
		$addPost->execute(array($wallGet['id'], $wallGet['signer_id'], $date, $wallGet['text']));
	} else {
		echo "Такой пост существует"; //После тестов удалить
	}


	// Test

	echo "<pre>";
	print_r($attachments);
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
