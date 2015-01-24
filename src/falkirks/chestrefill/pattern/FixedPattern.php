<?php

namespace falkirks\chestrefill\pattern;


use pocketmine\item\Item;

/**
 * Class FixedPattern
 * @package falkirks\chestrefill\pattern
 * @pattern-name fixed
 * @pattern-params {"items": []}
 */
class FixedPattern extends ChestPattern{
    public function apply(){
        $this->getChestTile()->getRealInventory()->clearAll();
        $this->getChestTile()->getRealInventory()->addItem($this->getPatternData()["items"]);

        $inv = $this->getChestTile()->getRealInventory();
        foreach ($this->getPatternData()["items"] as $key => $slot) {
            if(!is_array($slot)) $slot = [$slot, 1];
            $blockData = explode(":", $slot[0]);
            $inv->setItem($key, new Item($blockData[0], (isset($blockData[1]) ? $blockData[1] : 0), $slot[1]));
        }
    }
    public function checkPatternData(){
        return is_array($this->getPatternData()["items"]);
    }
}