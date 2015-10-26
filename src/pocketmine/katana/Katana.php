<?php
/*
 *   _         _
 *  | | ____ _| |_ __ _ _ __   __ _
 *  | |/ / _` | __/ _` | '_ \ / _` |
 *  |   < (_| | || (_| | | | | (_| |
 *  |_|\_\__,_|\__\__,_|_| |_|\__,_|
 *
 *  http://github.com/williamtdr/Katana
 *
 *  This file contains configuration and code relating to Katana's modified functionality.
 */

namespace pocketmine\katana;

use pocketmine\Server;
use pocketmine\utils\Config;

/*
 * Handles Katana's modified functionality and provides an abstraction layer for its modules.
 */

class Katana{
	/** @var Server */
	private $server;

	/** @var Config */
	private $properties;

	private $propertyCache = [];

	/** @var bool|RedirectEngine */
	public $redirect;

	/** @var CacheEngine */
	public $cache;

	/** @var Console */
	public $console;

	private $modules = [];

	public function __construct($server){
		$this->server = $server;

		$this->initConfig();
		$this->initLogger();
		$this->initModules();
	}

	public function getServer(){
		return $this->server;
	}

	public function getProperty($variable, $defaultValue = null){
		if(!array_key_exists($variable, $this->propertyCache)){
			$v = getopt("", ["$variable::"]);
			if(isset($v[$variable])){
				$this->propertyCache[$variable] = $v[$variable];
			}else{
				$this->propertyCache[$variable] = $this->properties->getNested($variable);
			}
		}

		return $this->propertyCache[$variable] === null ? $defaultValue : $this->propertyCache[$variable];
	}

	public function initConfig(){
		if(!file_exists($this->server->getDataPath() . "katana.yml")){
			$content = file_get_contents($this->server->getDataPath() . "src/pocketmine/resources/katana.yml");
			@file_put_contents($this->server->getDataPath() . "katana.yml", $content);
		}
		$this->properties = new Config($this->server->getDataPath() . "katana.yml", Config::YAML, []);
	}

	public function initLogger(){
		$this->server->getLogger()->setSettings([
			"level" => $this->getProperty("console.show-log-level"),
			"thread" => $this->getProperty("console.show-thread"),
			"timestamps" => $this->getProperty("console.show-timestamps")
		]);
	}

	public function initModules(){
		if($this->getProperty("redirect.enable")){
			$this->redirect = new RedirectEngine($this);
			$this->redirect->init();
			$this->modules[] = $this->redirect;
		}

		//TODO: may be it would be better to check if module enabled for each module???
		$this->console = new Console($this);
		$this->console->init();
		$this->modules[] = $this->console;

		if($this->getProperty('caching.enable')){
			$this->cache = new CacheEngine($this);
			$this->cache->init();
			$this->modules[] = $this->cache;
		}
	}

	public function tickModules(){
	}
}
