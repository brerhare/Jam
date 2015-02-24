<?php
	// Save SMS for later sending
	$number = $_POST['number'];
	$message = $_POST['message'];
	$file = "./data/sms-" . rand(100000000, 999999999);
	file_put_contents($file, "To: " . $number . "\n\n" . $message);
?>

<html>
<style>
* {
    font-family: Arial, "Times New Roman", Times, serif;
	font-size: 16px;
	margin:5px;
}
input[type=button] {
	padding:5px 15px; background:#ccc; border:0 none;
	cursor:pointer;
	background-color: #67a2cc;
	-webkit-border-radius: 3px;
	border-radius: 3px;
}
</style>

SMS Sent
<input type = 'button' value='ok' onClick='goback()'>
<script>
function goback() { window.location.replace('http://test.wireflydesign.com/smstest/sms.html');}
</script>
</html>

