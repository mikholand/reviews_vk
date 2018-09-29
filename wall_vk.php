<?php

	define('ROOT', dirname(__FILE__));
	include_once (ROOT.'/vk/db.php');
	include_once (ROOT.'/vk/vk_params.php');


	if ((array_key_exists('signer_id', $wallGet)) and ($wallGet['signer_id']!=0)) {	// Проверка: содержит ли массив ключ (подпись к записе)
		echo "Выполнение основной программы";
	} else {
		die("Error!");
	}

	echo "<br>";

	$db = db::getConnection();

	$user_id = intval($userGet['id']);
	$request = $db->query('SELECT count(id) FROM users WHERE user_id ='. $user_id);
	$request->setFetchMode(PDO::FETCH_ASSOC);
	$checkUser = $request->fetch();

	if ($checkUser['count(id)']==0) {	// Проверка: существует ли данный пользователь в таблице? Если нет, то добавить.
		// Добавление пользователя в таблицу users
		$addUser = $db->prepare('INSERT INTO `users` (`id`, `user_id`, `first_name`, `last_name`, `photo_100`) VALUES (NULL, ?, ?, ?, ?);');
		$addUser->execute(array($userGet['id'], $userGet['first_name'], $userGet['last_name'], $userGet['photo_100']));
	} else {
		echo "Такой пользователь существует"; //После тестов удалить
	}


	// Test

	echo "<pre>";
	print_r($userGet);
	echo "</pre>";

	echo $text;

	echo "<br>";
	echo "<img src=".$user_avatar." style=\"margin-top: 0px;\">";
	echo "<br>";
	echo "<h3>".$user."</h3>"

?>
