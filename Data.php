<?php
/**
 * A way to interact with where our data is stored.
 */

class Data {
	/* Class config. idgaf about creds here cause db grants are awesome */
	private $config = [
		'database' => [
			'hostname' => 'localhost',
			'username' => 'heick.family',
			'password' => 'family.heick',
			'database' => 'heick.family',
		],
	];

	/* When we connect to the db this is our handle */
	private $link = null;

	/**
	 * Class constructor
	 * Makes sure we have a connection to the database. One connection per request, no persistence.
	 * @see https://www.php.net/manual/en/mysqli.construct.php
	 */
	public function __construct()
	{
		if (is_null($this->link))
		{
			$db = $this->config['database'];
			$this->link = mysqli_connect($db['hostname'], $db['username'], $db['password'], $db['database']);
			if (mysqli_connect_errno())
			{
				$this->link = null;
				throw new Exception('mysqli connection error: ' . mysqli_connect_error());
			}
		}
	}

	/**
	 * Class destructor
	 */
	public function __destruct()
	{
		if (!is_null($this->link))
		{
			mysqli_close($this->link);
			$this->link = null;
		}
	}

	/**
	 * In case we wanna do some fancy interactions w/ mysql
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * shorthand real_escape_string, since we just wanna make neater things
	 */
	public function escape($string)
	{
		return mysqli_real_escape_string($this->link, $string);
	}
}