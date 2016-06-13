<?php
namespace falkirks\chestrefill\dispatcher;


use falkirks\chestrefill\Chest;
use falkirks\chestrefill\ChestRefill;

interface RefillDispatcher{
    public function __construct(ChestRefill $chestRefill, array $args);
    public function attach(Chest $chest);
    public function detach(Chest $chest);
    public function cancel();
    public function getArgs();
}