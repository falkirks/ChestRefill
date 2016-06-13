<?php
namespace falkirks\chestrefill\dispatcher;

use falkirks\chestrefill\Chest;
use falkirks\chestrefill\ChestRefill;
use pocketmine\scheduler\PluginTask;

class StandardDispatcher extends PluginTask implements RefillDispatcher{
    protected $task;
    /** @var  Chest[] */
    protected $chests;
    protected $args;
    public function __construct(ChestRefill $plugin, array $args){
        parent::__construct($plugin);
        $this->chests = [];
        $this->args = $args;
        if(isset($args["delay"]) && isset($args["repeat"])){
            $this->task = $plugin->getServer()->getScheduler()->scheduleDelayedRepeatingTask($this, $args["delay"], $args["repeat"]);
        }
        elseif(isset($args["delay"])){
            $this->task = $plugin->getServer()->getScheduler()->scheduleDelayedTask($this, $args["delay"]);
        }
        elseif(isset($args["repeat"])){
            $this->task = $plugin->getServer()->getScheduler()->scheduleRepeatingTask($this, $args["repeat"]);
        }
        else{
            $plugin->getLogger()->warning("You have created a dispatcher that will never execute.");
        }
    }

    /**
     * Actions to execute when run
     *
     * @param $currentTick
     *
     * @return void
     */
    public function onRun($currentTick){
        foreach($this->chests as $chest){
            $chest->apply();
        }
    }

    public function attach(Chest $chest){
        $this->chests[] = $chest;
    }

    public function detach(Chest $chest){
        $i = array_search($chest, $this->chests);
        if($i !== false) unset($this->chests[$i]);
    }

    public function cancel(){
        $this->chests = [];
        $this->getOwner()->getServer()->getScheduler()->cancelTask($this->task->getTaskId());
    }
    /**
     * @return array
     */
    public function getArgs(){
        return $this->args;
    }
}