<?php
namespace falkirks\chestrefill;

use falkirks\chestrefill\pattern\PatternStore;
use pocketmine\plugin\PluginBase;

class ChestRefill extends PluginBase{
    public function onEnable(){
        (new PatternStore())->loadClasses($this->getFile() . "src/" . str_replace("\\", "/", __NAMESPACE__) . "/pattern");
    }
}