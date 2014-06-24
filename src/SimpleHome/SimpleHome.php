<?php

namespace SimpleHome;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\level\Level;

class SimpleHome extends PluginBase{

    public $homeData;

    public function onEnable(){
        if(!file_exists($this->getDataFolder() . "players.db")){
            $this->database = new \SQLite3($this->getDataFolder() . "players.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
            $resource = $this->getResource("sqlite3.sql");
            $this->database->exec(stream_get_contents($resource));
        }else{
            $this->database = new \SQLite3($this->getDataFolder() . "players.db", SQLITE3_OPEN_READWRITE);
        }
        $this->getLogger()->info("SimpleHome has loaded!");

    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
            case "home":
                if ($sender instanceof Player) {

                }
                else {
                    $sender->sendMessage("Please run command in game.");
                    return true;
                }
                break;
            case "sethome":
                if ($sender instanceof Player) {
                        
                }
                else {
                    $sender->sendMessage("Please run command in game.");
                    return true;
                }
                break;
            default:
                return false;
        }
    }

    public function onDisable(){
        $this->getLogger()->info("SimpleHome has loaded!");
        $this->homeData->save();
    }

}
