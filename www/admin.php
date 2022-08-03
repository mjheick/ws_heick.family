<?php
require_once('../lib/auth_google.php');
require_once('../lib/Auth.php');
/* Session Management */
$sessionData = Auth::startSession();

/**
 * Handle inbound GET requests to this page.
 */
if (AuthGoogle::isOAuth())
{
	$user = AuthGoogle::handleOAuth();
	if ($user !== false)
	{
		Auth::setIdP('google');
		Auth::setUser($user['id']);
		Auth::setAuthenticated(true);
	}
	Auth::endSession();
	/* At the end of the oAuth flow we should redirect back to Admin for the session to take effect */
	header('Location: https://heick.family/admin.php?auth=ok-google', 302);
	die();
}

/**
 * Handle inbound POST requests from this page
 */
if (isset($_POST['action']) && isset($_POST['method']) && isset($_POST['data']))
{
	die();
}
Auth::endSession();
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
		<link rel="stylesheet" href="/media/theme.css">
		<title>heick.family</title>
	</head>
	<body>
		<div class="container">
<?php require_once("header.php"); ?>
<?php
if (Auth::getAuthenticated() === false)
{ /* Start of authenticated=false block */
?>
		<div class="row">
			<div class="col text-center">
				<button class="" onclick="document.location='<?php echo AuthGoogle::getAuthURL(); ?>';">Log in with Google</button>
			</div>
		</div>
<?php
} /* End of authenticated=false block */
if (Auth::getAuthenticated() === true)
{ /* Start of authenticated=true block */
?>
		Do authenticated stuff
<?php
} /* End of authenticated=true block */
?>
<?php require_once("footer.php"); ?>
		 </div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	</body>
</html>