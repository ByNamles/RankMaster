<?php

namespace ByNamles\Rank\utils;

use JetBrains\PhpStorm\Pure;
use ByNamles\Rank\Rank;

class RankUtils{

    #[Pure] public static function getRanks() : array{
        return Rank::getInstance()->config->getAll(true);
    }

    public static function getFirstRank() : string{
        $array = self::getRanks();
        return array_shift($array);
    }

    public static function getEndRank() : string{
        $array = self::getRanks();
        return array_pop($array);
    }

    #[Pure] public static function getNextRank(string $rank) : string{
        return self::getRanks()[array_search($rank, self::getRanks()) + 1];
    }

    public static function getRankMoney(string $rank) : int{
        return floor((int) Rank::getInstance()->config->get($rank)["money"]);
    }

    public static function getRankPermissions(string $rank) : array{
        return Rank::getInstance()->config->get($rank)["perms"];
    }
}