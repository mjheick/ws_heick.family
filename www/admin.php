<?php
require_once('../lib/auth_google.php');
require_once('../lib/Auth.php');
require_once('../lib/Family.php');
/* Session Management */
$sessionData = Auth::startSession();

/**
 * Handle inbound GET requests to this page.
 */
if (AuthGoogle::isOAuth())
{
	$user = AuthGoogle::handleOAuth();
	if ($user !== false)
	{
		Auth::setIdP('google');
		Auth::setUser($user['id']);
		Auth::setAuthenticated(true);
	}
	Auth::endSession();
	/* At the end of the oAuth flow we should redirect back to Admin for the session to take effect */
	header('Location: https://heick.family/admin.php?auth=ok-google', 302);
	die();
}

/* Test if we're allowed to use this page or not. Simple true/false flag for "admin" */
$admin = false;
if (Auth::getAuthenticated() === true)
{
	$config = json_decode(file_get_contents('../conf/configuration.json'), true);
	$admins = $config['admins'];
	$id = Auth::getIdentity();
	if (in_array($id, $admins))
	{
		$admin = true;
	}
}

/**
 * Handle inbound POST requests from this page, only if authenticated
 */
if ((Auth::getAuthenticated() === true) && ($_SERVER['REQUEST_METHOD'] == 'POST') && $admin) {
	/* Attempt to get JSON POSTed DATA */
	$data = file_get_contents('php://input');
	if ($data !== false)
	{
		$data = json_decode($data, true);
		if (!isset($data['action'])) { die(json_encode([]));}
		if ($data['action'] == 'getpeople')
		{
			$members = Family::getAllFamily();
			$output = [];
			foreach ($members as $id => $name)
			{
				$output[] = ["id" => $id, "name" => $name];
			}
			echo json_encode($output);
		}
		if ($data['action'] == 'getperson')
		{
			$lineage = Family::getPerson($data['id']);
			$data = [
				'id' => $data['id'],
				'name' => $lineage['name'],
				'dob' => $lineage['dob'],
				'dod' => $lineage['dod'],
				'partner' => $lineage['partner'],
				'parentx' => $lineage['parent-bio-x'],
				'parenty' => $lineage['parent-bio-y'],
				'adoptx' => $lineage['parent-adopt-a'],
				'adopty' => $lineage['parent-adopt-b'],
			];
			echo json_encode($data);
		}
		if ($data['action'] == 'updateperson')
		{
			$response = Family::modify($data);
			echo json_encode($response);
		}
		die();
	}
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
<?php
if ((Auth::getAuthenticated() === true) && $admin) { /* Start of Administrative Javascript */
?>
		<script>
let Family = [];

function hookActions()
{
	document.getElementById('btn_addnew').addEventListener('click', btnAddNew);
	document.getElementById('btn_save').addEventListener('click', btnSave);
	document.getElementById('btn_cancel').addEventListener('click', btnCancel);
	document.getElementById('select_one').addEventListener('change', loadPerson);
	loadPeople();
}

function setupPage()
{
	/* Dropdowns dropdown */
	let dropdowns = ['select_one', 'parent_x', 'parent_y', 'adopted_x', 'adopted_y', 'partner'];
	for (y = 0; y < dropdowns.length; y++)
	{

		let d = document.getElementById(dropdowns[y]);
		/* clear the options */
		d.innerHTML = '';
		let o = document.createElement("option");
		o.value = '';
		o.text = '';
		d.add(o);
		for (let x = 0; x < Family.length; x++)
		{
			o = document.createElement("option");
			o.value = Family[x].id;
			o.text = Family[x].name;
			d.add(o);
		}
	}
	document.getElementById('current_id').value = '';
	document.getElementById('name').value = '';
	document.getElementById('date_of_birth').value = '';
	document.getElementById('date_of_death').value = '';
	document.getElementById('select_one').disabled = false;
	document.getElementById('btn_addnew').disabled = false;
}

function loadPeople()
{
	let data = {"action": "getpeople"};
	let xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200))
		{
			Family = JSON.parse(xhr.responseText);
			setupPage();
		}
	};
	xhr.open('POST', 'admin.php', true);
	xhr.send(JSON.stringify(data));
}

function loadPerson() /* executed from select_one::change */
{
	let o = document.getElementById('select_one');
	let idx = o.options[o.selectedIndex].value;
	if (idx.length == 0) { return; }

	let data = {"action": "getperson", "id": idx};

	let xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			let people = JSON.parse(xhr.responseText);
			/* Fill in the blanks of the form */
			document.getElementById('name').value = people.name;
			if (people.dob == '0000-00-00')
			{
				document.getElementById('date_of_birth').value = '';
			}
			else
			{
				document.getElementById('date_of_birth').value = people.dob;
			}
			if (people.dod == '0000-00-00')
			{
				document.getElementById('date_of_death').value = '';
			}
			else
			{
				document.getElementById('date_of_death').value = people.dod;
			}
			/* Must match what's selected */
			let dropdown_selectors = [
				{ 'element': 'partner', 'value': people.partner },
				{ 'element': 'parent_x', 'value': people.parentx },
				{ 'element': 'parent_y', 'value': people.parenty },
				{ 'element': 'adopted_x', 'value': people.adoptx },
				{ 'element': 'adopted_y', 'value': people.adopty },
			];
			for (d = 0; d < dropdown_selectors.length; d++)
			{
				if (dropdown_selectors[d].value == 0)
				{
					document.getElementById(dropdown_selectors[d].element).selectedIndex = 0;
				}
				else
				{
					for (let s = 0; s < document.getElementById(dropdown_selectors[d].element).options.length; s++)
					{
						if (document.getElementById(dropdown_selectors[d].element).options[s].value == dropdown_selectors[d].value)
						{
							document.getElementById(dropdown_selectors[d].element).options[s].selected = true;
						}
					}
				}
			}
			document.getElementById('current_id').value = people.id;
			document.getElementById('workspace').style.display = 'block';
		}
	};
	xhr.open('POST', 'admin.php', true);
	xhr.send(JSON.stringify(data));
}

function btnCancel()
{
	document.getElementById('workspace').style.display = 'none';
	setupPage();
}

function btnAddNew()
{
	setupPage();
	document.getElementById('select_one').disabled = true;
	document.getElementById('workspace').style.display = 'block';
}

function btnSave()
{
	/* quick check for "valid" data */
	if (document.getElementById('current_id').value == '' && document.getElementById('name').value == '')
	{
		return;
	}
	/* Disable everything until we get a response */
	document.getElementById('select_one').disabled = true;
	document.getElementById('btn_addnew').disabled = true;
	document.getElementById('workspace').style.display = 'none';

	let xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			let response = JSON.parse(xhr.responseText);
			if (response.status == 'OK')
			{
				loadPeople();
			}
			if (response.status == 'error')
			{
				alert('some problem occured...');
				document.getElementById('workspace').style.display = 'block';
			}
		}
	};
	let data = {
		'action': 'updateperson',
		'id': document.getElementById('current_id').value,
		'name': document.getElementById('name').value,
		'dob': document.getElementById('date_of_birth').value,
		'dod': document.getElementById('date_of_death').value,
		'partner': document.getElementById('partner').options[document.getElementById('partner').selectedIndex].value,
		'parentx': document.getElementById('parent_x').options[document.getElementById('parent_x').selectedIndex].value,
		'parenty': document.getElementById('parent_y').options[document.getElementById('parent_y').selectedIndex].value,
		'adoptx': document.getElementById('adopted_x').options[document.getElementById('adopted_x').selectedIndex].value,
		'adopty': document.getElementById('adopted_y').options[document.getElementById('adopted_y').selectedIndex].value,
	};

	xhr.open('POST', 'admin.php', true);
	xhr.send(JSON.stringify(data));
}


window.onload = hookActions;
		</script>
<?php
} /* End of Administrative Javascript */
?>
	</head>
	<body>
		<div class="container">
<?php require_once("header.php"); ?>
<?php
if (Auth::getAuthenticated() === false)
{ /* Start of authenticated=false block */
?>
		<div class="row">
			<div class="col text-center">
				<button class="" onclick="document.location='<?php echo AuthGoogle::getAuthURL(); ?>';">Log in with Google</button>
			</div>
		</div>
<?php
} /* End of authenticated=false block */
if ((Auth::getAuthenticated() === true) && !$admin)
{ /* Start of authenticated=true but not admin */
?>
		<div class="row">
			<div class="col">
				<p class="text-center">
				You're logged in but you're currently not allowed to administrate this area. If you'd like to administrate this area please email <span class="font-weight-bold">heick.family.admin@heick.email</span> with a request to adminstrate and provide <span class="font-weight-bold"><?php echo Auth::getIdentity(); ?></span>
			</p>
			</div>
		</div>
<?php
} /* End of authenticated=true but not admin */
if ((Auth::getAuthenticated() === true) && $admin)
{ /* Start of authenticated=true and admin block */
?>
		<div class="row">
			<!-- list of people, and add new button -->
			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Select One:</span>
				</div>
				<select id="select_one" class="form-control"><option>First Middle Last</option></select>
			</div>

			<div class="col-6 text-center"><button id='btn_addnew' class="btn btn-primary">Add New</button></div>
		</div>
		<!-- a list of data -->
	<div id="workspace"><!-- hidden until needed -->
		<input id="current_id" type="hidden" value="" />
		<div class="row">
			<div class="col-12"><hr /></div>
		</div>
		<!-- name    | parent x -->
		<!-- dob     | parent y -->
		<!-- dod     | adopted x -->
		<!-- partner | adopted y -->
		<div class="row">
			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Name:</span>
				</div>
				<input id="name" type="text" class="form-control" placeholder="Name" />
			</div>

			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Parent X:</span>
				</div>
				<select id="parent_x" class="form-control"><option>First Middle Last</option></select>
			</div>
		</div>

		<div class="row">
			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Date Of Birth:</span>
				</div>
				<input id="date_of_birth" type="date" class="form-control" />
			</div>

			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Parent Y:</span>
				</div>
				<select id="parent_y" class="form-control"><option>First Middle Last</option></select>
			</div>
		</div>

		<div class="row">
			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Date Of Death:</span>
				</div>
				<input id="date_of_death" type="date" class="form-control" />
			</div>

			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Adopted X:</span>
				</div>
				<select id="adopted_x" class="form-control"><option>First Middle Last</option></select>
			</div>
		</div>

		<div class="row">
			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Partner:</span>
				</div>
				<select id="partner" class="form-control"><option>First Middle Last</option></select>
			</div>

			<div class="input-group col-6">
				<div class="iput-group-prepend">
					<span class="input-group-text">Adopted Y:</span>
				</div>
				<select id="adopted_y" class="form-control"><option>First Middle Last</option></select>
			</div>
		</div>

		<div class="row">
			<div class="col-12"><hr /></div>
		</div>

		<!-- buttons -->
		<div class="row">
			<div class="col-6 text-center"><button id='btn_save' class="btn btn-primary">Save</button></div>
			<div class="col-6 text-center"><button id='btn_cancel' class="btn btn-info">Cancel</button></div>
		</div>
	</div><!-- end of workspace -->
<?php
} /* End of authenticated=true and admin block */
?>
<?php require_once("footer.php"); ?>
		 </div>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	</body>
</html>