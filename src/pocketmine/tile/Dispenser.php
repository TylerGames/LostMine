<?php
/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/
namespace pocketmine\tile;

use pocketmine\inventory\DispenserInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\protocol\ContainerSetDataPacket;
//Bug fixed by MagicDroidX, Genisys and Nukkit Project

class Dispenser extends Spawnable implements InventoryHolder, Container, Nameable{
	/** @var DispenserInventory */
	protected $inventory;

	public function __construct(FullChunk $chunk, Compound $nbt){
		parent::__construct($chunk, $nbt);
		$this->inventory = new DispenserInventory($this);
	}

	public function getSize(){
		return 9;
	}

	/**
	 * @return DispenserInventory
	 */
	public function getInventory(){
		return $this->inventory;
	}

	public function getName(){
		return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Furnace";
	}
	
	public function hasName(){
		return isset($this->namedtag->CustomName);
	}
	
	public function setName($str){
		if($str === ""){
			unset($this->namedtag->CustomName);
			return;
		}
		$this->namedtag->CustomName = new String("CustomName", $str);
	}

	public function getSpawnCompound(){
		$nbt = new Compound("", [
			new String("id", Tile::HOPPER),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z),
		]);

		if($this->hasName()){
			$nbt->CustomName = $this->namedtag->CustomName;
		}

		return $nbt;
	}
	
	public function close(){
		if($this->closed === false){
			foreach($this->getInventory()->getViewers() as $player){
				$player->removeWindow($this->getInventory());
			}
			parent::close();
		}
	}
	
	public function saveNBT(){
		$this->namedtag->Items = new Enum("Items", []);
		$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		for($index = 0; $index < $this->getSize(); ++$index){
			$this->setItem($index, $this->inventory->getItem($index));
		}
	}
	
	protected function getSlotIndex($index){
		foreach($this->namedtag->Items as $i => $slot){
			if($slot["Slot"] === $index){
				return $i;
			}
		}
		return -1;
	}
	
	public function getItem($index){
		$i = $this->getSlotIndex($index);
		if($i < 0){
			return Item::get(Item::AIR, 0, 0);
		}else{
			return NBT::getItemHelper($this->namedtag->Items[$i]);
		}
	}
        
	public function setItem($index, Item $item){
		$i = $this->getSlotIndex($index);
		$d = NBT::putItemHelper($item, $index);
		if($item->getId() === Item::AIR or $item->getCount() <= 0){
			if($i >= 0){
				unset($this->namedtag->Items[$i]);
			}
		}elseif($i < 0){
			for($i = 0; $i <= $this->getSize(); ++$i){
				if(!isset($this->namedtag->Items[$i])){
					break;
				}
			}
			$this->namedtag->Items[$i] = $d;
		}else{
			$this->namedtag->Items[$i] = $d;
		}
		return true;
	}
	
	public function onUpdate(){
		if($this->closed === true){
			return false;
		}
		$this->timings->startTiming();
		foreach($this->getInventory()->getViewers() as $player){
			$windowId = $player->getWindowId($this->getInventory());
			if($windowId > 0){
				$pk = new ContainerSetDataPacket();
				$pk->windowid = $windowId;
				$player->dataPacket($pk);
				$pk = new ContainerSetDataPacket();
				$pk->windowid = $windowId;
				$player->dataPacket($pk);
			}
		}
		$this->lastUpdate = microtime(true);
		$this->timings->stopTiming();
		return true;
	}
}
