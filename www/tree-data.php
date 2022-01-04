<?php
require_once("Classes.php");
$json = ['data' => '<p>No data to display</p>'];
/* In order to display this page we need an ID */
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (is_null($id)) { die(json_encode($json)); }
/* We need to see if we're asking for legit info */
$data = isset($_GET['data']) ? strtolower(trim($_GET['data'])) : null;
if (is_null($data)) { die(json_encode($json)); }
if (!in_array($data, ['lineage', 'children', 'siblings'])) { die(json_encode($json)); }
/* Lets find whatever the ID is in the database */
$me = Family::getMe($id);
if (is_null($me)) { die(json_encode($json)); }

/* Execute based on what data is being requested */
if ($data == 'lineage') {
	
}

if ($data == 'children') {
	
}

if ($data == 'siblings') {
	
}

$json = ['data' => '<p>I love this</p>'];
echo json_encode($json);