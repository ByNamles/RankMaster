<?php

namespace ByNamles\Rank;

use _64FF00\PureChat\PureChat;
use _64FF00\PurePerms\PurePerms;
use ByNamles\Rank\managers\CommandManager;
use ByNamles\Rank\managers\PermissionManager;
use onebone\economyapi\EconomyAPI;
use pocketmine\world\Position;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Rank extends PluginBase{

    /** @var Rank */
    private static Rank $api;

    /** @var Config */
    public Config $config, $data, $mine;

    /** @var EconomyAPI */
    public EconomyAPI $economy;

    /** @var PurePerms */
    public EconomyAPI $pureperms;

    /** @var PureChat */
    public PureChat $purechat;

    public function onLoad() : void{
        self::$api = $this;
    }

    public function onEnable() : void{
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->data = new Config($this->getDataFolder() . "data.yml", Config::YAML);
        $this->mine = new Config($this->getDataFolder() . "mine.yml", Config::YAML);

        $this->getServer()->getPluginManager()->registerEvents(new RankListener(), $this);

        CommandManager::init();
        PermissionManager::init();

        if(!(class_exists('onebone\economyapi\EconomyAPI') or class_exists('_64FF00\PurePerms\PurePerms') or class_exists('_64FF00\PurePerms\PurePerms'))){
            $this->getLogger()->warning(TextFormat::RED . "Couldn't find required plugins. Plugin is being disabled...");
            $this->setEnabled(false);
        }
        $this->economy = EconomyAPI::getInstance();
        $this->pureperms = $this->getServer()->getPluginManager()->getPlugin('PurePerms');
        $this->purechat = $this->getServer()->getPluginManager()->getPlugin('PureChat');
    }

    public static function getInstance() : Rank{
        return self::$api;
    }

    public function getPlayerRank(string $player) : string{
        return $this->data->get($player);
    }

    public function setRank(Player $player, string $rank) : void{
        $this->purechat->setPrefix($rank, $player);
        $this->data->set($player->getName(), $rank);
        $this->data->save();

        foreach($this->config->get($rank)["perms"] as $perm){
            $this->pureperms->getUserDataMgr()->setPermission($player, $perm);
        }
    }

    public function isRankMine(Position $pos) : ?string{
        foreach($this->data->getAll() as $value){
            if($value["Level"] == $pos->getWorld()->getFolderName()){
                $startPos = self::encodePosition($value["StartPos"]);
                $endPos = self::encodePosition($value["EndPos"]);
                return in_array(floor($pos->x), range($startPos->x, $endPos->x)) and in_array(floor($pos->y), range($startPos->y, $endPos->y)) and in_array(floor($pos->z), range($startPos->z, $endPos->z)) && $value["Perm"];
            }
        }
        return false;
    }

    public static function encodePosition(string $hash) : Vector3{
        $explode = explode(":", $hash);
        return new Vector3((int) $explode[0], (int) $explode[1], (int) $explode[2]);
    }
}