<?php

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityEnterPortalEvent;

class NetherPortal extends Flowable{
	protected $id = self::NETHER_PORTAL;

	public function __construct($meta = 0){
		$this->meta = (int) $meta;
	}

	public function getLightLevel(){
		return 15;
	}

	public function getName(){
		return "Nether Portal";
	}

	/**
	 * Places the Block, using block space and block target, and side. Returns if the block has been placed.
	 *
	 * @param Item   $item
	 * @param Block  $block
	 * @param Block  $target
	 * @param int    $face
	 * @param float  $fx
	 * @param float  $fy
	 * @param float  $fz
	 * @param Player $player = null
	 *
	 * @return bool
	 */
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
	    return $this->getLevel()->setBlock($this, $this, true, true);
	}

	public function getDrops(Item $item){
		return;
	}

	public function onEntityCollide(Entity $entity){
        //Server::getInstance()->getPluginManager()->callEvent($ev = new EntityEnterPortalEvent($this, $entity));
        //if(!$ev->isCancelled()) {
            //TODO: Delayed teleport entity to nether world.
        //}
        return true;
	}

	public function canPassThrough(){
		return true;
	}

	/*
	 * public function canBeReplaced(){
	 * return true;
	 * }
	 */
	// TODO: only source blocks of liquids
}
