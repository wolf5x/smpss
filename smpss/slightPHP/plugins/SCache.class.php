<?php
/*{{{LICENSE
+-----------------------------------------------------------------------+
| SlightPHP Framework                                                   |
+-----------------------------------------------------------------------+
| This program is free software; you can redistribute it and/or modify  |
| it under the terms of the GNU General Public License as published by  |
| the Free Software Foundation. You should have received a copy of the  |
| GNU General Public License along with this program.  If not, see      |
| http://www.gnu.org/licenses/.                                         |
| Copyright (C) 2008-2009. All Rights Reserved.                         |
+-----------------------------------------------------------------------+
| Supports: http://www.slightphp.com                                    |
+-----------------------------------------------------------------------+
}}}*/

/**
 * @package SlightPHP
 */
if(!defined("SLIGHTPHP_PLUGINS_DIR"))define("SLIGHTPHP_PLUGINS_DIR",dirname(__FILE__));
require_once(SLIGHTPHP_PLUGINS_DIR."/SConfig.class.php");
require_once(SLIGHTPHP_PLUGINS_DIR."/cache/Cache_MemCache.php");
class SCache extends Cache_MemCache{
	private static $_config;
	/**
	 * @deprecated
	 */
	private static $engines=array("file","apc","memcache");

	/**
	 * @deprecated
	 * @return class Db
	 */
	static function getCacheEngine($engine='memcache'){
		$engine = strtolower($engine);
		if(!in_array($engine,self::$engines)){
			return false;
		}
		if($engine=="apc" && extension_loaded("apc")){
			require_once(SLIGHTPHP_PLUGINS_DIR."/cache/CacheObject.php");
			require_once(SLIGHTPHP_PLUGINS_DIR."/cache/Cache_APC.php");
			return new Cache_APC;
		}elseif($engine=="memcache" && extension_loaded("memcache")){
			require_once(SLIGHTPHP_PLUGINS_DIR."/cache/Cache_MemCache.php");
			return new Cache_MemCache;
		}elseif($engine=="file"){
			require_once(SLIGHTPHP_PLUGINS_DIR."/cache/CacheObject.php");
			require_once(SLIGHTPHP_PLUGINS_DIR."/cache/Cache_File.php");
			return new Cache_File;
		}
		return false;
	}
	
	static function setConfigFile($file){
		self::$_config = new SConfig;
		self::$_config->setConfigFile($file);
	}
	static function getConfigFile(){
		return self::$_config->getConfigFile();
	}
	static function useConfig($zone){
		self::addServers(self::getConfig($zone));
	}
	
	/**
	 * @param string $zone="default"
	 * @return array
	 */
	static function getConfig($zone="default"){
		return self::$_config->listConfig($zone,".*");
	}
}
