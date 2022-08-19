<?php

class Auth
{
	private static $sessionData = [
		'authenticated' => false,
		'idp' => '',
		'user' => '',
	];

	public static function startSession()
	{
		if (session_start() === false)
		{
			throw Exception('cannot start a session, so we cannot properly secure administration');
		}
		if (isset($_SESSION))
		{
			foreach ($_SESSION as $key => $value)
			{
				if (isset(self::$sessionData[$key]))
				{
					self::$sessionData[$key] = $value;
				}
			}
		}
	}

	public static function setIdP($idp)
	{
		self::$sessionData['idp'] = $idp;
	}

	public static function setUser($user)
	{
		self::$sessionData['user'] = $user;
	}

	public static function getIdentity()
	{
		$id = '';
		if (self::$sessionData['idp'] == '')
		{
			$id = 'unset';
		}
		else
		{
			$id = self::$sessionData['idp'];
		}
		$id .= ':';
		if (self::$sessionData['user'] == '')
		{
			$id .= 'unset';
		}
		else
		{
			$id .= self::$sessionData['user'];
		}
		return $id;
	}

	public static function getAuthenticated()
	{
		return self::$sessionData['authenticated'];
	}

	public static function setAuthenticated($status = false)
	{
		self::$sessionData['authenticated'] = $status;
	}

	public static function endSession()
	{
		foreach (self::$sessionData as $key => $value)
		{
			$_SESSION[$key] = $value;
		}
		session_write_close();
	}
}