<?php
/*In order to display this page we need an ID */
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (is_null($id)) {
	header("Location: .", 302);
	die();
}

/* Get ready for database-related fun */
require_once("Data.php");
$db = new Data();
$link = $db->getLink();

/* We have an ID. We need to find us, parents, children + childrens other parents */
/* pretty much what exists for the person in the person table */
$me = null;
$query = 'SELECT * FROM `person` WHERE `id`=' . $id . ' LIMIT 1';
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_assoc($result)) {
	$me = $row;
}
if (is_null($me)) {
	header("Location: .", 302);
	die();
}

/* 2 or more indexes. mainly gonna use 0 and 1 */
$parent = [];
$query = 'SELECT * FROM `parents` LEFT JOIN `person` ON `parent`=`id` WHERE `person`=' . $id . ' LIMIT 2';
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_assoc($result)) {
	$parent[] = $row;
}

/* Each "other parent" is the key, and the children that share are the values */
/* Start by getting all my kids, first born to last born */
$child = []; /* Kid data storage */
$kids = []; /* used for imploding later. ha, if we could only implode kids sometimes... */
$query = 'SELECT * FROM `parents` LEFT JOIN `person` ON `person`=`id` WHERE `parent`=' . $id . ' ORDER BY `dob`';
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_assoc($result)) {
	$child[$row['id']] = $row;
	$kids[] = $row['person'];
}
/* If we have kids, continue on, else book outta here and start displaying */
$coparent = [];
if (count($kids) > 0) {
	/* Get other parents of above kids. 90% of the time it'll be one person, but sometimes it could be 30 */
	$query = 'SELECT * FROM `parents` LEFT JOIN `person` ON `parent`=`id` WHERE `person` IN (' . implode(',', $kids) . ') AND NOT (`parent` = ' . $id . ')';
	$result = mysqli_query($link, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		if (!isset($coparent[$row['parent']])) {
			$coparent[$row['parent']] = [
				'person' => $row,
				'kids' => []
			];
		}
		$coparent[$row['parent']]['kids'][] = $row['person'];
	}
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
<?php require_once("header.php"); ?>
			<!-- start of a bootstrap-geared family tree diagram -->
			<div class="row"><!-- labels -->
				<div class="col text-center"><small>Parent</small></div>
				<div class="col"></div>
				<div class="col text-center"><small>Parent</small></div>
			</div>
			<div class="row"><!-- People -->
				<div class="col text-center border-full"><?php
if (isset($parent[0])) {
	echo '<a href="tree.php?id=' . $parent[0]['id'] . '">' . $parent[0]['fullname'] . '</a><br />';
	$dob = substr($parent[0]['dob'], 0, 4);
	if ($dob == '0000') { echo '?'; } else { echo $dob; }
	echo ' - ';
	$dod = substr($parent[0]['dod'], 0, 4);
	if ($dob == '0000') { echo '?'; } else { echo $dod; }
} else {
	echo '?';
}
?></div>
				<div class="col"></div>
				<div class="col text-center border-full"><?php
if (isset($parent[1])) {
	echo '<a href="tree.php?id=' . $parent[1]['id'] . '">' . $parent[1]['fullname'] . '</a><br />';
	$dob = substr($parent[1]['dob'], 0, 4);
	if ($dob == '0000') { echo '?'; } else { echo $dob; }
	echo ' - ';
	$dod = substr($parent[1]['dod'], 0, 4);
	if ($dob == '0000') { echo '?'; } else { echo $dod; }
} else {
	echo '?';
}
?></div>
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
				<div class="col text-center border-full"><?php
echo $me['fullname'] . '<br />';
$dob = substr($me['dob'], 0, 4);
if ($dob == '0000') { echo '?'; } else { echo $dob; }
echo ' - ';
$dod = substr($me['dod'], 0, 4);
if ($dob == '0000') { echo '?'; } else { echo $dod; }
?></div>
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
<?php
/* If we have kids, we display them with the identified parent */
if (count($kids) > 0) { /* Start, kid/coparent logic */
	foreach ($coparent as $key => $cop_data)
	{
		/* Display is simple: We need a list of kids and a coparent */
		$display_kids = '';
		$display_coparent = '';
		foreach ($cop_data['kids'] as $kid_index) {
			if (strlen($display_kids) > 0) { $display_kids .= '<br />'; }
			$display_kids .= '<a href="tree.php?id=' . $kid_index . '">' . $child[$kid_index]['fullname'] . '</a> / ';
			$dob = substr($child[$kid_index]['dob'], 0, 4);
			if ($dob == '0000') { $display_kids .= '?'; } else { $display_kids .= $dob; }
			$display_kids .= ' - ';
			$dod = substr($child[$kid_index]['dod'], 0, 4);
			if ($dob == '0000') { $display_kids .= '?'; } else { $display_kids .= $dod; }
		}
		$display_coparent .= '<a href="tree.php?id=' . $cop_data['person']['id'] . '">' . $cop_data['person']['fullname'] . '</a><br />';
		$dob = substr($cop_data['person']['dob'], 0, 4);
		if ($dob == '0000') { $display_coparent .= '?'; } else { $display_coparent .= $dob; }
		$display_coparent .= ' - ';
		$dod = substr($cop_data['person']['dod'], 0, 4);
		if ($dob == '0000') { $display_coparent .= '?'; } else { $display_coparent .= $dod; }
?>
			<div class="row"><!-- draw a connecting line -->
				<div class="col border-right">&nbsp;</div>
				<div class="col"></div>
			</div>
			<div class="row">
				<div class="col-3"></div>
				<div class="col-4 text-center border-full"><?php echo $display_kids; ?></div>
				<div class="col-1"><!-- nest for a horizontal connecting line -->
					<div class="row">
						<div class="col border-bottom">&nbsp;</div>
					</div>
					<div class="row">
						<div class="col"></div>
					</div>
				</div>
				<div class="col-3 text-center border-full"><?php echo $display_coparent; ?></div>
				<div class="col-1"></div>
			</div>
<?php
	}
} /* end, kid/coparent logic */

/* If we have media lets display it */
?>
			<hr />
			<div class="row">
				<div class="col text-center">
				</div>
			</div>
			<hr />
			<!-- upload media for this person -->
<?php require_once("footer.php"); ?>
		</div>
		<!-- Below needed for boostrap 4.6, per https://getbootstrap.com/docs/4.6/getting-started/introduction/ -->
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	</body>
</html>
