<?php

namespace pocketmine\entity;

use pocketmine\block\Dispenser;

class  extends ContainerInventory{
	public function __construct(Dispenser $block){
		parent::__construct($block, InventoryType::get(InventoryType::DISPNSER));
	}
