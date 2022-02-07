<?php
require_once("Family.php");

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

/* Get the main Lineage handled */
$parentx = Family::getPerson($me['parent-x']);
$parenty = Family::getPerson($me['parent-y']);
$lineage = [
	'me' => ['id' => $me['id'], 'name' => Family::formatName($me)],
	'partner' => ['id' => $me['partner'], 'name' => Family::formatName(Family::getPerson($me['partner']))],
	'px' => ['id' => $me['parent-x'], 'name' => Family::formatName(Family::getPerson($me['parent-x']))],
	'py' => ['id' => $me['parent-y'], 'name' => Family::formatName(Family::getPerson($me['parent-y']))],
	'pxgx' => ['id' => $parentx['parent-x'], 'name' => Family::formatName(Family::getPerson($parentx['parent-x']))],
	'pxgy' => ['id' => $parentx['parent-y'], 'name' => Family::formatName(Family::getPerson($parentx['parent-y']))],
	'pygx' => ['id' => $parenty['parent-x'], 'name' => Family::formatName(Family::getPerson($parenty['parent-x']))],
	'pygy' => ['id' => $parenty['parent-y'], 'name' => Family::formatName(Family::getPerson($parenty['parent-y']))],
];
function showMember($l)
{
	if ($l['id'] == 0)
	{
		echo $l['name'];
	}
	else
	{
		echo '<a href="?id=' . $l['id'] . '">' . $l['name'] . '</a>';
	}
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
			<!-- using non-functional nav tabs as graphical visual separators -->
			<div class="row">
				<div class="col">
					<ul class="nav nav-tabs justify-content-center">
						<li class="nav-item">
							<a class="nav-link active" id='tab-lineage' href="#">Lineage</a>
						</li>
					</ul>
				</div>
			</div>
			<!-- Start: Lineage -->
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div class="col-4">&nbsp;</div>
				<div class="col-4">&nbsp;</div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div class="col-4">&nbsp;</div>
				<div title="grandparent" class="col-4 text-center border-full background-black"><?php showMember($lineage['pxgx']); ?></div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div class="col-4">&nbsp;</div>
				<div class="col-4 border-left">&nbsp;</div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div title="parent" class="col-4 text-center border-full background-black"><?php showMember($lineage['px']); ?></div>
				<div class="col-4 border-left">&nbsp;</div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div class="col-4 border-left">&nbsp;</div>
				<div class="col-4 border-left">&nbsp;</div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div class="col-4 border-left">&nbsp;</div>
				<div title="grandparent" class="col-4 text-center border-full background-black"><?php showMember($lineage['pxgy']); ?></div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div class="col-4 border-left">&nbsp;</div>
				<div class="col-4">&nbsp;</div>
			</div>
			<div class="row">
				<div title="self" class="col-4 text-center border-full background-black"><?php showMember($lineage['me']); ?></div>
				<div class="col-4 border-left">&nbsp;</div>
				<div class="col-4">&nbsp;</div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div class="col-4 border-left">&nbsp;</div>
				<div class="col-4">&nbsp;</div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div class="col-4 border-left">&nbsp;</div>
				<div title="grandparent" class="col-4 text-center border-full background-black"><?php showMember($lineage['pygx']); ?></div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div class="col-4 border-left">&nbsp;</div>
				<div class="col-4 border-left">&nbsp;</div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div title="parent" class="col-4 text-center border-full background-black"><?php showMember($lineage['py']); ?></div>
				<div class="col-4 border-left">&nbsp;</div>
			</div>
			<div class="row">
				<div class="col-4">&nbsp;</div>
				<div class="col-4">&nbsp;</div>
				<div class="col-4 border-left">&nbsp;</div>
			</div>
			<div class="row">
				<?php
if ($lineage['partner']['id'] == 0)
{
	echo '<div class="col-4">&nbsp;</div>';
}
else
{
	echo '<div title="partner" class="col-4 text-center border-full background-black">';
	showMember($lineage['partner']);
	echo '</div>';
}
				?>
				<div class="col-4">&nbsp;</div>
				<div title="grandparent" class="col-4 text-center border-full background-black"><?php showMember($lineage['pygy']); ?></div>
			</div>
			<div class="row">
				<div class="col-12">&nbsp;</div>
			</div>
			<!-- End: Lineage -->
<?php
$children = Family::getChildren($id);
if (count($children) > 0)
{ /* Start of children block */
?>
			<ul class="nav nav-tabs justify-content-center">
				<li class="nav-item">
					<a class="nav-link active" id='tab-children' href="#">Children</a>
				</li>
			</ul>
			<div class="row">
				<div class="col"><pre><?php var_export($children); ?></div>
			</div>
<?php
} /* End of children block */
?>
<?php
$siblings = Family::getSiblings($id);
if (count($siblings) > 0)
{ /* Start of siblings block */
?>
			<ul class="nav nav-tabs justify-content-center">
				<li class="nav-item">
					<a class="nav-link active" id='tab-siblings' href="#">Siblings</a>
				</li>
			</ul>
			<div class="row">
				<div class="col">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">Full</th>
								<th scope="col">Half</th>
								<th scope="col">Step</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td scope="col">None</td>
								<td scope="col">None</td>
								<td scope="col">None</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
<?php
} /* End of siblings block */
?>
<?php require_once("footer.php"); ?>
		</div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	</body>
</html>
