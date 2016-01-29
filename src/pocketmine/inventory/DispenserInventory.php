<?php

namespace pocketmine\inventory;

use pocketmine\block\Dispenser;

class DispenserInventory extends ContainerInventory{
	public function __construct(Dispenser $block){
		parent::__construct($tile, InventoryType::get(InventoryType::DISPENSER));
	}
	
    public function getInventory(){
        return $this->inventory;
    }
}
