<?php
/* Do something PHP */
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
		<title>heick.family</title>
		<style>
body, input {
	background: black;
	color: white;
}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col text-center"><h1>Heick Family Tree</h1></div>
			</div>
			<div class="row">
				<div class="col text-center">Search: <input type="text" id="search" name="search" placeholder="Enter a name to search" value="" /></div>
			</div>
			<div id="search-results" class="row">
				<div class="col"></div>
			</div>
			<div id="search-results" class="row">
				<div class="col text-center"><small>&copy;2021 heick.family</small></div>
			</div>
		 </div>
		<!-- Below needed for boostrap 4.6, per https://getbootstrap.com/docs/4.6/getting-started/introduction/ -->
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	</body>
</html>