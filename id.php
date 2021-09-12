<?php
/*In order to display this page we need an ID */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
	header("Location: .", 302);
	die();
}
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
.border-full {
	border: 1px solid white;
}
.border-left {
	border-left: 1px solid white;
}
.border-right {
	border-right: 1px solid white;
}
.border-bottom {
	border-bottom: 1px solid white;
}

#ft-person {
	border: 1px solid white;
}
#ft-person-footer {
	border-left: 1px solid white;
	border-right: 1px solid white;
}
#ft-father-header {
	border-right: 1px solid white;
}
#ft-mother-header {
	border-left: 1px solid white;
}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col text-center"><h1>Heick Family Tree</h1></div>
			</div>
			<!-- start of a bootstrap-geared family tree diagram -->
			<div class="row"><!-- labels -->
				<div class="col text-center"><small>Father</small></div>
				<div class="col"></div>
				<div class="col text-center"><small>Mother</small></div>
			</div>
			<div class="row"><!-- People -->
				<div class="col text-center border-full">Dad Heick<br />1955 - ?</div>
				<div class="col"></div>
				<div class="col text-center border-full">Mom Heick<br /> 1954 - ?</div>
			</div>
			<div class="row"><!-- Spacing & Drawing -->
				<div class="col-2 border-right">&nbsp;</div>
				<div class="col-8"></div>
				<div class="col-2 border-left">&nbsp;</div>
			</div>
			<!-- nested to make the illusion of a border going into the middle of a square -->
			<div class="row">
				<div class="col"><!-- 2x2 grid that helps draw lines -->
					<div class="row">
						<div class="col"></div>
						<div class="col border-left border-bottom">&nbsp;</div>
					</div>
					<div class="row">
						<div class="col"></div>
					</div>
				</div>
				<div class="col text-center border-full">Me Heick<br>1981 - ?</div>
				<div class="col"><!-- 2x2 grid that helps draw lines -->
				<div class="row">
						<div class="col border-right border-bottom">&nbsp;</div>
						<div class="col"></div>
					</div>
					<div class="row">
						<div class="col"></div>
					</div>
				</div>
			</div>
			<!-- if we have kids, lets display them with the complimentary parents -->
			<div class="row"><!-- draw a connecting line -->
				<div class="col border-right">&nbsp;</div>
				<div class="col"></div>
			</div>
			<div class="row">
				<div class="col-3"></div>
				<div class="col-4 text-center border-full">Kid Heick 1 / 2003-?<br />Kid Heick 2 / 2004-?</div>
				<div class="col-1"><!-- nest for a horizontal connecting line -->
					<div class="row">
						<div class="col border-bottom">&nbsp;</div>
					</div>
					<div class="row">
						<div class="col"></div>
					</div>
				</div>
				<div class="col-3 text-center border-full">CoParent 1<br />1985 - </div>
				<div class="col-1"></div>
			</div>

			<div class="row"><!-- draw a connecting line -->
				<div class="col border-right">&nbsp;</div>
				<div class="col"></div>
			</div>
			<div class="row">
				<div class="col-3"></div>
				<div class="col-4 text-center border-full">Kid Heick 3 / 2015-?<br />Kid Heick 4 / 2016-?</div>
				<div class="col-1"><!-- nest for a horizontal connecting line -->
					<div class="row">
						<div class="col border-bottom">&nbsp;</div>
					</div>
					<div class="row">
						<div class="col"></div>
					</div>
				</div>
				<div class="col-3 text-center border-full">CoParent 2<br />1981 - </div>
				<div class="col-1"></div>
			</div>
			<hr />
			<!-- Person information -->
		 </div>
		<!-- Below needed for boostrap 4.6, per https://getbootstrap.com/docs/4.6/getting-started/introduction/ -->
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	</body>
</html>