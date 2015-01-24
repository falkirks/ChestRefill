<?php
namespace falkirks\chestrefill\pattern;

use pocketmine\Server;

class PatternStore{
    private $classes;
    public function loadClasses($path){
        $logger = Server::getInstance()->getLogger();
        $externalPathLength = strpos($path, "src/")+4;
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file){
            $basename = basename($file);
            if($basename{0}  === ".") continue;
            $class = str_replace("/", "\\", substr($file, $externalPathLength, -4));
            try {
                if(is_subclass_of($class, ChestPattern::class)){
                    $logger->info($class);
                    var_dump($class::getParams());
                }
            }
            catch(\Exception $e){
                //Class couldn't be found
            }
        }
    }
}