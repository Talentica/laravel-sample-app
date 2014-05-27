<?php
class CacheManager 
{
	private static $connectionList = array();
	function __construct()
	{
	}
	public static function create($cache)
	{
		if(array_key_exists($cache,self::$connectionList) && !is_null(self::$connectionList[$cache]))
		{
			return self::$connectionList[$cache];
		}
		$cacheFile = dirname(__FILE__).'/'.$cache.'.php';
		if(!file_exists($cacheFile))
		{
			throw new CacheException('Cache Wrapper you trying to initialize does not exists.',5001);
		}
		try
		{
			require_once($cacheFile);
			self::$connectionList[$cache] = new $cache(123);
			return self::$connectionList[$cache];
		}	
		catch(Exception $e)
		{
		}
	}
}
class CacheException extends Exception
{
	public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }
}