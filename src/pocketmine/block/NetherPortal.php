<?php

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityEnterPortalEvent;

class NetherPortal extends Flowable{
	protected $id = self::NETHER_PORTAL;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getLightLevel(){
		return 15;
	}

	public function getName(){
		return "Nether Portal";
	}

	public function getDrops(Item $item){
		return;
	}

	public function onEntityCollide(Entity $entity){
        Server::getInstance()->getPluginManager()->callEvent($ev = new EntityEnterPortalEvent($this, $entity));
        if(!$ev->isCancelled()) {
			return true;
		}
        return false;
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
