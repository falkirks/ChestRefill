<?php

namespace falkirks\chestrefill;


use falkirks\chestrefill\pattern\ChestPattern;
use pocketmine\level\Position;

class Chest {
    /** @var  ChestPattern */
    protected $pattern;
    /** @var  Position */
    protected $position;

    /** @var  String[] */
    protected $dispatchers;

    public function __construct(Position $position){
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getPattern(){
        return $this->pattern;
    }

    /**
     * @param ChestPattern $pattern
     * @return boolean
     */
    public function setPattern($pattern){
        if($pattern->getChestTile()->getLevel() === $this->position->getLevel() && $pattern->getChestTile()->getBlock()->x === $this->position->x && $pattern->getChestTile()->getBlock()->y === $this->position->y && $pattern->getChestTile()->z === $this->position->z) {
            $this->pattern = $pattern;
            return true;
        }
        else{
            return false;
        }
    }
    public function getChest(){
        if($this->hasPattern()) return $this->pattern->getChestTile();

        $tile = $this->position->getLevel()->getTile($this->position);
        if($tile instanceof \pocketmine\tile\Chest){
            return $tile;
        }
        else{
            return false;
        }
    }
    public function hasPattern(){
        return $this->pattern instanceof ChestPattern;
    }

    public function apply(){
        return ($this->hasPattern() ? $this->pattern->apply() : false);
    }

    /**
     * @return \String[]
     */
    public function getDispatchers(): array{
        return $this->dispatchers;
    }

    public function usesDispatcher($name){
        return in_array($name, $this->dispatchers);
    }

    public function addDispatcher($name){
        if(!$this->usesDispatcher($name)){
            $this->dispatchers[] = $name;
        }
    }

    public function removeDispatcher($name){
        if($this->usesDispatcher($name)){
            $this->dispatchers = array_diff($this->dispatchers, [$name]);

        }
    }

    /**
     * @return Position
     */
    public function getPosition(){
        return $this->position;
    }

}