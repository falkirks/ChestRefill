ChestRefill
===========

ChestRefill is powerful chest management plugin. When first released, ChestRefill allowed users to capture chest contents and refill them all on a timer. Now ChestRefill can do much more than that.

## API
ChestRefill comes with an easy to use API. This API allows other plugins to use portions of ChestRefill, modify existing features or simply simulate normal user interaction. 

### `ChestPattern`
Chest patterns define what happens when a chest is "refilled". They are loaded in a dynamic fashion and new ones can easily be injected. Each chest has it's one and only one `ChestPattern`.

#### Example
Patterns must be declared in a certain structure.
```
/**
 * @pattern-name setALL
 * @pattern-params {"id": "", "meta": "", "amount": ""}
 */
class SetAllPattern extends ChestPattern{
    public function apply(){
        // This method applies the pattern to the chest, chest can be accessed at $this->getChestTile()
    }
    public static function startWizard(CommandSender $sender, array $args, ChestRefill $main){
        // This method is run when a CommandSender attempts to access pattern
    }
}
```

The above pattern could be registered by using
```
$this->getServer()->getPluginManager()->getPlugin("ChestRefill")->getPatternStore()->addClass(SetAllPattern::class);
```
### `RefillDispatcher` (in progress)
Chest dispatchers are what cause chests' to be refilled. A dispatcher can have multiple chests and a chest can have multiple dispatchers. 

