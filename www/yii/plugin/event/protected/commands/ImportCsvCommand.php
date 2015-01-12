<?php

class ImportCsvCommand extends CConsoleCommand
{
	public function run($args)
	{
		$dummyRun = 0;
		$file = "/tmp/ws.csv";
		$row = 0;
		if (($handle = fopen($file, "r")) === FALSE)
			die("Cant open $file");
    	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
    	{
        	// Ignore header line
        	if ($row == 0)
        	{
				echo $row . " --> " . print_r($data, true) . "\n";
            	$row++;
           		continue;
        	}
        	//if ($row == 1)
        	//{
        		//$row++;
        		//continue;
        	//}

        	// Init db tables;
        	$event = new Event;
        	$ws = new Ws;
			$eventHasInterest = new EventHasInterest;
			$eventHasFormat = new EventHasFormat;

        	$num = count($data);
			echo $row . " --> ";
        	//echo "<p> $num fields in line $row: <br /></p>\n";
        	for ($c = 0; $c < $num; $c++)
        	{
            	//echo $data[$c] . "<br />\n";
        		switch ($c)
        		{
        			case 0: // title
        				$event->title = $data[$c];
        				echo $event->title . "<br>";
        				break;
        			case 1: // interest
						break;
					case 2: // type
						break;
					case 3: // start date
						$dt = $data[$c] . ' ' . $data[$c+1];
						$date = str_replace('/', '-', $dt);
						echo date('Y-m-d H:i:s', strtotime($date)) . "<br>";
						$event->start = date('Y-m-d H:i:s', strtotime($date));
						break;
					case 5: // start date
						$dt = $data[$c] . ' ' . $data[$c+1];
						$date = str_replace('/', '-', $dt);
						//echo date('Y-m-d H:i:s', strtotime($date)) . "<br>";
						$event->end = date('Y-m-d H:i:s', strtotime($date));
						break;
					case 7: // address
						$event->address = $data[$c];
						break;
					case 8: // post code
						$event->post_code = $data[$c];
						break;
					case 9: // web
						$event->web = $data[$c];
						break;
					case 10: // price band
						break;
					case 11: // contact
						$event->contact = $data[$c];
						break;
					case 12: // description
						$event->description = $data[$c];
						break;
// -----------------------------------------------------------------------------
					case 13:
						if ((strtoupper($data[$c]) == 'YES') || (strtoupper($data[$c]) == 'Y'))
							$ws->booking_essential = 1;
						break;
					case 14:
						$ws->os_grid_ref = $data[$c];
						break;
					case 15:
						$ws->grade = $data[$c];
						break;
					case 16:
						if ((strtoupper($ws->wheelchair_accessible) == 'YES') || (strtoupper($ws->wheelchair_accessible) == 'Y'))
						$ws->wheelchair_accessible = 1;
						break;
					case 17:
						if ($ws->min_age)
							$ws->min_age = $data[$c];
						break;
					case 18:
						if ($ws->max_age)
						$ws->max_age = $data[$c];
						break;
					case 19:
						$ws->child_ages_restrictions = $data[$c];
						break;
					case 20:
						$criteria = new CDbCriteria;
						$criteria->addCondition("organisation = '" . $data[$c] . "'");
						$member = Member::model()->find($criteria);
						if (!($member))
							die("Organisation field could not identify a member");
						$event->member_id = $member->id;
						break;
					case 21:
						$ws->full_price_notes = $data[$c];
						break;
					case 22:
						$ws->additional_venue_info = $data[$c];
						break;
					case 23:
						$ws->short_description = $data[$c];
						break;
				}
			}

// @@TODO: Remove hardcoding in all the updates here
			$event->member_id = 25;				// $member->id;
			$event->program_id = 6;				// $member->lock_program_id;
			$event->event_price_band_id = 3;	// whatever value was input
			$event->active = 1;
			if ((!($dummyRun)) && (!($event->save())))
				die( "\nEvent save failed on line " . $row . "\n");

			$ws->event_id = $event->id;
			echo $ws->event_id . "<br>";
			if ((!($dummyRun)) && (!($ws->save())))
				die("\nEvent additional info save failed on line " . $row . "\n");

			$eventHasInterest->event_event_id = $event->id;
			$eventHasInterest->event_interest_id = 11;
			if ((!($dummyRun)) && (!($eventHasInterest->save())))
				die("\nEvent has interest save failed on line " . $row . "\n");

			$eventHasFormat->event_event_id = $event->id;
			$eventHasFormat->event_format_id = 2;
			if ((!($dummyRun)) && (!($eventHasFormat->save())))
				die("\nEvent has format save failed on line " . $row . "\n");

			echo " UPDATED";

			$row++;
			echo "\n";
		}
		echo ($row-1) . " rows imported\n";
	}

}

?>

