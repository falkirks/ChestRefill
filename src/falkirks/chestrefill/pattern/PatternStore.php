<?php
namespace falkirks\chestrefill\pattern;

use pocketmine\command\defaults\PardonCommand;
use pocketmine\Server;

class PatternStore{
    private $classes;

    public function __construct(){
        $this->classes = [];
    }
    public function loadClasses($path){
        $logger = Server::getInstance()->getLogger();
        $externalPathLength = strpos($path, "src/")+4;
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file){
            $basename = basename($file);
            if($basename{0}  === ".") continue;
            $class = str_replace("/", "\\", substr($file, $externalPathLength, -4));
            try {
                if(is_subclass_of($class, ChestPattern::class)){
                    $this->classes[$class::getName()] = [$class, $class::getParams()];
                }
            }
            catch(\Exception $e){
                //Class couldn't be found
            }
        }
    }
    public function verifyPatternData($name, array $data){
        return PatternStore::compareArray($this->classes[$name][1], $data);
    }
    private static function compareArray($template, $test){
        foreach($template as $i => $value){
            if(is_array($value)) {
                if (!isset($test[$i])) return false;
                if(count($value) === 0) continue;
                if (PatternStore::compareArray($value, $test[$i])) return false;
            }
            elseif(!isset($test[$value])) return false;
        }
        return true;
    }
    public function makePattern($name, ...$params){
        if(!isset($this->classes[$name])) return false;
        $class = $this->classes[$name][0];
        return new $class(...$params);
    }
    public function clearClasses(){
        $this->classes = [];
    }
}