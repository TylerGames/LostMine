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
use pocketmine\item\Tool; 

class GlowingObsidian extends Solid{

	protected $id = self::GLOWING_OBSIDIAN;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Glowing Obsidian";
	}
	
	public function getHardness(){
		return 6;
	}

	public function getLightLevel(){
		return 12;
	}
	
	public function getToolType(){ 
 		return Tool::TYPE_PICKAXE; 
 	} 
 	
 	public function getDrops(Item $item){
 		if($item->isPickaxe() >= Tool::TIER_DIAMOND){ 
 			return [ 
 				[Item::OBSIDIAN, 0, 1], 
 			]; 
 		}else{ 
 			return []; 
 		} 
	}
}
