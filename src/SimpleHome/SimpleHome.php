<?php

namespace SimpleHome;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class SimpleHome extends PluginBase{

    public function onEnable(){
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder()."homes.yml", Config::YAML, array());
        $this->getLogger()->info("SimpleHome has loaded!");

    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
            case "home":
                if ($sender instanceof Player) {
                    if (isset($args[0])){
                        //execute command here
                    }
                }
                else {
                    $sender->sendMessage("Please run command in game.");
                    return true;
                }
                break;
            case "sethome":
                if ($sender instanceof Player) {
                    if (isset($args[0])){
                        //execute command here
                    }
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
}