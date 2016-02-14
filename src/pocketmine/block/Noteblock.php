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
/*
 * Author: PeratX
 * Time: 2015/12/25 16:46
 * Copyright(C) 2011-2015 iTX Technologies LLC.
 * All rights reserved.
 *
 * OpenGenisys Project
*/
namespace pocketmine\block;
use pocketmine\item\Tool;
use pocketmine\item\Item;
use pocketmine\level\sound\NoteblockSound;
use pocketmine\Player;

class Noteblock extends Solid implements RedstoneConsumer{
	protected $id = self::NOTEBLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 0.8;
	}

	public function getResistance(){
		return 4;
	}

	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	public function canBeActivated(){
		return true;
	}

	public function getStrength(){
		return $this->meta;
	}

	public function getInstrument(Block $block){
		switch($block->getId()){
			case self::STONE:
			case self::COBBLESTONE:
			case self::COBBLE_STAIRS:
			case self::BEDROCK:
			case self::GOLD_ORE:
			case self::IRON_ORE:
			case self::COAL_ORE:
			case self::LAPIS_ORE:
			case self::DIAMOND_ORE:
			case self::REDSTONE_ORE:
			case self::EMERALD_ORE:
			case self::GLOWING_REDSTONE_ORE:
			case self::FURNACE:
			case self::BURNING_FURNACE:
			case self::BRICKS:
			case self::BRICK_STAIRS:
			case self::STONE_BRICK:
			case self::STONE_BRICK_STAIRS:
			case self::NETHERRACK:
			case self::COBBLE_WALL:
			case self::STONECUTTER:
			case self::MOSS_STONE:
			case self::OBSIDIAN:
			case self::SANDSTONE:
			case self::END_STONE:
			case self::MONSTER_SPAWNER:
			case self::END_PORTAL_FRAME:
			case self::QUARTZ_BLOCK:
			case self::QUARTZ_STAIRS:
			case self::NETHER_BRICKS:
			case self::NETHER_BRICKS_STAIRS:
			case self::ENCHANT_TABLE:
			case self::STONE_PRESSURE_PLATE:
				return NoteBlockSound::INSTRUMENT_BASS_DRUM;
			case self::SAND:
			case self::GRAVEL:
			case self::SOUL_SAND:
				return NoteBlockSound::INSTRUMENT_SNARE_DRUM;
			case self::GLASS:
			case self::GLASS_PANEL:
			case self::GLOWSTONE:
				return NoteBlockSound::INSTRUMENT_CLICKS_AND_STICKS;
			case self::WOOD:
			case self::WOOD2:
			case self::PLANK:
			case self::SPRUCE_WOOD_STAIRS:
			case self::BIRCH_WOOD_STAIRS:
			case self::JUNGLE_WOOD_STAIRS:
			case self::DOUBLE_WOOD_SLAB:
			case self::ACACIA_WOOD_STAIRS:
			case self::DARK_OAK_WOOD_STAIRS:
			case self::WOOD_STAIRS:
			case self::BOOKSHELF:
			case self::CHEST:
			case self::WORKBENCH:
			case self::SIGN_POST:
			case self::WALL_SIGN:
			case self::WOOD_DOOR_BLOCK:
			case self::SPRUCE_DOOR_BLOCK:
			case self::BIRCH_DOOR_BLOCK:
			case self::JUNGLE_DOOR_BLOCK:
			case self::ACACIA_DOOR_BLOCK:
			case self::DARK_OAK_DOOR_BLOCK:
			case self::TRAPDOOR:
			case self::FENCE:
			case self::FENCE_GATE:
			case self::FENCE_GATE_SPRUCE:
			case self::FENCE_GATE_BIRCH:
			case self::FENCE_GATE_JUNGLE:
			case self::FENCE_GATE_DARK_OAK:
			case self::FENCE_GATE_ACACIA:
			case self::WOOD_SLAB:
			case self::BROWN_MUSHROOM:
			case self::RED_MUSHROOM:
			case self::NOTEBLOCK:
			case self::WOODEN_PRESSURE_PLATE:
			case self::DAYLIGHT_DETECTOR:
			case self::DAYLIGHT_DETECTOR_INVERTED:
				return NoteBlockSound::INSTRUMENT_BASS_GUITAR;
				break;
			case self::SLAB:
			case self::DOUBLE_SLAB:
				if($block->getDamage() == 2){ // Wooden Slab
					return NoteBlockSound::INSTRUMENT_BASS_GUITAR;
				}else{ // else : Stones
					return NoteBlockSound::INSTRUMENT_BASS_DRUM;
				}
				break;
			default:
				return NoteBlockSound::INSTRUMENT_PIANO_OR_HARP;
				break;
		}
	}

	public function onActivate(Item $item, Player $player = null){
		$this->getLevel()->addSound(new NoteblockSound($this, $this->getInstrument($this->getSide(0)), $this->meta));
		$this->meta = (int) ++$this->meta % 25;
		$this->getLevel()->setBlock($this, $this);
		return true;
	}

	public function getName(){
		return "Noteblock";
	}

	/*
	 * overriding Block::onRedstoneUpdate
	 * is causing memory leak if noteblock is activated
	 */
	public function onRedstoneUpdate($type, $power){
		return true;
	}

}