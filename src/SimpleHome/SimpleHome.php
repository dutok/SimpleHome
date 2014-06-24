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
        $this->saveDefaultConfig();
        $this->reloadConfig();
        $provider = new SQLite3DataProvider($this);
        $this->getLogger()->info("SimpleHome has loaded!");

    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
            case "home":
                if ($sender instanceof Player) {
                    if ($this->homeData->exists($sender->getName())) {
                        $homeX = $this->homeData->get($sender->getName())[0];
                        $homeY = $this->homeData->get($sender->getName())[1];
                        $homeZ = $this->homeData->get($sender->getName())[2];
                        $homeLevel = $this->homeData->get($sender->getName())[3];
                        foreach ($this->getServer()->getLevels() as $levelsLoaded => $levelLoaded) {
                            if ($homeLevel === $levelLoaded->getName()) {
                                $actualLevel = $levelLoaded;
                                $pos = new Position((int) $homeX, (int) $homeY, (int) $homeZ, $actualLevel);
                                $sender->teleport($pos);
                                $sender->sendMessage("You teleported home.");
                            }
                            else {
                                $sender->sendMessage("That world is not loaded!");
                            }
                        }
                    }
                    else {
                        $sender->sendMessage("Please set your home before using this command.");
                    }
                break;
                }
                else {
                    $sender->sendMessage("Please run command in game.");
                    return true;
                }
                break;
            case "sethome":
                if ($sender instanceof Player) {
                        $this->homeData->set($sender->getName(), array((int) $sender->x, (int) $sender->y, (int) $sender->z, $sender->getLevel()->getName()));
                        $this->homeData->save();
                        $sender->sendMessage("Your home is set.");
                        $this->getLogger()->info($sender->getName() . " has set their home in world " . $sender->getLevel()->getName());
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