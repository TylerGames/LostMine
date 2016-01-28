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

namespace pocketmine\tile;

use pocketmine\inventory\HopperInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;

use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\Int;

use pocketmine\nbt\tag\String;

class Hopper extends Spawnable implements InventoryHolder, Container, Nameable{

    /** @var HopperInventory */
    protected $inventory;

    public function __construct(FullChunk $chunk, Compound $nbt){
        parent::__construct($chunk, $nbt);
        $this->inventory = new HopperInventory($this);

        if(!isset($this->namedtag->Items) or !($this->namedtag->Items instanceof Enum)){
            $this->namedtag->Items = new Enum("Items", []);
            $this->namedtag->Items->setTagType(NBT::TAG_Compound);
        }

        for($i = 0; $i < $this->getSize(); ++$i){
            $this->inventory->setItem($i, $this->getItem($i));
        }
    }

    public function close(){
        if($this->closed === false){
            foreach($this->getInventory()->getViewers() as $player){
                $player->removeWindow($this->getInventory());
            }

            foreach($this->getRealInventory()->getViewers() as $player){
                $player->removeWindow($this->getRealInventory());
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

    /**
     * @return int
     */
    public function getSize(){
        return 27;
    }

    /**
     * @param $index
     *
     * @return int
     */
    protected function getSlotIndex($index){
        foreach($this->namedtag->Items as $i => $slot){
            if((int) $slot["Slot"] === (int) $index){
                return (int) $i;
            }
        }

        return -1;
    }

    /**
     * This method should not be used by plugins, use the Inventory
     *
     * @param int $index
     *
     * @return Item
     */
    public function getItem($index){
        $i = $this->getSlotIndex($index);
        if($i < 0){
            return Item::get(Item::AIR, 0, 0);
        }else{
            return NBT::getItemHelper($this->namedtag->Items[$i]);
        }
    }

    /**
     * This method should not be used by plugins, use the Inventory
     *
     * @param int  $index
     * @param Item $item
     *
     * @return bool
     */
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

    /**
     * @return HopperInventory
     */
    public function getInventory(){
        if($this->isPaired()){
            $this->checkPairing();
        }
        return $this->inventory instanceof HopperInventory;
    }

    /**
     * @return HopperInventory
     */
    public function getRealInventory(){
        return $this->inventory;
    }

    protected function checkPairing(){
        if(($pair = $this->getPair()) instanceof Hopper){
            if(!$pair->isPaired()){
                $pair->createPair($this);
                $pair->checkPairing();
            }else{

            }
        }
    }

    public function getName(){
        return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Hopper";
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

    public function isPaired(){
        if(!isset($this->namedtag->pairx) or !isset($this->namedtag->pairz)){
            return false;
        }

        return true;
    }

    /**
     * @return Hopper
     */
    public function getPair(){
        if($this->isPaired()){
            $tile = $this->getLevel()->getTile(new Vector3((int) $this->namedtag["pairx"], $this->y, (int) $this->namedtag["pairz"]));
            if($tile instanceof Hopper){
                return $tile;
            }
        }

        return null;
    }

    public function pairWith(Hopper $tile){
        if($this->isPaired() or $tile->isPaired()){
            return false;
        }

        $this->createPair($tile);

        $this->spawnToAll();
        $tile->spawnToAll();
        $this->checkPairing();

        return true;
    }

    private function createPair(Hopper $tile){
        $this->namedtag->pairx = new Int("pairx", $tile->x);
        $this->namedtag->pairz = new Int("pairz", $tile->z);

        $tile->namedtag->pairx = new Int("pairx", $this->x);
        $tile->namedtag->pairz = new Int("pairz", $this->z);
    }

    public function unpair(){
        if(!$this->isPaired()){
            return false;
        }

        $tile = $this->getPair();
        unset($this->namedtag->pairx, $this->namedtag->pairz);

        $this->spawnToAll();

        if($tile instanceof Hopper){
            unset($tile->namedtag->pairx, $tile->namedtag->pairz);
            $tile->checkPairing();
            $tile->spawnToAll();
        }
        $this->checkPairing();

        return true;
    }

    public function getSpawnCompound(){
        if($this->isPaired()){
            $h = new Compound("", [
                new String("id", Tile::HOPPER),
                new Int("x", (int) $this->x),
                new Int("y", (int) $this->y),
                new Int("z", (int) $this->z),
                new Int("pairx", (int) $this->namedtag["pairx"]),
                new Int("pairz", (int) $this->namedtag["pairz"])
            ]);
        }else{
            $h = new Compound("", [
                new String("id", Tile::HOPPER),
                new Int("x", (int) $this->x),
                new Int("y", (int) $this->y),
                new Int("z", (int) $this->z)
            ]);
        }

        if($this->hasName()){
            $h->CustomName = $this->namedtag->CustomName;
        }

        return $h;
    }
}