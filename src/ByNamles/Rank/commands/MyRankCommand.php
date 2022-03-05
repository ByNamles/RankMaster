<?php

namespace ByNamles\Rank\commands;

use ByNamles\Rank\forms\MyRankForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class MyRankCommand extends Command{

    public function __construct(){
        parent::__construct(
            "myrank",
            "Learn your rank"
        );
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        $sender instanceof Player ? $sender->sendForm(new MyRankForm($sender)) : $sender->sendMessage(TextFormat::RED . "Use this command just in-game.");
    }
}