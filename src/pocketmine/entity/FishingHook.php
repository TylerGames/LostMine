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

namespace pocketmine\entity;

use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\level\particle\SplashParticle;
use pocketmine\item\Item as ItemItem;

class FishingHook extends Projectile{
	const NETWORK_ID = 77;

	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;
	
	protected $gravity = 0.1;
	protected $drag = 0.05;
	
	public $data = 0;
	public $attractTimer = 0;
	
	public function initEntity(){
		parent::initEntity();
		
		if(isset($this->namedtag->Data)){
			$this->data = $this->namedtag["Data"];
		}
		
		$this->setDataProperty(FallingSand::DATA_BLOCK_INFO, self::DATA_TYPE_INT, $this->getData());
	}

	public function __construct(FullChunk $chunk, CompoundTag $nbt, Entity $shootingEntity = null){
		parent::__construct($chunk, $nbt, $shootingEntity);
	}
	
	public function setData($id){
		$this->data = $id;
	}
	
	public function getData(){
		return $this->data;
	}
	
	public function kill(){
		parent::kill();
	}

	public function onUpdate($currentTick){
		if($this->closed){
			return false;
		}
		
		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);

		if($this->isCollidedVertically && $this->isInsideOfWater()){
			$this->motionX = 0;
			$this->motionY += 0.01;
			$this->motionZ = 0;
			$this->motionChanged = true;
			$hasUpdate = true;
		}elseif($this->isCollided && $this->keepMovement === true){
			$this->motionX = 0;
			$this->motionY = 0;
			$this->motionZ = 0;
			$this->motionChanged = true;
			$this->keepMovement = false;
			$hasUpdate = true;
		}
		if($this->attractTimer === 0 && mt_rand(0, 100) <= (0.1 * 100)){ // chance, that a fish bites
			$this->attractTimer = mt_rand(5, 10) * 20; // random delay when a fish bites (5-10 seconds)
		}else{
			$this->attractFish();
			$this->attractTimer--;
		}

		$this->timings->stopTiming();

		return $hasUpdate;
	}
	
	public function attractFish(){
		$this->getLevel()->addParticle(new SplashParticle($this));
	}
	
	public function reelLine(){
		if($this->shootingEntity !== null && $this->shootingEntity instanceof Player && $this->attractTimer > 0 && $this->attractTimer < 2){
			$this->shootingEntity->getInventory()->addItem(ItemItem::get(ItemItem::RAW_FISH, (mt_rand(0, 3))));
		}
		$this->kill();
		$this->close();
	}

	public function spawnTo(Player $player){
		$pk = $this->addEntityDataPacket($player);
		$pk->type = FishingHook::NETWORK_ID;

		$player->dataPacket($pk);
		parent::spawnTo($player);
	}
}