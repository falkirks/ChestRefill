<?php

namespace falkirks\chestrefill\command;


use falkirks\chestrefill\ChestRefill;
use falkirks\chestrefill\pattern\ChestPattern;
use falkirks\chestrefill\pattern\PatternStore;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

class ChestCommand extends Command implements PluginIdentifiableCommand{
    /** @var ChestRefill  */
    protected $main;

    public function __construct(ChestRefill $main){
        parent::__construct("chest", "", "/chest <stuff>", []);
        $this->main = $main;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param string[] $args
     *
     * @return mixed
     */
    public function execute(CommandSender $sender, $commandLabel, array $args){
        if($sender->hasPermission("chestrefill.command")){
            if(isset($args[0])) {
                switch ($args[0]) {
                    case 'add':
                        if (isset($args[1])) {
                            $pattern = $this->main->getPatternStore()->getPattern($args[1]);
                            if ($pattern !== null) {
                                $pattern::startWizard($sender, $args);
                            } else {
                                $sender->sendMessage("That pattern doesn't exist.");
                            }
                        } else {
                            $sender->sendMessage("You must specify a pattern type.");
                        }
                        break;
                    default:
                        $sender->sendMessage("Unknown subcommand.");
                        break;
                }
            }
            else{
                $sender->sendMessage("Running ChestRefill v" . $this->main->getDescription()->getVersion());
            }
        }
        else{
            $sender->sendMessage("You don't have permission to use this command.");
        }
    }

    /**
     * @return \pocketmine\plugin\Plugin
     */
    public function getPlugin(){
        return $this->main;
    }


}