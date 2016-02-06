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

namespace pocketmine\item;

use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Liquid;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\level\Level;
use pocketmine\Player;

use pocketmine\block\Water;
use pocketmine\block\StillWater;
use pocketmine\block\Lava;
use pocketmine\block\StillLava;

class Bucket extends Food{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BUCKET, $meta, $count, "Bucket");
	}

	public function getMaxStackSize() : int{
		return 1;
	}

	public function canBeActivated() : bool{
		return true;
	}

	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
		$bucketContents = Block::get($this->meta);

		if($bucketContents instanceof Air){
		// Bucket Empty so pick up block
			if($target instanceof Liquid and $target->getDamage() === 0){
				$result = clone $this;
				
				if($target instanceof StillWater)
				{
					$toStore = Block::WATER;
				}
				elseif($target instanceof StillLava)
				{
					$toStore = Block::LAVA;
				}
				else
				{
					return false;
				}
				
				$result->setDamage($toStore);
				$player->getServer()->getPluginManager()->callEvent($ev = new PlayerBucketFillEvent($player, $block, $face, $this, $result));
				if(!$ev->isCancelled()){
					$player->getLevel()->setBlock($target, new Air(), true, true);
					if($player->isSurvival()){
						$player->getInventory()->setItemInHand($ev->getItem(), $player);
					}
					return true;
				}else{
					$player->getInventory()->sendContents($player);
				}
			}
		}elseif($bucketContents instanceof Liquid){
			// Bucket Full, so empty
			$result = clone $this;
			$result->setDamage(0);
			
			if($bucketContents instanceof Water)
			{
				$toCreate = Block::STILL_WATER;
			}
			elseif($bucketContents instanceof Lava)
			{
				$toCreate = Block::STILL_LAVA;
			}
			else
			{
				return false;
			}
			
			$bucketContents = Block::get($toCreate);
			
			$player->getServer()->getPluginManager()->callEvent($ev = new PlayerBucketFillEvent($player, $block, $face, $this, $result));
			if(!$ev->isCancelled()){
				$player->getLevel()->setBlock($block, $bucketContents, true, true);
				if($player->isSurvival()){
					$player->getInventory()->setItemInHand($ev->getItem(), $player);
				}
				return true;
			}else{
				$player->getInventory()->sendContents($player);
			}
		}

		return false;
	}
}