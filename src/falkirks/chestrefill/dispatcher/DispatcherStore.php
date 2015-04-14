<?php
namespace falkirks\chestrefill\dispatcher;


use falkirks\chestrefill\Chest;
use falkirks\chestrefill\ChestRefill;

class DispatcherStore {
    /** @var  RefillDispatcher[] */
    private $dispatchers;
    /** @var ChestRefill  */
    private $plugin;

    public function __construct(ChestRefill $plugin){
        $this->plugin = $plugin;
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
}