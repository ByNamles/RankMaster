<?php

namespace ByNamles\Rank\forms;

use ByNamles\Rank\Rank;
use dktapps\pmforms\{CustomForm, CustomFormResponse};
use dktapps\pmforms\element\Label;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class MyRankForm extends CustomForm{

    public function __construct(Player $player){
        parent::__construct(
            TextFormat::GREEN . "Rank Menu",[
            new Label("element0",TextFormat::AQUA . "Your rank: " . Rank::getInstance()->data->get($player->getName()))
            ], function(Player $player, CustomFormResponse $data):void{
            	
            });
            
        
    }
}