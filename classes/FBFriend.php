<?php
	class FBFriend extends Fighter
	{
		public function __construct($name,
						  $level = 1,
						  $bmi = null,
						  $thac0 = null,
						  $ac = null,
						  $charisma = null,
						  $dexterity = null,
						  $wisdom = null,
						  $intelligence = null,
						  $strength = null,
						  $constitution = null,
						  $warcries = null)
		{
			$this->playerName = $name;
		
			if($level < 1) $this->level = 1; else $this->level = $level;
			if($bmi < 1) $this->bmi = 1; else $this->bmi = $bmi;
			if($thac0 < 1) $this->thac0 = 1; else $this->thac0 = $thac0;
			if($ac < 1) $this->ac = 10; else $this->ac = $ac;
			if(!isset($charisma)) $this->charisma = 1; else $this->charisma = $charisma;
			if(!isset($dexterity)) $this->dexterity = 1; else $this->dexterity = $dexterity;
			if(!isset($wisdom)) $this->wisdom = 1; else $this->wisdom = $wisdom;
			if(!isset($intelligence)) $this->intelligence = 1; else $this->intelligence = $intelligence;
			if(!isset($strength)) $this->strength = 1; else $this->strength = $strength;
			if(!isset($constitution)) $this->constitution = 1; else $this->constitution = $constitution;
			if(!isset($warcries)) $this->warcries = array(); else $this->warcries = $warcries;
		}
	}
?>