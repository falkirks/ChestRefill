<?php
namespace falkirks\chestrefill\storage;


use falkirks\chestrefill\Chest;
use falkirks\chestrefill\ChestRefill;

interface ChestDataStore {
    public function __construct(ChestRefill $chestRefill);
    public function getChests();
    public function load();
    public function addChest(Chest $chest);
    public function removeChest(Chest $chest);
    public function saveChests();
}