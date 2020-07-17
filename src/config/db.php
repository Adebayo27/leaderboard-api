<?php

	/**
	 * 
	 */
	class db
	{
		
		function connect()
		{
			// $host = "u28rhuskh0x5paau.cbetxkdyhwsb.us-east-1.rds.amazonaws.com" ;
			// $user = "goji21eq5u4wxqlz";
			// $dbname = "rtu11t8dugq2wtqf";
			// $pass = "m8nv9grd9iu7nfek";

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
