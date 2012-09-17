#!/usr/bin/php -q
<?php
	$access_token = $argv[1] != '' ? $argv[1] : 'AAAAAAITEghMBAMJOYhJfbgE3ZAGZAPvyvuWWqiqLpjxiO2YNU50EUuap1LtZCMl7CLXPIQMnlt0fSPYjTOEML3AvvUiYdPgpixWW9xkGgZDZD' ;
	
	$friends = array();
	$contestants = array();
	$contestants[] = "<?php";
	
	$friends = get_friends($friends,'https://graph.facebook.com/me/friends?access_token='.$access_token);
	echo "Got ".count($friends)." Friends";
	if(is_array($friends))
	{
		foreach($friends as $friend)
		{
			$contestants[] = '$fb_'.$friend->id." = new FBFriend('".addslashes($friend->name)."');";
			$contestants[] = '$battle->contestants[] = $fb_'.$friend->id.";";
		}
	}
	$contestants[] = "?>";
	
	//echo implode("\n", $contestants);
	file_put_contents('battles/friends/Contestants.php', implode("\n",$contestants));
	
	
	
	function get_friends($friends,$url)
	{
		$data = json_decode(file_get_contents($url));
	
		$friends = array_merge($friends,$data->data);
		
		if(isset($data->paging) && isset($data->paging->next))
		{
			get_friends($friends,$data->paging->next);
		}
		return $friends;
	}
?>
