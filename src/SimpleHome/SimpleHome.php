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
        @mkdir($this->getDataFolder());
        if(!file_exists($this->getDataFolder() . "homes.db")){
            $this->database = new \SQLite3($this->getDataFolder() . "homes.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
            $resource = $this->getResource("sqlite3.sql");
            $this->database->exec(stream_get_contents($resource));
            @fclose($resource);
        }else{
            $this->database = new \SQLite3($this->getDataFolder() . "homes.db", SQLITE3_OPEN_READWRITE);
        }
        $this->getLogger()->info("SimpleHome has loaded!");
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
            case "home":
                if ($sender instanceof Player) {
                    $name = trim(strtolower($sender->getName()));
                    $prepare = $this->database->prepare("SELECT * FROM homes WHERE name = :name");
                    $prepare->bindValue(":name", $name, SQLITE3_TEXT);

                    $result = $prepare->execute();

                    if($result instanceof \SQLite3Result){
                        $data = $result->fetchArray(SQLITE3_ASSOC);
                        $result->finalize();
                        $dataWorld = $data['world'];
                        $dataX = $data['x'];
                        $dataY = $data['y'];
                        $dataZ = $data['z'];
                        foreach ($this->getServer()->getLevels() as $levelsLoaded => $levelLoaded) {
                            if ($dataWorld === $levelLoaded->getName()) {
                                $actualLevel = $levelLoaded;
                                $pos = new Position((int) $dataX, (int) $dataY, (int) $dataZ, $actualLevel);
                                $sender->teleport($pos);
                                $sender->sendMessage("You teleported home.");
                            }
                            else {
                                $sender->sendMessage("That world is not loaded!");
                            }
                        }

                    }

                }
                else {
                    $sender->sendMessage("Please run command in game.");
                    return true;
                }
                break;
            case "sethome":
                if ($sender instanceof Player) {
                    $name = trim(strtolower($sender->getName()));
                    $world = trim(strtolower($sender->getLevel()->getName()));
                    $x = (int) $sender->x;
                    $y = (int) $sender->y;
                    $z = (int) $sender->z;

                    $name = trim(strtolower($sender->getName()));
                    $prepare = $this->database->prepare("SELECT * FROM homes WHERE name = :name");
                    $prepare->bindValue(":name", $name, SQLITE3_TEXT);

                    $result = $prepare->execute();

                    if($result instanceof \SQLite3Result){
                        $data = $result->fetchArray(SQLITE3_ASSOC);
                        $result->finalize();
                        if(isset($data["name"])) {
                            $prepare = $this->database->prepare("UPDATE homes SET world = :world, x = :x, y = :y, z = :z WHERE name = :name");
                            $prepare->bindValue(":name", $name, SQLITE3_TEXT);
                            $prepare->bindValue(":world", $world, SQLITE3_TEXT);
                            $prepare->bindValue(":x", $x, SQLITE3_INTEGER);
                            $prepare->bindValue(":y", $y, SQLITE3_INTEGER);
                            $prepare->bindValue(":z", $z, SQLITE3_INTEGER);
                            $prepare->execute();
                        }
                        else {
                            $prepare = $this->database->prepare("INSERT INTO homes (name, world, x, y, z) VALUES (:name, :world, :x, :y, :z)");
                            $prepare->bindValue(":name", $name, SQLITE3_TEXT);
                            $prepare->bindValue(":world", $world, SQLITE3_TEXT);
                            $prepare->bindValue(":x", $x, SQLITE3_INTEGER);
                            $prepare->bindValue(":y", $y, SQLITE3_INTEGER);
                            $prepare->bindValue(":z", $z, SQLITE3_INTEGER);
                            $prepare->execute();
                        }
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

    public function onDisable(){
        $this->getLogger()->info("SimpleHome has unloaded!");
    }

}
