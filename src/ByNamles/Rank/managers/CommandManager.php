<?php

namespace ByNamles\Rank\managers;

use ByNamles\Rank\commands\MyRankCommand;
use ByNamles\Rank\commands\RankMineCommand;
use ByNamles\Rank\commands\RankUpCommand;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class CommandManager{

    public static function init() : void{
        foreach(self::getCommands() as $command => $class){
            Server::getInstance()->getCommandMap()->register($command, $class);
        }
        Server::getInstance()->getLogger()->notice(TextFormat::AQUA . count(self::getCommands()) . TextFormat::GREEN . " plugin loaded.");
    }

    public static function getCommands() : array{
        return [
            "myrank" => new MyRankCommand(),
            "rankup" => new RankUpCommand(),
            "rankmine" => new RankMineCommand()
        ];
    }
}