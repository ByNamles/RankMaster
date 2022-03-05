<?php

namespace ByNamles\Rank\commands;

use JsonException;
use ByNamles\Rank\Rank;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class RankMineCommand extends Command{

    /** @var Rank */
    private Rank $plugin;

    public function __construct(){
        parent::__construct(
            "rankmine",
            "Rank, raw material main command."
        );
        $this->plugin = Rank::getInstance();
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Use this command just in-game.");
            return;
        }

        if(!Server::getInstance()->isOp($sender->getName())){
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return;
        }

        if(empty($args[0])){
            $sender->sendMessage(TextFormat::RED . "Usage: " . TextFormat::WHITE . "/rankmine [create | set | delete | list] [argument]>");
            return;
        }

        switch($args[0]){
            case "create":
                if(empty($args[1]) and empty($args[2])){
                    $sender->sendMessage(TextFormat::RED . "Usage: " . TextFormat::WHITE . "/rankmine [name] [perm]");
                    return;
                }

                if($this->plugin->mine->exists($args[1])){
                    $sender->sendMessage(TextFormat::RED . "This raw materials place already exists.");
                    return;
                }

                $this->plugin->mine->set($args[1],
                    [
                        "Perm" => $args[2],
                        "StartPos" => null,
                        "EndPos" => null,
                        "Level" => null
                    ]
                );
                $this->plugin->mine->save();
                PermissionManager::getInstance()->addPermission(new Permission($args[2]));
                $sender->sendMessage(TextFormat::GREEN . " Raw material ".TextFormat::AQUA . $args[1].TextFormat::GREEN ." has been created, please define the borders");
                break;

            case "set":
                if(empty($args[1]) and empty($args[2])){
                    $sender->sendMessage(TextFormat::RED . "Usage: " . TextFormat::WHITE . "/rankmine set [name] [1 | 2]");
                    return;
                }

                if(!is_numeric($args[2])){
                    $sender->sendMessage(TextFormat::RED . "Position should be numeric.");
                    return;
                }

                if(!$this->plugin->mine->exists($args[1])){
                    $sender->sendMessage(TextFormat::RED . "There's no such raw material.");
                    return;
                }

                $data = $this->plugin->mine->get($args[1]);
                switch($args[2]){
                    case 1:
                        if($data["StartPos"] !== null){
                            $sender->sendMessage(TextFormat::RED . "This position already set.");
                            return;
                        }

                        $this->plugin->mine->set($args[1],
                            [
                                "Perm" => $data["Perm"],
                                "StartPos" => $sender->getPosition()->getFloorX() . ":" . $sender->getPosition()->getFloorY() . ":" . $sender->getPosition()->getFloorZ(),
                                "EndPos" => $data["EndPos"],
                                "Level" => $sender->getWorld()->getFolderName()
                            ]
                        );
                        $this->plugin->mine->save();
                        $sender->sendMessage(TextFormat::GREEN . "The first position defined.");
                        break;

                    case 2:
                        if($data["EndPos"] !== null){
                            $sender->sendMessage(TextFormat::RED . "This position already set.");
                            return;
                        }

                        $this->plugin->mine->set($args[1],
                            [
                                "Perm" => $data["Perm"],
                                "StartPos" => $data["StartPos"],
                                "EndPos" => $sender->getPosition()->getFloorX() . ":" . $sender->getPosition()->getFloorY() . ":" . $sender->getPosition()->getFloorZ(),
                                "Level" => $data["Level"]
                            ]
                        );
                        $this->plugin->mine->save();
                        $sender->sendMessage(TextFormat::GREEN . "The second position defined.");
                        break;
                }
                break;

            case "delete":
                if(empty($args[1])){
                    $sender->sendMessage(TextFormat::RED . "Usage: " . TextFormat::WHITE . "/rankmine delete [name]");
                    return;
                }

                if(!$this->plugin->mine->exists($args[1])){
                    $sender->sendMessage(TextFormat::RED . "There's no such raw material.");
                    return;
                }

                $this->plugin->mine->remove($args[1]);
                $this->plugin->mine->save();
                $sender->sendMessage(TextFormat::AQUA . $args[1] . TextFormat::GREEN . " raw material has been removed.");
                break;

            case "list":
                $data = $this->plugin->mine->getAll(true);
                if(count($data) == 0){
                    $sender->sendMessage(TextFormat::RED . "There are no raw materials");
                    return;
                }

                $mines = "";
                $i = 1;
                foreach($data as $datum){
                    $mines .= TextFormat::DARK_BLUE . "[" . TextFormat::DARK_PURPLE . $i . TextFormat::DARK_BLUE . "]" . TextFormat::AQUA . $datum . TextFormat::EOL;
                    $i++;
                }
                $sender->sendMessage(TextFormat::RED . "Raw Material List" . TextFormat::EOL . $mines);
                break;
        }
    }
}