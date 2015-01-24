<?php
namespace ChestRefill;

use pocketmine\scheduler\PluginTask;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\tile\Chest;

class RefillTask extends PluginTask{
	public function onRun($tick){
		foreach ($this->getOwner()->config->getAll() as $c => $slots) {
			$c = explode(":", $c);
			if(($lev = $this->getOwner()->getServer()->getLevelByName($c[3])) === false) continue;
			$tile = $lev->getTile(new Vector3($c[0],$c[1],$c[2]));
			if(!$tile) continue;
            if(!($tile instanceof Chest)) continue;
            $inv = $tile->getRealInventory();
			foreach ($slots as $key => $slot) {
				$inv->setItem($key, new Item($slot[0],$slot[2],$slot[1]));
			}

		}
		$this->getOwner()->getLogger()->info("Chests have been reset.");
	}
}