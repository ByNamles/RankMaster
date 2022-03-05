<?php

namespace ByNamles\Rank\forms;

use ByNamles\Rank\Rank;
use ByNamles\Rank\utils\RankUtils;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class RankUpForm extends MenuForm
{

    /** @var Rank */
    private Rank $plugin;

    /** @var string */
    private string $rank;

    public function __construct(Player $player){
        $this->plugin = Rank::getInstance();
        $rank = $this->plugin->getPlayerRank($player->getName());
        $this->rank = RankUtils::getNextRank($rank);
        parent::__construct(
            TextFormat::GREEN . "Rank Menu",TextFormat::AQUA . "Now rank: " . $rank . "\n" . TextFormat::RED . "Required  money to rank up: " . TextFormat::YELLOW . RankUtils::getRankMoney($this->rank) . TextFormat::DARK_PURPLE . " " . $this->plugin->economy->getMonetaryUnit(),
            [
                new MenuOption(TextFormat::GREEN . "Rank up!")
            ], function(Player $player, int $selected) use ($rank): void{
                if ($selected === 0) {
                    $this->plugin = Rank::getInstance();
                    $money = RankUtils::getRankMoney($this->rank);
                    if ($money > $this->plugin->economy->myMoney($player)) {
                        $player->sendMessage(TextFormat::RED . "You don't afford next rank's cost.");
                        return;
                    }

                    $this->plugin->setRank($player, $this->rank);
                    $this->plugin->economy->reduceMoney($player, $money);

                    $player->sendMessage(TextFormat::GREEN . "You have ranked up " . TextFormat::YELLOW . $this->rank . TextFormat::GREEN . "by paying " . TextFormat::AQUA . $money . TextFormat::DARK_PURPLE . $this->plugin->economy->getMonetaryUnit() . ".");
                }
                });
    }

    
}