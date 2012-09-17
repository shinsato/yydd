#!/usr/bin/php -q
<?php
	$csv_data = explode("\n",file_get_contents('https://docs.google.com/spreadsheet/pub?key=0AoepryCO01AzdDhKOVd1dzhQZGtXVGk5N1pwb1dtbkE&single=true&gid=0&output=csv'));
	$headers = str_getcsv($csv_data[0]);
	unset($csv_data[0]);
	
	$flip_headers = array_flip($headers);
	
	$contestants = array();
	$contestants[] = "<?php\n";
	foreach($csv_data as $raw_data)
	{
		$data = str_getcsv($raw_data);
		$class_name = $data[$flip_headers['phpClassName']];
		
		$contestants[] = '$'."{$class_name} = new Fighter();";
		foreach($headers as $key => $header)
		{
			if($header == 'phpClassName')
				continue;
				
			if($header == 'warcries')
				$contestants[] = '$'."{$class_name}->{$header} = array({$data[$key]});";
			else
				$contestants[] = '$'."{$class_name}->{$header} = '{$data[$key]}';";
		}
		$contestants[] = '$battle->contestants[] = $'.$class_name.";\n\n";
	}
	$contestants[] = "?>";

	echo implode("\n", $contestants);
	file_put_contents('battles/presidents/Contestants.php', implode("\n",$contestants));
?>
