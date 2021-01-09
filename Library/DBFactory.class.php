<?php

namespace Library;

class DBFactory
{
	public static function MySQLPDO()
	{
		$db = new \PDO('mysql:host=localhost;dbname=c1146011c_iob;charset=utf8', 'c1146011c_iob', 'IOB@2020');
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return $db;
	}
	public static function MySQLMySQLi()
	{
		return new \MySQLi('localhost', 'c1146011c_iob', 'IOB@2020', 'c1146011c_iob');
	}
}
