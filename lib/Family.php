<?php
/**
 * This is how we CRUD the family
 */

class Family
{
	/* MySQL connection data */
	private static $database = [
		'hostname' => 'localhost',
		'username' => 'heick.family',
		'password' => 'family.heick',
		'database' => 'heick.family',
	];

	/* When we connect to the db this is our handle */
	private static $link = null;

	/**
	 * Gets a persons information via an integer identifier
	 * 
	 * @param integer pk into family table
	 * @return null|array null if nothing, array of name/dob/dod
	 */
	public static function getMe($id = 0)
	{
		$data = null;
		$query = 'SELECT * FROM `family` WHERE `id`=' . self::_escape($id) . ' LIMIT 1';
		$result = self::_query($query);
		while ($row = mysqli_fetch_assoc($result)) {
			$data['name'] = $row['name'];
			$data['dob'] = $row['dob'];
			$data['dod'] = $row['dod'];
			return $data;
		}
		return $data;
	}

	/**
	 * Performs a query against the mysql database w/ preset query
	 */
	private static function _query($query = null)
	{
		if (is_null($query))
		{
			return null;
		}
		self::_connect();
		$result = mysqli_query(self::$link, $query);
		return $result;
	}

	/**
	 * shorthand real_escape_string, since we just wanna make neater things
	 */
	private static function _escape($string = "")
	{
		self::_connect();
		return mysqli_real_escape_string(self::$link, $string);
	}

	/**
	 * Initiates a connection to the MySQL database
	 * @throws Exception something wrong w/ the database
	 */
	private static function _connect()
	{
		if (!is_null(self::$link))
		{
			return;
		}
		$db = self::$database;
		self::$link = mysqli_connect($db['hostname'], $db['username'], $db['password'], $db['database']);
		if (mysqli_connect_errno())
		{
			self::$link = null;
			throw new Exception('mysqli connection error: ' . mysqli_connect_error());
		}
		return;
	}
}