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
declare(strict_types=1);

namespace pocketmine\level;

use pocketmine\level\format\FullChunk;

class SimpleChunkManager implements ChunkManager{

	/** @var FullChunk[] */
	protected $chunks = [];

	protected $seed;

	public function __construct($seed){
		$this->seed = $seed;
	}

	/**
	 * Gets the raw block id.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int 0-255
	 */
	public function getBlockIdAt(int $x, int $y, int $z) : int{
		if($chunk = $this->getChunk($x >> 4, $z >> 4)){
			return $chunk->getBlockId($x & 0xf, $y & 0x7f, $z & 0xf);
		}
		return 0;
	}

	/**
	 * Sets the raw block id.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $id 0-255
	 */
	public function setBlockIdAt(int $x, int $y, int $z, int $id){
		if($chunk = $this->getChunk($x >> 4, $z >> 4)){
			$chunk->setBlockId($x & 0xf, $y & 0x7f, $z & 0xf, $id);
		}
	}

	/**
	 * Gets the raw block metadata
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int 0-15
	 */
	public function getBlockDataAt(int $x, int $y, int $z) : int{
		if($chunk = $this->getChunk($x >> 4, $z >> 4)){
			return $chunk->getBlockData($x & 0xf, $y & 0x7f, $z & 0xf);
		}
		return 0;
	}

	/**
	 * Sets the raw block metadata.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $data 0-15
	 */
	public function setBlockDataAt(int $x, int $y, int $z, int $data){
		if($chunk = $this->getChunk($x >> 4, $z >> 4)){
			$chunk->setBlockData($x & 0xf, $y & 0x7f, $z & 0xf, $data);
		}
	}

	/**
	 * @param int $chunkX
	 * @param int $chunkZ
	 *
	 * @return FullChunk|null
	 */
	public function getChunk(int $chunkX, int $chunkZ){
		return isset($this->chunks[$index = Level::chunkHash($chunkX, $chunkZ)]) ? $this->chunks[$index] : null;
	}

	/**
	 * @param int $chunkX
	 * @param int $chunkZ
	 * @param FullChunk $chunk
	 */
	public function setChunk(int $chunkX, int $chunkZ, FullChunk $chunk = null){
		if($chunk === null){
			unset($this->chunks[Level::chunkHash($chunkX, $chunkZ)]);
			return;
		}
		$this->chunks[Level::chunkHash($chunkX, $chunkZ)] = $chunk;
	}

	public function cleanChunks(){
		$this->chunks = [];
	}

	/**
	 * Gets the level seed
	 *
	 * @return int
	 */
	public function getSeed() : int{
		return $this->seed;
	}
}