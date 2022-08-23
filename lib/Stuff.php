<?php
require_once('Family.php');
class Stuff extends Family
{
	/**
	 * This is used on the main page to get the $count "LastUpdated"
	 *
	 * @return array key/value of lastupdated
	 */
	public static function getLastModified($count = 10)
	{
		$data = [];
		$query = 'SELECT `id`,`name` FROM `family` ORDER BY `lastupdated` DESC LIMIT ' . $count;
		$result = self::_query($query);
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = ['id' => $row['id'], 'name' => $row['name']];
		}
		return $data;
	}

	/**
	 * This dumps data for 5 columns of quick visibility
	 *
	 * @return array super-complex stuff
	 */
	public static function getAdminTable()
	{
		$data = [];
		$query = 'SELECT * FROM `family` ORDER BY `name` ASC';
		$result = self::_query($query);
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
		}
		foreach ($data as $idx => $item)
		{
			foreach (['parent-bio-x', 'parent-bio-y', 'parent-adopt-a', 'parent-adopt-b', 'partner'] as $column)
			{
				if ($item[$column] > 0)
				{
					$query = 'SELECT `name` FROM `family` WHERE `id`=' . $item[$column] . ' LIMIT 1';
					$result = self::_query($query);
					while ($row = mysqli_fetch_assoc($result)) {
						$data[$idx][$column] = $row['name'];
					}
				}
				else {
					$data[$idx][$column] = '-';
				}
			}
		}
		return $data;
	}
}
