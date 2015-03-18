<?php
namespace falkirks\chestrefill;

use falkirks\chestrefill\command\ChestCommand;
use falkirks\chestrefill\pattern\PatternStore;
use falkirks\chestrefill\storage\ChestDataStore;
use falkirks\chestrefill\storage\FlatFileStore;
use pocketmine\plugin\PluginBase;

class ChestRefill extends PluginBase{
    /** @var  PatternStore */
    protected $patternStore;
    /** @var  ChestDataStore */
    protected $chestDataStore;
    public function onEnable(){
        $this->patternStore = new PatternStore();
        $this->patternStore->loadClasses($this->getFile() . "src/" . str_replace("\\", "/", __NAMESPACE__) . "/pattern");

        $this->chestDataStore = new FlatFileStore($this);
        $this->chestDataStore->load();

        $this->getServer()->getCommandMap()->register("chestrefill", new ChestCommand($this));
    }

    /**
     * @return PatternStore
     */
    public function getPatternStore(){
        return $this->patternStore;
    }

    /**
     * @return ChestDataStore
     */
    public function getChestDataStore(){
        return $this->chestDataStore;
    }

    public function onDisable(){
        $this->chestDataStore->saveChests();
    }

}