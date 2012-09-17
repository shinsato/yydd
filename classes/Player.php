<?php
	class Player
	{
		var $strength = 0;
		var $constitution = 0;
		var $health = 0;
		var $damage = 0;
		var $playerName = '';
		var $level = 1;
		var $hitdie = 1;
		var $hitdieSides = 6;
		var $warcries = array();

		var $initiative = 0;
		var $critChance = 0;
		var $thac0 = 19;
		var $ac = 10;

		public function __construct()
		{
			for($levels=0; $levels < $this->level; $levels++)
			{
				$this->health += $this->rollDice($this->hitdie, $this->hitdieSides, 'constitution');
			}
		}

		public function getStrength()
		{
			return $this->strength;
		}

		public function getEndurance()
		{
			return $this->constitution;
		}

		public function getMaxHealth()
		{
			return $this->health;
		}

		public function getName()
		{
			return $this->playerName;
		}

		public function getModifer($roll)
		{
			$function = '_mod_' . $roll;
			if (method_exists($this, $function))
				return $this->$function();
			else
				return 0;
		}

		private function _mod_initiative()
		{
			return $this->getAbilityMod($this->dexterity);
		}

		private function _mod_defend()
		{
			return $this->getAbilityMod($this->dexterity);
		}

		private function _mod_damage()
		{
			return $this->getAbilityMod($this->strength);
		}

		private function _mod_tohit()
		{
			return $this->getAbilityMod($this->strength);
		}

		public function getAbilityMod($stat)
		{
			return(intval($stat/2) - 5);
		}

		public function roll($min, $max, $mod = 0)
		{
			return Random::getNextInt($min, $max) + $mod;
		}

		public function rollDice($num, $sides, $mod = null)
		{
			$result = 0;
			for($roll=0; $roll < $num; $roll++)
			{
				$result += $this->roll(1, $sides);
			}
			$function = '_mod_' . $mod;
			if (method_exists($this, $function))
				$result += $this->$function();

			return $result;
		}

		public function setInitiative()
		{
			$this->initiative = $this->roll(1, 20, $this->getModifer('initiative'));
		}

		public function getInitiative()
		{
			return $this->initiative;
		}

		public function ai($battle)
		{
			if ($this->isAlive())
			{
				$target = $this->findTarget($battle->contestants);
				$this->attack($battle, $target);
			}
		}

		private function getCritChance()
		{
			return (20 - $this->critChance);
		}

		private function getAC()
		{
			return ($this->ac - $this->getModifer('defend'));
		}

		public function attack($battle, $target)
		{
			$toHit = $this->thac0 + $this->getModifer('tohit') - $battle->contestants[$target]->getAC();
			$toHitRoll = $this->rollDice(1,20);
			$damageMultiplyer = 1;

			if ($toHitRoll == 1)
			{
				$this->warCry();
				//critical miss
				echo $this->getName() . " tries to hit someone, not sure who. Because he whiffs so bad he trips...";
				$damage = $this->rollDice(1, 4);
				echo " for $damage damage.\n";
				$this->takeDamage($damage);
				if (!$this->isAlive())
				{
					echo $this->getName() . " died.\n";
				}
				return;
			} elseif ($toHitRoll >= $this->getCritChance()) {
				$this->warCry();
				//Critical hit!
				echo $this->getName() . " SUPER MEGA stabs " . $battle->contestants[$target]->getName();
				$damageMultiplyer = 2;
			} elseif ($toHitRoll >= $toHit) {
				//Hit!
				echo $this->getName() . " stabs " . $battle->contestants[$target]->getName();
			} else {
				//miss :(
				echo $this->getName() . " misses " . $battle->contestants[$target]->getName() . "\n";
				return;
			}

			$damage = $this->roll(1, 10, $this->getModifer('damage')) * $damageMultiplyer;
			echo " for $damage damage.\n";
			$battle->contestants[$target]->takeDamage($damage);
			if (!$battle->contestants[$target]->isAlive())
			{
				echo $battle->contestants[$target]->getName() . " died.\n";
			}
		}

		public function warCry()
		{
			if(count($this->warcries) > 0)
			{
				$cry = $this->roll(0, count($this->warcries) - 1);
				echo $this->getName() . " shouts \"" . $this->warcries[$cry] . "\"\n";
			}
		}

		public function takeDamage($damage)
		{
			$this->damage += $damage;
		}

		public function isAlive()
		{
			if ($this->damage < $this->health)
				return true;
			else
				return false;
		}

		public function findTarget($contestants)
		{
			$target = Random::getNextInt(0, count($contestants) - 1);
			while ($contestants[$target] == $this || !$contestants[$target]->isAlive())
				$target = Random::getNextInt(0, count($contestants) - 1);
			return $target;
		}
	}
?>
