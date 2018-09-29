<?php

	class db
	{
		public static function getConnection()
		{
			$paramsPath = ROOT.'/vk/db_params.php';
			$params = include ($paramsPath);

			$dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
			$db = new PDO($dsn, $params['user'], $params['password']);

			return $db;
		}
	}
?>
