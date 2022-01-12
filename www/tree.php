<?php
require_once("Classes.php");

/* In order to display this page we need an ID */
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (is_null($id)) {
	header("Location: /", 302);
	die();
}

/* Lets find whatever the ID is in the database */
$me = Family::getPerson($id);
if (is_null($me)) {
	header("Location: /", 302);
	die();
}

?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
		<title>heick.family</title>
		<link rel="stylesheet" href="/media/theme.css">
		<link rel="stylesheet" href="/media/tree.css">
	</head>
	<body>
		<div class="container">
<?php require_once("header.php"); ?>
			<!-- start of a bootstrap-geared family tree diagram -->
			<!-- start off with 3 tabs in our nav bar: lineage, children, and siblings -->
			<ul class="nav nav-tabs justify-content-center">
				<li class="nav-item">
					<a class="nav-link" id='tab-lineage' href="#">Lineage</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id='tab-children' href="#">Children</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id='tab-siblings' href="#">Siblings</a>
				</li>
				<li class="nav-item">
					<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Nothing</a>
				</li>
			</ul>
			<div class="row">
				<div class="col" id="loading-area">xxx</div>
			</div>
<?php require_once("footer.php"); ?>
		</div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
		<script>
/* Some Javascript to make your day happy */
let person = <?php echo $id; ?>;
function loadTab(tab) {
	/* Definitions */
	let tabs = ["lineage", "children", "siblings"];
	/* reset the tabs */
	for (t = 0; t < tabs.length; t++) {
		$("#tab-" + tabs[t]).removeClass("active");
	}
	$("#tab-" + tab).addClass("active");
	/* Blank out the area */
	$("#loading-area").html( showSpinner() );
	$.ajax({
		url: "tree-data.php",
		data: {
			id: person,
			data: tab
		},
		dataType: "json"
	}).done(function(j) {
		$("#loading-area").html(j.data);
	});
}
function showSpinner() {
	return '<div class="spinner-border m-5" role="status"><span class="sr-only">Loading...</span></div>';
}
$(document).ready(function() {
	$("#tab-lineage").on("click", function(){loadTab("lineage");});
	$("#tab-children").on("click", function(){loadTab("children");});
	$("#tab-siblings").on("click", function(){loadTab("siblings");});
	loadTab("lineage");
});
		</script>
	</body>
</html>