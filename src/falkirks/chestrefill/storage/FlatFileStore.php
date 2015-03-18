<?php
namespace falkirks\chestrefill\storage;

use falkirks\chestrefill\ChestRefill;
use falkirks\chestrefill\Chest;
use falkirks\chestrefill\pattern\ChestPattern;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\utils\Config;

class FlatFileStore implements ChestDataStore{
    /** @var ChestRefill  */
    protected $main;
    /** @var Config  */
    protected $config;
    /** @var  Chest[] */
    protected $chests;
    public function __construct(ChestRefill $chestRefill){
        $this->main = $chestRefill;
        $this->config = new Config($chestRefill->getDataFolder() . "chests.yml", Config::YAML, []);
        $this->chests = [];
    }

    public function getChests(){
        return $this->chests;
    }

    public function load(){
        foreach($this->config->getAll() as $chestData){
            $level = $this->main->getServer()->getLevelByName($chestData["levelName"]);
            if($level instanceof Level) {
                $chest = new Chest(new Position($chestData["x"], $chestData["y"], $chestData["z"], $level));
                $chestTile = $chest->getChest();
                if($chestTile instanceof \pocketmine\tile\Chest) {
                    $pattern = $this->main->getPatternStore()->makePattern($chestData["patternName"], $chestData["patternArgs"]);
                    if($pattern instanceof ChestPattern){
                        $chest->setPattern($pattern);
                        $this->chests[] = $chest;
                    }
                    else{
                        $this->main->getLogger()->warning("Failed to load a chest: Pattern data invalid.");
                    }
                }
                else{
                    $this->main->getLogger()->warning("Failed to load a chest: No chest at position.");
                }
            }
            else{
                $this->main->getLogger()->warning("Failed to load a chest: Level is not loaded.");
            }
        }
    }

    public function addChest(Chest $chest){
        $this->chests[] = $chest;
    }

    public function removeChest(Chest $chest){
        $i = array_search($chest, $this->chests);
        if($i !== false) unset($this->chests[$i]);
    }
    public function saveChests(){
        $save = [];
        foreach($this->chests as $chest){
            /** @var ChestPattern $pattern */
            $pattern = $chest->getPattern();
            $save[] = [
                "x" => $chest->getPosition()->x,
                "y" => $chest->getPosition()->y,
                "z" => $chest->getPosition()->z,
                "levelName" => $chest->getPosition()->getLevel()->getName(),
                "patternName" => $pattern::getName(),
                "patternArgs" => $pattern->getPatternData()
            ];
        }
        $this->config->setAll($save);
        $this->config->save();
    }

}