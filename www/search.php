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
require_once("Family.php");
$results = Family::Search($data);
$response = [];
foreach ($results as $item)
{
	$response[] = [
		'id' => $item['id'],
		'fullname' => $item['name'],
		'dob' => $item['dob'],
		'dod' => $item['dod']
	];
}

echo json_encode($response);
die();
