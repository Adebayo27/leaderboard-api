<?php

	/**
	 * 
	 */
	class db
	{
		
		function connect()
		{
			$host = "localhost" ;
			$user = "root";
			$dbname = "leaderboard";
			$pass = "";

			$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			return $pdo;
		}
	}