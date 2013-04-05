<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>Untitled Page</title>
    <link href="CSS/StyleSheet.css" rel="stylesheet" type="text/css" />
<center>Should you have a query or experience any difficulty booking your tickets please <a href="mailto:info@insightKLG.co.uk?Subject=Booking%20enquiry">contact us </a></center>
<br>
</head>

<body<?= $BodyAttributes ?>>
	<div style="width:<?= $Width ?>px;margin:auto">
    		<form name="Form" action="<?= $FormAction ?>" method="post"<?= $FormAttributes ?>>
