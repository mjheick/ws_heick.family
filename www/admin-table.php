<?php
require_once('../lib/auth_google.php');
require_once('../lib/Auth.php');
require_once('../lib/Family.php');
require_once('../lib/Stuff.php');
/* Session Management */
$sessionData = Auth::startSession();

if (Auth::getAuthenticated() === false)
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
			<div class="row">
				<div class="col text-center">
<table class="table">
	<thead>
		<tr>
			<th>Person</th>
			<th>DOB</th>
			<th>DOD</th>
			<th>Partner</th>
			<th>Parent X<br />Adopt X</th>
			<th>Parent Y<br />Adopt Y</th>
		</tr>
	</thead>
<?php
$table = Stuff::getAdminTable();
foreach ($table as $item)
{
	echo '<tr>';
	echo '<td><a href="tree.php?id=' . $item['id'] . '" target="_blank">' . $item['name'] . '</a></td>';
	echo '<td>' . $item['dob'] . '</td>';
	echo '<td>' . $item['dod'] . '</td>';
	echo '<td>' . $item['partner'] . '</td>';

	echo '<td>';
	echo $item['parent-bio-x'] . '<br />';
	echo $item['parent-adopt-a'];
	echo '</td>';

	echo '<td>';
	echo $item['parent-bio-y'] . '<br />';
	echo $item['parent-adopt-b'];
	echo '</td>';
	echo '</tr>';
}
?>
</table>
				</div>
			</div>
<?php require_once("footer.php"); ?>
		</div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	</body>
</html>