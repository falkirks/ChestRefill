<?php
namespace ChestRefill;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;

class ChestRefill extends PluginBase implements CommandExecutor, Listener {
    private $c;
    public $config;
    public function onEnable() {
        $this->c = [];
        if(!is_file($this->getDataFolder() . "/config.txt")){
            @mkdir($this->getDataFolder());
            file_put_contents($this->getDataFolder() . "/config.txt", 5);
        }
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new RefillTask($this), file_get_contents($this->getDataFolder() . "/config.txt")*20);
        $this->config = new Config($this->getDataFolder()."chests.yml", Config::YAML, array());
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        if($sender instanceof Player){
            $this->c[$sender->getName()] = true;
            $sender->sendMessage("Touch a chest to capture contents.");
        }
        else{
            $sender->sendMessage("Please run command in game.");
        }
        return true;
    }
    public function onPlayerInteract(PlayerInteractEvent $event){
        if(isset($this->c[$event->getPlayer()->getName()]) && $event->getBlock()->getID() == 54){
            $tile = $event->getPlayer()->getLevel()->getTile(new Vector3($event->getBlock()->x, $event->getBlock()->y, $event->getBlock()->z));
            $new = [];
            for($i = 0; $i < 27; $i++){
                $new[] = array($tile->getItem($i)->getID(),$tile->getItem($i)->count,$tile->getItem($i)->getDamage());
            }
            $this->config->set($event->getBlock()->x . ":" . $event->getBlock()->y . ":" . $event->getBlock()->z . ":" . $event->getPlayer()->getLevel()->getName(), $new);
            $this->config->save();
            $event->getPlayer()->sendMessage("Chest added.");
            unset($this->c[$event->getPlayer()->getName()]);
        }
    }
}
