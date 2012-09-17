<?php
	class Battle
	{
		var $name = '';
		var $contestantList = array();
		var $contestants = array();

		public function __construct()
		{
			$this->contestants = array();
		}

		public function start()
		{
			if(is_array($this->contestants))
			{
				foreach($this->contestants as $contestant)
				{
					echo $contestant->getName() . " Entered the Arena! " . $contestant->health . "\n";
				}
			}
			while(count($this->contestants) > 1)
			{
				echo count($this->contestants) . " contestants remaining.\n";

				//New Round Start
				//Set Initiative
				foreach($this->contestants as $contestant)
				{
					$contestant->setInitiative();
				}
				usort($this->contestants, 'sortContestants');
				reset($this->contestants);

				$numContestants = count($this->contestants);

				//Execute Each Turn
				for($x=0; $x < $numContestants; $x++)
				{
					if (isset($this->contestants[$x]))
						$this->contestants[$x]->ai($this);
				}

				//Output End of Round Data
				echo "Round over!\n\n";
				for($x=0; $x < $numContestants; $x++)
				{
					if (!$this->contestants[$x]->isAlive())
						unset($this->contestants[$x]);
				}
			}
			$winner = current($this->contestants);
			echo $winner->getName() . " wins!\n";
		}
	}

	function sortContestants($a, $b)
	{
		if ($a->getInitiative() == $b->getInitiative()) {
			return 0;
		}
		return ($a->getInitiative() < $b->getInitiative()) ? -1 : 1;
	}
?>
