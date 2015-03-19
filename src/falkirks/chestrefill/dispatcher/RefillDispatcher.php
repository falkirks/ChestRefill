<?php
namespace falkirks\chestrefill\dispatcher;


use falkirks\chestrefill\Chest;

interface RefillDispatcher{
    public function attach(Chest $chest);
    public function detach(Chest $chest);
    public function cancel();
}