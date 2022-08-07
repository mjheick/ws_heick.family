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
		<link rel="stylesheet" href="/media/admin.css">
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
		<div class="row">
			<!-- list of people, and add new button -->
			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Select One:</span>
				</div>
				<select class="form-control"><option>First Middle Last</option></select>
			</div>

			<div class="col-6 text-center"><button class="btn btn-primary">Add New</button></div>
		</div>
		<!-- a list of data -->
	<div id="workspace"><!-- hidden until needed -->
		<div class="row">
			<div class="col-12"><hr /></div>
		</div>
		<!-- name    | parent x -->
		<!-- dob     | parent y -->
		<!-- dod     | adopted x -->
		<!-- partner | adopted y -->
		<div class="row">
			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Name:</span>
				</div>
				<input type="text" class="form-control" placeholder="Name" />
			</div>

			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Parent X:</span>
				</div>
				<select class="form-control"><option>First Middle Last</option></select>
			</div>
		</div>

		<div class="row">
			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Date Of Birth:</span>
				</div>
				<input type="date" class="form-control" />
			</div>

			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Parent Y:</span>
				</div>
				<select class="form-control"><option>First Middle Last</option></select>
			</div>
		</div>

		<div class="row">
			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Date Of Death:</span>
				</div>
				<input type="date" class="form-control" />
			</div>

			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Adopted X:</span>
				</div>
				<select class="form-control"><option>First Middle Last</option></select>
			</div>
		</div>

		<div class="row">
			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Partner:</span>
				</div>
				<select class="form-control"><option>First Middle Last</option></select>
			</div>

			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Adopted Y:</span>
				</div>
				<select class="form-control"><option>First Middle Last</option></select>
			</div>
		</div>

		<div class="row">
			<div class="col-12"><hr /></div>
		</div>

		<!-- buttons -->
		<div class="row">
			<div class="col-6 text-center"><button class="btn btn-primary">Save</button></div>
			<div class="col-6 text-center"><button class="btn btn-info">Cancel</button></div>
		</div>
	</div><!-- end of workspace -->
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