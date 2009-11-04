<?php

class Cache {

    private static $cacheObject = null; 	// Cache object
    private static $cachedir = CACHEDIR;
    static private $cache = array();
    static private $STORE_TO_MEMORY = true;
    
    static function store($key, $tocache) {
    	if(!ENABLE_CACHE) {
    		return;
    	}
    	if(self::$STORE_TO_MEMORY) {
    		self::storeToMemory($key, $tocache);
    		return;
    	}
    	$filename = self::getFilename($key);
    	$handle = fopen($filename, "w");
    	DebugUtils::debug("creating cachefile:".$filename);
    	if(!$handle) {
    		throw new RuntimeException("Cannot write to cachefile");
    	}
    	$data =  serialize($tocache);
    	fwrite($handle, $data);
    	fclose($handle);
    }
    
    static function storeToMemory($key, $tocache) {
    if(!ENABLE_CACHE) {
    		return;
    	}
    	if(empty(self::$cache[$key])) {
			self::$cache[$key] = $tocache;
		}
    }
    
    static function getCache($key) {
    	if(self::$STORE_TO_MEMORY) {
    		if(!empty(self::$cache[$key])) {
    			return self::$cache[$key];
    		}
    		return null;
    	}
    	$filename = self::getFileName($key);
    	if(file_exists($filename)) {
    		return unserialize(file_get_contents($filename));
    	}
    	return null;
    }
    
    static function getFileName($key) {
    	return CACHEDIR.md5($key).".cache";
    }
    
    public static function get($key) {
    	if(self::$cacheObject == null) {
    		self::$cacheObject = new Cache();
    	}
    	$filename = self::getFileName($key);
    	return self::getCache($key);
    }
    
}
?>