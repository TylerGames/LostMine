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
use pocketmine\level\Level;
use pocketmine\Player;


class UnlitRedstoneTorch extends Flowable implements Redstone,RedstoneSource{

	protected $id = self::UNLIT_REDSTONE_TORCH;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}
	
	public function getLightLevel(){
		return 0;
	}

	public function getName(){
		return "Redstone Torch";
	}

	public function getPower(){
		return 0;
	}
	
	public function BroadcastRedstoneUpdate($type,$power){
		for($side = 1; $side <= 5; $side++){
			$around=$this->getSide($side);
			$this->getLevel()->setRedstoneUpdate($around,Block::REDSTONEDELAY,$type,$power);
		}
	}
	
	public function onRedstoneUpdate($type,$power){
		if($type === Level::REDSTONE_UPDATE_BLOCK_UNCHARGE){
			$this->id = 76;
			$this->getLevel()->setBlock($this, $this, true, false);
			$this->BroadcastRedstoneUpdate(Level::REDSTONE_UPDATE_PLACE, 16);
			return;
		}
		return;
	}
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$below = $this->getSide(0);
			$side = $this->getDamage();
			$faces = [
				1 => 4,
				2 => 5,
				3 => 2,
				4 => 3,
				5 => 0,
				6 => 0,
				0 => 0,
			];
			if($this->getSide($faces[$side])->isTransparent() === true and !($side === 0 and ($below->getId() === self::FENCE or $below->getId() === self::COBBLE_WALL))){
				$this->getLevel()->useBreakOn($this);
				
				return Level::BLOCK_UPDATE_NORMAL;
			}
			
		}
		
		return false;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$below = $this->getSide(0);

		if($target->isTransparent() === false and $face !== 0){
			$faces = [
				1 => 5,
				2 => 4,
				3 => 3,
				4 => 2,
				5 => 1,
			];
			$this->meta = $faces[$face];
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}elseif($below->isTransparent() === false or $below->getId() === self::FENCE or $below->getId() === self::COBBLE_WALL){
			$this->meta = 0;
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}

		return false;
	}

	public function getDrops(Item $item){
		return [
			[Item::REDSTONE_TORCH, 0, 1],
		];
	}
}