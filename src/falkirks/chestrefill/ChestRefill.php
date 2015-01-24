<?php
namespace falkirks\chestrefill;

use falkirks\chestrefill\pattern\PatternStore;
use pocketmine\plugin\PluginBase;

class ChestRefill extends PluginBase{
    public function onEnable(){
        $store = new PatternStore();
        $store->loadClasses($this->getFile() . "src/" . str_replace("\\", "/", __NAMESPACE__) . "/pattern");
        $this->getLogger()->info(var_export($store->verifyPatternData("fixed", ["items" => []]), true));
    }
}