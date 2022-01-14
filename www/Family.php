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
	 * @todo add caching to this, since it'll be heavily used. prob wanna think about APC
	 *
	 * @param integer pk into family table
	 * @return null|array null if nothing, array of data
	 */
	public static function getPerson($id = 0)
	{
		$data = null;
		$query = 'SELECT * FROM `family` WHERE `id`=' . self::_escape($id) . ' LIMIT 1';
		$result = self::_query($query);
		if ($result === false)
		{
			return null;
		}
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
	 * Returns all natural children and "other" parents of children
	 *
	 * @param integer pk into family table
	 * @return array keys are other parents, values are shared children
	 */
	public static function getChildren($id = 0)
	{
		$data = [];
		$query = 'SELECT * FROM `family` WHERE (`parent-x`=' . self::_escape($id) . ' OR `parent-y`=' . self::_escape($id) . ')';
		$result = self::_query($query);
		while ($row = mysqli_fetch_assoc($result))
		{
			$child = $row['id']; /* This is our kid */
			if ($row['parent-x'] == $id)
			{
				$partner = $row['parent-y'];
			}
			else
			{
				$partner = $row['parent-x'];
			}
			if (!isset($data[$partner]))
			{
				$data[$partner] = [];
			}
			$data[$partner][] = $child;
		}
		return $data;
	}

	/**
	 * Returns all siblings: full, half, and step
	 * 
	 * @param integer pk into family table
	 * @return array keys to indexes of siblings. keys will exist if items are present.
	 *               'full' => [],
	 *               'half' => [],
	 *               'step' => [],
	 */
	public static function getSiblings($id = 0)
	{
		$data = [];
		/* Get me. Validate we have a parent. */
		$me = self::getPerson($id);
		if (is_null($me))
		{
			return $data;
		}
		$parentx = $me['parent-x'];
		$parenty = $me['parent-y'];

		/* Do a search for full blooded siblings. can only do this if we know both parents */
		if (($parentx > 0) && ($parenty > 0))
		{
			$full_siblings = [];
			$query = 'SELECT * FROM `family` WHERE (`parent-x`=' . $parentx . ' AND `parent-y`=' . $parenty . ') OR (`parent-x`=' . $parenty . ' AND `parent-y`=' . $parentx . ')';
			$result = self::_query($query);
			while ($row = mysqli_fetch_assoc($result))
			{
				if ($row['id'] != $id) /* Don't need to count me in */
				{
					$full_siblings[] = $row['id'];
				}
			}
			if (count($full_siblings) > 0)
			{
				$data['full'] = $full_siblings;
			}
		}

		/* Do a search for half blooded siblings. only need to know one parent */
		if (($parentx > 0) || ($parenty > 0))
		{
			$half_siblings = [];
			$parents = [];
			if ($parentx > 0)
			{
				$parents[] = $parentx;
			}
			if ($parenty > 0)
			{
				$parents[] = $parenty;
			}
			foreach ($parents as $parent)
			{
				$query = 'SELECT * FROM `family` WHERE (`parent-x`=' . $parent . ' OR `parent-y`=' . $parent . ')';
				$result = self::_query($query);
				while ($row = mysqli_fetch_assoc($result))
				{
					if ($row['id'] != $id) /* Don't need to count me in */
					{
						$half_siblings[] = $row['id'];
					}
				}
			}
			if (count($half_siblings) > 0)
			{
				$data['half'] = $half_siblings;
			}
		}

		/* Do a search for step siblings. only need to know one parents partner */
		if (($parentx > 0) || ($parenty > 0))
		{
			$step_siblings = [];
			$parents = [];
			if ($parentx > 0)
			{
				$parents[] = $parentx;
			}
			if ($parenty > 0)
			{
				$parents[] = $parenty;
			}
			$step_parents = [];
			foreach ($parents as $parent)
			{
				$them = self::getPerson($parent);
				if ($them['partner'] > 0)
				{
					$step_parents[] = $p['partner'];
				}
			}
			foreach ($step_parents as $step_parent)
			{
				$query = 'SELECT * FROM `family` WHERE (`parent-x`=' . $step_parent . ' OR `parent-y`=' . $step_parent . ')';
				$result = self::_query($query);
				while ($row = mysqli_fetch_assoc($result))
				{
					if ($row['id'] != $id) /* Don't need to count me in, but it should NEVER happen here lol */
					{
						$step_siblings[] = $row['id'];
					}
				}
			}
			if (count($step_siblings) > 0)
			{
				$data['step'] = $step_siblings;
			}
		}
		return $data;
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
	 * Formats a name as presented from getPerson for display
	 *
	 * @param array fields presented from getPerson or anything related
	 * @return string a displayable name
	 */
	public static function formatName($id = [])
	{
		if (is_null($id) || !is_array($id))
		{
			return 'Unknown';
		}
		return $id['name'];
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