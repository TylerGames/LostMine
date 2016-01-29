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

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\Int;
use pocketmine\nbt\tag\String;
use pocketmine\tile\Hopper as TileHopper;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Player;
use pocketmine\tile\Tile;

class Hopper extends Transparent{

    protected $id = self::HOPPER;

    public function __construct($meta = 0){
        $this->meta = $meta;
    }

    public function getName(){
        return "Hopper";
    }

    public function getHardness(){
        return 3;
    }

    public function getToolType(){
        return Tool::TYPE_PICKAXE;
    }

    public function onActivate(Item $item, Player $player = null){
        if($player instanceof Player){
            $top = $this->getSide(1);
            if($top->isTransparent() !== true){
                return true;
            }

            $t = $this->getLevel()->getTile($this);
            $hopper = null;
            if($t instanceof TileHopper){
                $hopper = $t;
            }else{
                $nbt = new Compound("", [
                    new Enum("Items", []),
                    new String("id", Tile::HOPPER),
                    new Int("x", $this->x),
                    new Int("y", $this->y),
                    new Int("z", $this->z)
                ]);
                $nbt->Items->setTagType(NBT::TAG_Compound);
                $hopper = Tile::createTile("Hopper", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
            }

            if(isset($hopper->namedtag->Lock) and $hopper->namedtag->Lock instanceof String){
                if($hopper->namedtag->Lock->getValue() !== $item->getCustomName()){
                    return true;
                }
            }

            $player->addWindow($hopper->getInventory());
        }

        return true;
    }

    public function getDrops(Item $item){
        if($item->isPickaxe() >= Tool::TIER_WOODEN){
            return [[$this->id, 0, 1]];
        }else{
            return[];
        }
    }

    public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
        $faces = [
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
        ];

        $hopper = null;
        $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];

        for($side = 2; $side <= 5; ++$side){
            if(($this->meta === 4 or $this->meta === 5) and ($side === 4 or $side === 5)){
                continue;
            }elseif(($this->meta === 3 or $this->meta === 2) and ($side === 2 or $side === 3)){
                continue;
            }
            $h = $this->getSide($side);
            if($h instanceof Hopper and $h->getDamage() === $this->meta){
                $tile = $this->getLevel()->getTile($h);
                if($tile instanceof TileHopper and !$tile->isPaired()){
                    $hopper = $tile;
                    break;
                }
            }
        }

        $this->getLevel()->setBlock($block, $this, true, true);
        $nbt = new Compound("", [
            new Enum("Items", []),
            new String("id", Tile::HOPPER),
            new Int("x", $this->x),
            new Int("y", $this->y),
            new Int("z", $this->z)
        ]);
        $nbt->Items->setTagType(NBT::TAG_Compound);

        if($item->hasCustomName()){
            $nbt->CustomName = new String("CustomName", $item->getCustomName());
        }

        if($item->hasCustomBlockData()){
            foreach($item->getCustomBlockData() as $key => $v){
                $nbt->{$key} = $v;
            }
        }

        $tile = Tile::createTile("Hopper", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);

        if($hopper instanceof TileHopper and $tile instanceof TileHopper){
            $hopper->pairWith($tile);
            $tile->pairWith($hopper);
        }

        return true;
    }
}