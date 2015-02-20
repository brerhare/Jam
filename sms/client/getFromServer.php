<?php 

$files = array();
$pollInterval = 5;
$serverHttpAddress = "http://test.wireflydesign.com/smstest";
$messageHeading = "From Wirefly: ";

while (true)
{
	// Call for list of files
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, "http://test.wireflydesign.com/smstest/smsList.php"); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);			//return the transfer as a string 
	$output = curl_exec($ch); 
	curl_close($ch);      
	
	$output = trim($output, "\n");
	if ($output)
	{
		// Retrieve files one by one
		echo "\n";
		$files = explode("\n", $output);
		foreach ($files as $file)
		{
			echo "Retrieving $file from server. ";
			$defaults = array( 
				CURLOPT_URL => $serverHttpAddress . "/smsCat.php/?" . http_build_query(array("file"=>"$file")), 
				CURLOPT_HEADER => 0, 
				CURLOPT_RETURNTRANSFER => TRUE, 
				CURLOPT_TIMEOUT => 4 
			); 
			$ch = curl_init();
			curl_setopt_array($ch, ($defaults));
			$output = curl_exec($ch);
			curl_close($ch);
			if ($output)				// Successfully retrieved the file
			{
				echo "Success. Removing file from server\n";

				$sms = str_replace_nth("\n", "\n$messageHeading", $output, 1);
				file_put_contents("/var/spool/sms/outgoing/$file", $sms);
	
				// Call to delete the file
				$defaults = array(
					CURLOPT_URL => $serverHttpAddress . "/smstest/smsDelete.php/?" . http_build_query(array("file"=>"$file")), 
					CURLOPT_HEADER => 0, 
					CURLOPT_RETURNTRANSFER => TRUE, 
					CURLOPT_TIMEOUT => 4 
				); 
				$ch = curl_init();
				curl_setopt_array($ch, ($defaults));
				$output = curl_exec($ch);
				curl_close($ch);
				if ((!($output)) || ($output != "ok"))
				{
					echo "    Problem removing $file from server!\n";
				}
			}
			else
			{
				echo "Fail! Leaving file on server\n";
			}
		}
	}
	else
		echo ".";
	sleep($pollInterval);
}

function str_replace_nth($search, $replace, $subject, $nth)
{
    $found = preg_match_all('/'.preg_quote($search).'/', $subject, $matches, PREG_OFFSET_CAPTURE);
    if (false !== $found && $found > $nth) {
        return substr_replace($subject, $replace, $matches[0][$nth][1], strlen($search));
    }
    return $subject;
}

?>
