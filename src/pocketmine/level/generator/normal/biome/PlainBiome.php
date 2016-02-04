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

namespace pocketmine\level\generator\normal\biome;

use pocketmine\level\generator\populator\TallGrass;
use pocketmine\level\generator\populator\Flowers;

use pocketmine\block\Block;
use pocketmine\block\Flower;

class PlainBiome extends GrassyBiome{

	public function __construct(){
		parent::__construct();

		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(12);
		
		$flowers = new Flowers();
		$flowers->setBaseAmount(2);
		$flowers->addType([Block::DANDELION, 0]);
		$flowers->addType([Block::RED_FLOWER, Flower::TYPE_POPPY]);
		$flowers->addType([Block::RED_FLOWER, Flower::TYPE_AZURE_BLUET]);
		$flowers->addType([Block::RED_FLOWER, Flower::TYPE_RED_TULIP]);
		$flowers->addType([Block::RED_FLOWER, Flower::TYPE_ORANGE_TULIP]);
		$flowers->addType([Block::RED_FLOWER, Flower::TYPE_WHITE_TULIP]);
		$flowers->addType([Block::RED_FLOWER, Flower::TYPE_PINK_TULIP]);
		$flowers->addType([Block::RED_FLOWER, Flower::TYPE_OXEYE_DAISY]);

		$this->addPopulator($tallGrass);
		$this->addPopulator($flowers);

		$this->setElevation(63, 74);

		$this->temperature = 0.8;
		$this->rainfall = 0.4;
	}

	public function getName(){
		return "Plains";
	}
}