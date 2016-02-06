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

namespace pocketmine;

class ThreadManager extends \Volatile{

	/** @var ThreadManager */
	private static $instance = null;

	public static function init(){
		self::$instance = new ThreadManager();
	}

	/**
	 * @return ThreadManager
	 */
	public static function getInstance(){
		return self::$instance;
	}

	/**
	 * @param Worker|Thread $thread
	 */
	public function add($thread){
		if($thread instanceof Thread or $thread instanceof Worker){
			$this->{spl_object_hash($thread)} = $thread;
		}
	}

	/**
	 * @param Worker|Thread $thread
	 */
	public function remove($thread){
		if($thread instanceof Thread or $thread instanceof Worker){
			unset($this->{spl_object_hash($thread)});
		}
	}

	/**
	 * @return Worker[]|Thread[]
	 */
	public function getAll(){
		$array = [];
		foreach($this as $key => $thread){
			$array[$key] = $thread;
		}

		return $array;
	}
}