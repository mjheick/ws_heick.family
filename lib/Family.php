<?php
/**
 * This is how we CRUD the family
 */

class Family
{
	/* When we connect to the db this is our handle */
	private static $link = null;

	/**
	 * Gets a persons information via an integer identifier
	 * 
	 * @todo add caching to this, since it'll be heavily used. prob wanna think about APC
	 *
	 * @param integer pk into family table
	 * @return array of data from the table or empty array
	 */
	public static function getPerson($id = 0)
	{
		if ($id == 0)
		{
			return [];
		}
		$query = 'SELECT * FROM `family` WHERE `id`=' . self::_escape($id) . ' LIMIT 1';
		$result = self::_query($query);
		if ($result === false)
		{
			return [];
		}
		while ($row = mysqli_fetch_assoc($result))	 {
			return $row;
		}
		return [];
	}

	/**
	 * Gets a persons parents
	 *
	 * @param integer pk into family table
	 * @return array people who would be considered parents
	 */
	public static function getParents($id = 0)
	{
		$data = [];
		$person = self::getPerson($id);
		$parent_bio_x = isset($person['parent-bio-x']) ? $person['parent-bio-x'] : 0;
		$parent_bio_y = isset($person['parent-bio-y']) ? $person['parent-bio-y'] : 0;
		$parent_adopt_a = isset($person['parent-adopt-a']) ? $person['parent-adopt-a'] : 0;
		$parent_adopt_b = isset($person['parent-adopt-b']) ? $person['parent-adopt-b'] : 0;
		$data = [
			'bio-x' => self::getPerson($parent_bio_x),
			'bio-y' => self::getPerson($parent_bio_y),
			'adopt-a' => self::getPerson($parent_adopt_a),
			'adopt-b' => self::getPerson($parent_adopt_b),
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
			return [];
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
		$query = 'SELECT * FROM `family` WHERE (`parent-bio-x`=' . self::_escape($id) . ' OR `parent-bio-y`=' . self::_escape($id) . ')';
		$result = self::_query($query);
		while ($row = mysqli_fetch_assoc($result))
		{
			$child = $row['id']; /* This is our kid */
			if ($row['parent-bio-x'] == $id)
			{
				$partner = $row['parent-bio-y'];
			}
			else
			{
				$partner = $row['parent-bio-x'];
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
		$parentx = $me['parent-bio-x'];
		$parenty = $me['parent-bio-y'];

		/* Do a search for full blooded siblings. can only do this if we know both parents */
		if (($parentx > 0) && ($parenty > 0))
		{
			$full_siblings = [];
			$query = 'SELECT * FROM `family` WHERE (`parent-bio-x`=' . $parentx . ' AND `parent-bio-y`=' . $parenty . ') OR (`parent-bio-x`=' . $parenty . ' AND `parent-bio-y`=' . $parentx . ')';
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
				$query = 'SELECT * FROM `family` WHERE (`parent-bio-x`=' . $parent . ' OR `parent-bio-y`=' . $parent . ')';
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
				if (count($data['full']) > 0)
				{
					/* If we have 'full' siblings we'll need to cull them out of this list */
					$data['half'] = [];
					foreach ($half_siblings as $p)
					{
						if (!in_array($p, $data['full']))
						{
							$data['half'][] = $p;

						}
					}
				}
				else
				{
					$data['half'] = $half_siblings;
				}
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
					$step_parents[] = $them['partner'];
				}
			}
			foreach ($step_parents as $step_parent)
			{
				$query = 'SELECT * FROM `family` WHERE (`parent-bio-x`=' . $step_parent . ' OR `parent-bio-y`=' . $step_parent . ')';
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
				if (count($data['half']) > 0)
				{
					/* If we have 'half' siblings we'll need to cull them out of this list. queries are too "near" */
					$data['step'] = [];
					foreach ($step_siblings as $p)
					{
						if (!in_array($p, $data['half']))
						{
							$data['step'][] = $p;

						}
					}
				}
				else
				{
					$data['step'] = $step_siblings;
				}
			}
		}
		return $data;
	}

	/**
	 * Returns relevant family members
	 * 
	 * @param integer pk into family table
	 * @return array keys to indexes of parents and parents parents
	 */
	public static function getLineage($id = 0)
	{
		$lineage = [
			'partner' => ['id' => 0, 'name' => 'unknown' ],
			/* parents */
			'parent-bio-x' => ['id' => 0, 'name' => 'unknown' ],
			'parent-bio-y' => ['id' => 0, 'name' => 'unknown' ],
			'parent-adopt-a' => ['id' => 0, 'name' => 'unknown' ],
			'parent-adopt-b' => ['id' => 0, 'name' => 'unknown' ],
			/* bio-x bio-parents */
			'parent-x-parent-bio-x' => ['id' => 0, 'name' => 'unknown' ],
			'parent-x-parent-bio-y' => ['id' => 0, 'name' => 'unknown' ],
			/* bio-y bio-parents */
			'parent-y-parent-bio-x' => ['id' => 0, 'name' => 'unknown' ],
			'parent-y-parent-bio-y' => ['id' => 0, 'name' => 'unknown' ],
		];

		/* Partner */
		$partner = self::getPartner($id);
		$lineage['partner']['id'] = isset($partner['id']) ? $partner['id'] : 0;
		$lineage['partner']['name'] = self::formatName(self::getPerson($lineage['partner']['id']));

		/* Get the parents */
		$parents = self::getParents($id);
		$lineage['parent-bio-x']['id'] = isset($parents['bio-x']['id']) ? $parents['bio-x']['id'] : 0;
		$lineage['parent-bio-x']['name'] = self::formatName(self::getPerson($lineage['parent-bio-x']['id']));
		$lineage['parent-bio-y']['id'] = isset($parents['bio-y']['id']) ? $parents['bio-y']['id'] : 0;
		$lineage['parent-bio-y']['name'] = self::formatName(self::getPerson($lineage['parent-bio-y']['id']));
		$lineage['parent-adopt-a']['id'] = isset($parents['adopt-a']['id']) ? $parents['adopt-a']['id'] : 0;
		$lineage['parent-adopt-a']['name'] = self::formatName(self::getPerson($lineage['parent-adopt-a']['id']));
		$lineage['parent-adopt-b']['id'] = isset($parents['adopt-b']['id']) ? $parents['adopt-b']['id'] : 0;
		$lineage['parent-adopt-b']['name'] = self::formatName(self::getPerson($lineage['parent-adopt-b']['id']));

		/* Parent of bio-x */
		$parents = self::getParents($lineage['parent-bio-x']['id']);
		$lineage['parent-x-parent-bio-x']['id'] = isset($parents['bio-x']['id']) ? $parents['bio-x']['id'] : 0;
		$lineage['parent-x-parent-bio-x']['name'] = self::formatName(self::getPerson($lineage['parent-x-parent-bio-x']['id']));
		$lineage['parent-x-parent-bio-y']['id'] = isset($parents['bio-y']['id']) ? $parents['bio-y']['id'] : 0;
		$lineage['parent-x-parent-bio-y']['name'] = self::formatName(self::getPerson($lineage['parent-x-parent-bio-y']['id']));

		/* Parent of bio-y */
		$parents = self::getParents($lineage['parent-bio-y']['id']);
		$lineage['parent-y-parent-bio-x']['id'] = isset($parents['bio-x']['id']) ? $parents['bio-x']['id'] : 0;
		$lineage['parent-y-parent-bio-x']['name'] = self::formatName(self::getPerson($lineage['parent-y-parent-bio-x']['id']));
		$lineage['parent-y-parent-bio-y']['id'] = isset($parents['bio-y']['id']) ? $parents['bio-y']['id'] : 0;
		$lineage['parent-y-parent-bio-y']['name'] = self::formatName(self::getPerson($lineage['parent-y-parent-bio-y']['id']));

		return $lineage;
	}

	/**
	 * Searches names and returns data based on query
	 *
	 * @param string what to search for
	 * @param integer maximum amount of results to query for
	 * @return array results
	 */
	public static function Search($question = '', $results = 10)
	{
		$question = str_replace(' ', '%', $question);
		$query = 'SELECT * FROM `family` WHERE `name` LIKE "%' . self::_escape($question) . '%" LIMIT ' . $results;
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
		if (isset($id['name']))
		{
			return $id['name'];
		}
		return 'Unknown';
	}

	/**
	 * Gets a list of all family members
	 * 
	 * @return array key/value of index/family member
	 */
	public static function getAllFamily()
	{
		$query = 'SELECT `id`,`name` FROM `family` ORDER BY `name` ASC';
		$res = self::_query($query);
		$data = [];
		while ($row = mysqli_fetch_assoc($res))
		{
			$data[$row['id']] = $row['name'];
		}
		return $data;
	}

	public static function modify($data = [])
	{
		/* Verify that all fields exist in struture before continuing on */
		$fields = ['id', 'name', 'dob', 'dod', 'partner', 'parentx', 'parenty', 'adoptx', 'adopty'];
		foreach ($fields as $field)
		{
			if (!array_key_exists($field, $data))
			{
				return ['status' => 'error'];
			}
		}
		/* Verify that numerical fields have numbers */
		foreach (['partner', 'parentx', 'parenty', 'adoptx', 'adopty'] as $z)
		{
			if ($data[$z] == '')
			{
				$data[$z] = 0;
			}
		}
		$sql_fields = [
			'name' => 'name',
			'parent-bio-x' => 'parentx',
			'parent-bio-y' => 'parenty',
			'parent-adopt-a' => 'adoptx',
			'parent-adopt-b' => 'adopty',
			'partner' => 'partner',
			'dob' => 'dob',
			'dod' => 'dod'
		];
		if ($data['id'] == '')
		{
			/* This is a new person */
			$query = 'INSERT INTO `family` (`name`, `parent-bio-x`, `parent-bio-y`, `parent-adopt-a`, `parent-adopt-b`, `partner`, `dob`, `dod`) VALUES (';
			$comma = false;
			foreach ($sql_fields as $u => $f)
			{
				if ($comma)
				{
					$query .= ',';
				}
				$query .= '"' . self::_escape($data[$f]) . '"';
				$comma = true;
			}
			$query .= ')';
			$res = self::_query($query);
		}
		else
		{
			/* This would be an update */
			$query = 'UPDATE `family` SET ';
			$comma = false;
			foreach ($sql_fields as $u => $f)
			{
				if ($comma)
				{
					$query .= ',';
				}
				$query .= '`' . $u . '`="' . self::_escape($data[$f]) . '"';
				$comma = true;
			}
			$query .= ' WHERE `id`=' . self::_escape($data['id']) . ' LIMIT 1';
			$res = self::_query($query);
		}
		return ['status' => 'OK'];
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
		$config = json_decode(file_get_contents('../conf/configuration.json'), true);
		$db = $config['database'];
		self::$link = mysqli_connect($db['hostname'], $db['username'], $db['password'], $db['database']);
		if (mysqli_connect_errno())
		{
			self::$link = null;
			throw new Exception('mysqli connection error: ' . mysqli_connect_error());
		}
		return;
	}
}
