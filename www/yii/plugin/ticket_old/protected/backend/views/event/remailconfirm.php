<br/>
<center>

<?php if ($id == 'sent')
{
	echo "<br>The email was sent<br><br>";
	echo '<button type="button" onclick="window.open(\'\', \'_self\', \'\'); window.close();">Close</button>';
}
else
{
	echo "<br>Confirm to send ticket(s) to " . $name . "<br><br>";
	echo '<button type="button" onclick="window.location.href=' . "'" . Yii::app()->createUrl('event/remailSend', array('id' => $id)) . "'" .  '">Send Email</button>';
	echo '<button type="button" onclick="window.open(\'\', \'_self\', \'\'); window.close();">Cancel</button>';
}
?>


</center>
