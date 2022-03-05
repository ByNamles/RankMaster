<?php

namespace ByNamles\Rank;

use JetBrains\PhpStorm\Pure;
use ByNamles\Rank\utils\RankUtils;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\TextFormat;

class RankListener implements Listener{

    /** @var Rank */
    private Rank $plugin;

    #[Pure] public function __construct(){
        $this->plugin = Rank::getInstance();
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $data = $this->plugin->data;
        if(!$data->exists($player->getName())){
            $this->plugin->setRank($player, RankUtils::getFirstRank());
        }
    }

    public function onBreak(BlockBreakEvent $event){
        $block = $event->getBlock();
        $player = $event->getPlayer();
        $perm = $this->plugin->isRankMine($block->getPosition()->asPosition());
        if($perm !== false){
            if(!$player->hasPermission($perm)){
                $player->sendMessage(TextFormat::RED . "You don't have permission to break this place.");
                $event->cancel();
            }
        }
    }
}