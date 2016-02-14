<?php
/**
 * Author: PeratX
 * Time: 2015/12/24 22:03
 * Copyright(C) 2011-2015 iTX Technologies LLC.
 * All rights reserved.
 *
 * OpenGenisys Project
 */
namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\protocol\BlockEventPacket;

class NoteblockSound extends GenericSound{
	protected $instrument;
	protected $pitch;

	const INSTRUMENT_PIANO_OR_HARP = 0; // Any other material
	const INSTRUMENT_BASS_DRUM = 1; // Stone, SandStone, Ores, Brick, NetherRack, Opsidian, Quartz
	const INSTRUMENT_SNARE_DRUM = 2; // Sand, Gravel, SoulSand
	const INSTRUMENT_CLICKS_AND_STICKS = 3; // Glass, GlowStone
	const INSTRUMENT_BASS_GUITAR = 4; // Wood, Mushroom, Daylight Sensor, Wooden plate

	public function __construct(Vector3 $pos, $instrument = self::INSTRUMENT_PIANO_OR_HARP, $pitch = 0){
		parent::__construct($pos, 0);
		$this->instrument = $instrument;
		$this->pitch = $pitch;
	}

	public function encode(){
		$pk = new BlockEventPacket();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->case1 = $this->instrument;
		$pk->case2 = $this->pitch;

		return $pk;
	}
}