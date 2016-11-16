<?php
if (isset($_FILES['upload_file'])) {
	$root = $_SERVER["DOCUMENT_ROOT"];
	if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $root . "/jam/uploads/forms/referral/" .  $_FILES['upload_file']['name']  )){
		echo $_FILES['upload_file']['name']. " OK";
	} else {
		echo $_FILES['upload_file']['name']. " KO";
	}
	exit;
} else {
	echo "No files uploaded ...";
}
?>

