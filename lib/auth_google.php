<?php
/**
 * https://console.cloud.google.com/apis/api/identitytoolkit.googleapis.com/credentials?project=heick-family-website
 * https://cloud.google.com/docs/authentication
 * https://developers.google.com/identity/protocols/oauth2/web-server#httprest_1
 * https://developers.google.com/identity/protocols/oauth2/scopes
 */

class AuthGoogle
{
	private static $config = [
		'scopes' => [
			'https://www.googleapis.com/auth/userinfo.email',
			'https://www.googleapis.com/auth/userinfo.profile',
			'openid',
		],
		'project_id' => 'heick-family-website',
		'client_id' => '898332180753-9j46esr5mqo1a0499t3l1s8vpmt71sd6.apps.googleusercontent.com',
		'client_secret' => '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',
		'redirect_uri' => 'https://heick.family/admin.php',
		'oauth_client_json' => '{"web":{"client_id":"898332180753-9j46esr5mqo1a0499t3l1s8vpmt71sd6.apps.googleusercontent.com","project_id":"heick-family-website","auth_uri":"https://accounts.google.com/o/oauth2/auth","token_uri":"https://oauth2.googleapis.com/token","auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs","client_secret":"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","redirect_uris":["https://heick.family/admin.php"]}}',
	];

	public static function isOAuth()
	{
		/* This checks if we have $_GET stuff passed in from Google's API. */
		if (isset($_GET['code']))
		{
			return true;
		}
		if (isset($_GET['error']))
		{
			return true;
		}
		return false;
	}

	public static function handleOAuth()
	{
		/* We should be in an oAuth flow, so we should be able to do what's needed to get information */
		if (isset($_GET['error'])) /* We got an error, do nothing */
		{
			return false;
		}
		if (!isset($_GET['code'])) /* We do not have a code, do nothing */
		{
			return false;
		}
		$code = $_GET['code'];
		$json_config = json_decode(self::$config['oauth_client_json'], true);
		$params = http_build_query([
			'client_id' => $json_config['web']['client_id'],
			'client_secret' => $json_config['web']['client_secret'],
			'code' => $code,
			'grant_type' => 'authorization_code',
			'redirect_uri' => $json_config['web']['redirect_uris'][0],
		]);
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => $json_config['web']['token_uri'],
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $params,
		]);
		$data = curl_exec($ch);
		curl_close($ch);

		/* Verify that we have an access token to make subsequent authorization calls */
		$json_data = json_decode($data, true);
		if ($json_data === null)
		{
			return false; /* Some reason we couldn't parse, so we can't do anything */
		}
		if (!isset($json_data['access_token']))
		{
			return false; /* No access token, can't do anything */
		}
		$access_token = $json_data['access_token'];
		/* Either make a call to /oauth2/v2/userinfo or to /userinfo/v2/me to get same data */
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => 'https://www.googleapis.com/oauth2/v2/userinfo',
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: Bearer ' . $access_token,
			],
		]);
		$data = curl_exec($ch);
		curl_close($ch);

		/* See if we have 'id', the minimum we care about */
		$json_data = json_decode($data, true);
		if ($json_data === null)
		{
			return false;
		}
		if (!isset($json_data['id']))
		{
			return false;
		}
		return $json_data;
	}

	public static function getAuthURL()
	{
		$json_config = json_decode(self::$config['oauth_client_json'], true);
		$params = http_build_query([
			'client_id' => $json_config['web']['client_id'],
			'redirect_uri' => $json_config['web']['redirect_uris'][0],
			'response_type' => 'code',
			'scope' => implode(' ', self::$config['scopes']),
			'prompt' => 'select_account',
		]);
		return $json_config['web']['auth_uri'] . '?' . $params;
	}
}