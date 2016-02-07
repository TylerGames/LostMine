<?php
/*
 *
 *  _                       _           _ __  __ _
 * (_)                     (_)         | |  \/  (_)
 *  _ _ __ ___   __ _  __ _ _  ___ __ _| | \  / |_ _ __   ___
 * | | '_ ` _ \ / _` |/ _` | |/ __/ _` | | |\/| | | '_ \ / _ \
 * | | | | | | | (_| | (_| | | (_| (_| | | |  | | | | | |  __/
 * |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|_|  |_|_|_| |_|\___|
 *                     __/ |
 *                    |___/
 *
 * This program is a third party build by ImagicalMine.
 *
 * PocketMine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.ml/
 *
 *
*/

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Player;

class Cauldron extends Solid{

    protected $id = self::CAULDRON;

    public function __construct($meta = 0){
        $this->meta = $meta;
    }

    public function isSolid(){
        return true;
    }

    public function getName(){
        return "Cauldron";
    }

    public function getToolType(){
        return Tool::TYPE_PICKAXE;
    }

    public function getHardness(){
        return 2;
    }

    public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
        $faces = [
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
        ];

        $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];

        $this->getLevel()->setBlock($block, $this, true);
        return true;
    }

    public function getDrops(Item $item){
        if($item->isPickaxe() >= Tool::TIER_WOODEN){
            return [[$this->id, 0, 1]];
        }else{
            return[];
        }
    }


}