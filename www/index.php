<?php
/* Do something PHP */
require_once('../lib/Stuff.php');
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
		<link rel="stylesheet" href="media/theme.css">
		<title>heick.family</title>
	</head>
	<body>
		<div class="container">
<?php require_once("header.php"); ?>

			<div class="row">
				<div class="input-group">
					<div class="iput-group-prepend">
						<span class="input-group-text">Enter a name to search:</span>
					</div>
					<input class="form-control" type="text" id="search" name="search" placeholder="Enter 3 or more letters to search" value="" />
				</div>
			</div>
			
			<div id="search-results" class="row">
				<div class="col"></div>
			</div>
			<hr />
			<div class="row">
				<div class="col text-center"><h3>Recent Entries/Edits</h2></div>
			</div>
<?php
$recent = Stuff::getLastModified();
foreach ($recent as $value)
{
	echo "\t\t\t" . '<div class="row"><div class="col text-center"><a href="tree.php?id=' . $value['id'] . '">' . $value['name'] . '</a></div></div>' . "\n";
}
?>
<?php require_once("footer.php"); ?>
		 </div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
		<script src="media/index.js"></script>
	</body>
</html>
