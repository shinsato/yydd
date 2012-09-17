#!/usr/bin/php -q
<?php
	$dataCSV = fopen('battles/presidents/data.csv', 'r');
	$headers = fgetcsv($dataCSV);
	
	$flip_headers = array_flip($headers);
	
	$contestants = array();
	$contestants[] = "<?php\n";
	while($data = fgetcsv($dataCSV))
	{
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
