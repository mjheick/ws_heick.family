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
	 * @return null|array null if nothing, array of data
	 */
	public static function getPerson($id = 0)
	{
		$data = null;
		$query = 'SELECT * FROM `family` WHERE `id`=' . self::_escape($id) . ' LIMIT 1';
		$result = self::_query($query);
		while ($row = mysqli_fetch_assoc($result))
		{
			$data = $row;
			return $data;
		}
		return null;
	}

	/**
	 * Gets a persons parents
	 *
	 * @param integer pk into family table
	 * @return array 2 people who would be considered parents
	 */
	public static function getParents($id = 0)
	{
		$data = [];
		$person = self::getPerson($id);
		$parentx = isset($person['parent-x']) ? $person['parent-x'] : 0;
		$parenty = isset($person['parent-y']) ? $person['parent-y'] : 0;
		$data = [
			'x' => self::getPerson($parentx),
			'y' => self::getPerson($parenty),
		];
		return $data;
	}

	/**
	 * Gets a persons partner
	 *
	 * @param integer pk into family table
	 * @return array person who the partner is
	 */
	public static function getPartner($id = 0)
	{
		$data = [];
		$person = self::getPerson($id);
		$partner = isset($person['partner']) ? $person['partner'] : 0;
		if ($partner == 0)
		{
			return null;
		}
		return self::getPerson($partner);
	}

	/**
	 * Searches names and returns data based on query
	 *
	 * @param string what to search for
	 * @param integer maximum amount of results to query for
	 * @return array results
	 */
	public static function Search($query = '', $results = 10)
	{
		$query = str_replace(' ', '%', $query);
		$query = 'SELECT * FROM `family` WHERE `name` LIKE "%' . self::_escape($query) . '%" LIMIT ' . $results;
		$res = self::_query($query);
		$data = [];
		while ($row = mysqli_fetch_assoc($res))
		{
			$data[] = $row;
		}
		return $data;
	}

	/**
	 * Performs a query against the mysql database w/ preset query
	 *
	 * @param string query to send to mysql
	 * @return Mysqli_Result a mysql result
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