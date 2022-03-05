<?php

namespace ByNamles\Rank\commands;

use ByNamles\Rank\forms\RankUpForm;
use ByNamles\Rank\Rank;
use ByNamles\Rank\utils\RankUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class RankUpCommand extends Command{

    /** @var Rank */
    private $plugin;

    public function __construct(){
        parent::__construct(
            "rankup",
            "Rank atla!"
        );
        $this->plugin = Rank::getInstance();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Use this command just in-game.");
            return;
        }

        if($this->plugin->getPlayerRank($sender->getName()) == RankUtils::getEndRank()){
            $sender->sendMessage(TextFormat::RED . "Son seviyeye ulaşmışsın!");
            return;
        }

        $sender->sendForm(new RankUpForm($sender));
    }
}