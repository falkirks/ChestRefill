<?php
namespace falkirks\chestrefill\dispatcher;


use falkirks\chestrefill\Chest;
use falkirks\chestrefill\ChestRefill;
use pocketmine\utils\Config;

class DispatcherStore {
    /** @var  RefillDispatcher[] */
    private $dispatchers;
    /** @var ChestRefill  */
    private $plugin;

    public function __construct(ChestRefill $plugin){
        $this->plugin = $plugin;
        $this->store = new Config($this->plugin->getDataFolder() . "dispatchers.yml", Config::YAML);
    }
    public function hasDispatcher($name){
        return isset($this->dispatchers[$name]);
    }
    public function addDispatcher($name, RefillDispatcher $dispatcher){
        $this->dispatchers[$name] = $dispatcher;
    }
    public function addStandardDispatcher($name, $args){
        $this->addDispatcher($name, new StandardDispatcher($this->plugin, $args));
    }
    public function addStandardDispatchers($data){
        foreach($data as $name => $args){
            $this->addStandardDispatcher($name, $args);
        }
    }
    public function getDispatcher($name){
        return $this->hasDispatcher($name) ? $this->dispatchers[$name] : null;
    }
    public function removeDispatcher($name){
        if($this->hasDispatcher($name)) unset($this->dispatchers[$name]);
    }
    public function clearAll(){
        foreach($this->dispatchers as $dispatcher){
            $dispatcher->cancel();
        }
        $this->dispatchers = [];
    }
    public function attachChestTo(Chest $chest, $name){
        if($this->hasDispatcher($name)) $this->dispatchers[$name]->attach($chest);
    }
    public function detachChestFrom(Chest $chest, $name){
        if($this->hasDispatcher($name)) $this->dispatchers[$name]->detach($chest);
    }
    public function removeChestFromAll(Chest $chest){
        foreach($this->dispatchers as $dispatcher){
            $dispatcher->detach($chest);
        }
    }
    protected function serializeDispatcher(RefillDispatcher $refillDispatcher){
        $args = $refillDispatcher->getArgs();
        $args["type"] = get_class($refillDispatcher);
        return $args;
    }
    protected function unserializeDispatcher($args){
        if(isset($args["type"]) && class_exists($args["type"])){
            $class = $args["type"];
            unset($args["type"]);
            return new $class($this->plugin, $args);
        }
        return false;
    }
}