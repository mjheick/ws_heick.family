<?php
/**
 * We should get a query called "data".
 */
$data = isset($_GET['data']) ? trim($_GET['data']) : null;
if (is_null($data)) { die(); }

/* We're ready to give a response */
header("Content-Type: application/json");

/* Lets do processing for things >= 3 characters */
if (strlen($data) < 3)
{
	$response = [];
	echo json_encode($response);
	die();
}

/* Search our names */
require_once("Classes.php");
$db = new Data();
$link = $db->getLink();
$query = 'SELECT `id`, `fullname`, `dob`, `dod` FROM `person` WHERE `fullname` LIKE "%' . $db->escape($data) . '%" LIMIT 10';
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_assoc($result))
{
	$response[] = [
		'id' => $row['id'],
		'fullname' => $row['fullname'],
		'dob' => $row['dob'],
		'dod' => $row['dod'],
	];
}

echo json_encode($response);
die();
