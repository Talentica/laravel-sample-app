<?php
include dirname(__FILE__).'/ICache.php';
include dirname(__FILE__).'/BCache.php';
define('MEMCACHE_SERVER', 'localhost:11211');
define('MEMCACHE_SERVER_CLUSTER', 'localhost:11211');
define('MEMCACHE_SERVER_CLUSTER_NEW', 'localhost:11211');
define('MEMCACHE_SERVER_CLUSTER_NEW_MIRROR', null);
define('ENABLE_MEMCACHE_SERVER_MIRRORING', false);
define('CURRENT_ENVIRONMENT', 'prefix');
define('CACHE_API_VERSION', '1.0');

class Memcache extends BCache implements ICache
{
	var $_connected_standalone = false;
	var $_connected_cluster_new = false;
	var $_connected_cluster_new_mirror = false;
	var $_Memcache_standalone = null;
	var $_Memcache_cluster_new = null;
	var $_Memcache_cluster_new_mirror = null;
	const KEY_LENGTH_HASHING_THRESHOLD = 40; 

	var $serverConf = array(
						'servers' => array(MEMCACHE_SERVER),
						'debug'   => false,
						'compress_threshold' => 20000,  // Not used
						'persistant' => true, 
						'enable_compression' => FALSE, // Not used
						'enable_user_level_compression' => 1,
						'min_savings' => 0.2 // Not used.
						);

	function __construct()
	{
		parent::__construct();
		if($this->serverConf['enable_compression'] === TRUE) //Added extra check to avoid double compression.
		{
			$this->serverConf['enable_user_level_compression']=0;
		}
		$this->_connect();
	}

	/**
		* Connect to the memcached server(s)
	*/
	public function _connect() 
	{
		if (defined('DISABLE_CACHE') && (DISABLE_CACHE)) 
		{
			return false;
		}

		$this->_Memcache_standalone =& new Memcached();
		$this->_Memcache_cluster_new =& new Memcached();
				
		if(ENABLE_MEMCACHE_SERVER_MIRRORING)
			$this->_Memcache_cluster_new_mirror =& new Memcached();
		// several servers - use addServer
		foreach ($this->serverConf['servers'] as $server) 
		{
			$parts = explode(':', $server);

			$host = $parts[0];
			$port = isset($parts[1]) ? $parts[1] : 11211; // default port
			if ($this->_Memcache_standalone->addServer($host, $port)) 
			{
				$this->_connected_standalone = true;
			} 
		}
		
		///$this->_Memcache_standalone->setOption(Memcached::OPT_COMPRESSION,$this->serverConf['enable_compression']);

		$server_cluster = explode(',', MEMCACHE_SERVER_CLUSTER_NEW);
		$count_not_connected = 0;
		$count_connected = 0;
		foreach ($server_cluster as $server) 
		{
			$parts = explode(':', $server);
	
			$host = $parts[0];
			$port = isset($parts[1]) ? $parts[1] : 11211; // default port
			if (!$this->_Memcache_cluster_new->addServer($host, $port)) 
			{
				$count_not_connected++;
			}
			else
			{
				$count_connected++;
			}
		}
		
		///$this->_Memcache_standalone->setOption(Memcached::OPT_COMPRESSION,$this->serverConf['enable_compression']);

		if( $count_not_connected == 0 && $count_connected > 0 )
		{
			$this->_connected_cluster_new = true; 
		}
		else
		{
			$this->_connected_cluster_new = false;
		}

		if(ENABLE_MEMCACHE_SERVER_MIRRORING)
		{
			if( MEMCACHE_SERVER_CLUSTER_NEW_MIRROR != null)
			{
				$server_cluster_mirror = explode(',', MEMCACHE_SERVER_CLUSTER_NEW_MIRROR);
				if(count($server_cluster)!=count($server_cluster_mirror))
				{
					return false;
				}
			}
			else
			{
				$server_cluster_mirror = array_reverse($server_cluster);
			}

			$count_not_connected = 0;
			$count_connected = 0;
			foreach ($server_cluster_mirror as $server) 
			{
				$parts = explode(':', $server);
				
				$host = $parts[0];
				$port = isset($parts[1]) ? $parts[1] : 11211; // default port
				if (!$this->_Memcache_cluster_new_mirror->addServer($host, $port)) 
				{
					$count_not_connected++;
				}
				else
				{
					$count_connected++;
				}
			}
			
			///$this->_Memcache_standalone->setOption(Memcached::OPT_COMPRESSION,$this->serverConf['enable_compression']);

			if( $count_not_connected == 0 && $count_connected > 0 )
			{
				$this->_connected_cluster_new_mirror = true; 
			}
			else
			{
				$this->_connected_cluster_new_mirror = false;
			}

			return ($this->_connected_standalone && $this->_connected_cluster_new && $this->_connected_cluster_new_mirror); 
		} 
		else
		{
			return ($this->_connected_standalone && $this->_connected_cluster_new); 
		}
	} 

	public function getKey($key)
	{
		//if(FAM_UI_VERSION_NO != '')
		if((CURRENT_ENVIRONMENT != '') && (CACHE_API_VERSION != ''))
		{
			//$key = FAM_UI_VERSION_NO.'_'.$key;
			$key = CURRENT_ENVIRONMENT.'_'.CACHE_API_VERSION.'_'.$key;
		}
		if(strlen($key)>self::KEY_LENGTH_HASHING_THRESHOLD)
		{
			$key = sha1($key);
		}
		return $key;
	}
	
	/**
	 * Set a value in the cache
	 *
	 * Expiration time is one hour if not set
	 */
	public function set($key, $var, $expires = 0) 
	{
		if ((defined('DISABLE_CACHE') && (DISABLE_CACHE)) || !$this->_connected_cluster_new || (ENABLE_MEMCACHE_SERVER_MIRRORING && !$this->_connected_cluster_new_mirror)) 
		{
			return false;
		}
		if (!is_numeric($expires)) 
		{
			$expires = strtotime($expires);
		}
		
		if ($expires != 0) //If caching is not infinite.
		{
			$expires = time()+$expires;
		}
		
		$key = $this->getKey($key);
		
		//User level compression
		$var = $this->_compressData($var);
		
		if(ENABLE_MEMCACHE_SERVER_MIRRORING)
		{
			@$this->_Memcache_cluster_new_mirror->set($key, $var, $expires);
		}
		return @$this->_Memcache_cluster_new->set($key, $var, $expires);
	}

	/**
	 * Set a value in the cache
	 *
	 * Expiration time is one hour if not set
	 */
	public function multiset($var, $expires = 0) 
	{
		if ((defined('DISABLE_CACHE') && (DISABLE_CACHE)) || !$this->_connected_cluster_new || (ENABLE_MEMCACHE_SERVER_MIRRORING && !$this->_connected_cluster_new_mirror)) 
		{
			return false;
		}
		if (!is_numeric($expires)) 
		{
			$expires = strtotime($expires);
		}
		
		if ($expires != 0) //If caching is not infinite.
		{
			$expires = time()+$expires;
		}
		
		//User level compression
		$var = $this->_compressMultiData($var);
		
		if(ENABLE_MEMCACHE_SERVER_MIRRORING)
		{
			@$this->_Memcache_cluster_new_mirror->setMulti($var, $expires);
		}		
		
		return @$this->_Memcache_cluster_new->setMulti($var, $expires);
	}
	
	public function increment($key, $value)
	{
		if ((defined('DISABLE_CACHE') && (DISABLE_CACHE)) || !$this->_connected_cluster_new || (ENABLE_MEMCACHE_SERVER_MIRRORING && !$this->_connected_cluster_new_mirror)) 
		{
			return false;
		}
	
		$key = $this->getKey($key);
		
		// LOOK IN NEW CLUSTER FIRST
		if(ENABLE_MEMCACHE_SERVER_MIRRORING)
		{
			@$this->_Memcache_cluster_new_mirror->increment($key, $value);
		}
		@$this->_Memcache_cluster_new->increment($key, $value);
	}

	/**
	 * Get a value from cache
	 */
	public function get($key) 
	{
		if ((defined('DISABLE_CACHE') && (DISABLE_CACHE)) || !$this->_connected_cluster_new || (ENABLE_MEMCACHE_SERVER_MIRRORING && !$this->_connected_cluster_new_mirror)) 
		{
			return false;
		}
		
		$key = $this->getKey($key);

		// LOOK IN NEW CLUSTER FIRST
		$ret = @$this->_Memcache_cluster_new->get($key);

		if($ret == '' && ENABLE_MEMCACHE_SERVER_MIRRORING)
		{
			 $ret = @$this->_Memcache_cluster_new_mirror->get($key);	
			 if($ret != '')
			 {
				//can we define some appropriate expiry time?
				@$this->_Memcache_cluster_new->set($key, $ret, time()+3600);
			 }
		}
		
		//User level compression
		$ret = $this->_unCompressData($ret);

		return $ret;
	}

	/**
	 * Get a value from cache
	 */
	public function multiget($keys) 
	{
		if ((defined('DISABLE_CACHE') && (DISABLE_CACHE)) || !$this->_connected_cluster_new || (ENABLE_MEMCACHE_SERVER_MIRRORING && !$this->_connected_cluster_new_mirror)) 
		{
			return false;
		}

		$finalKeys = array();
		$hashKeyToActualKeyMap = array();
		foreach($keys as $key)
		{
			$temp = $this->getKey($key);
			array_push($finalKeys,$temp);
			$hashKeyToActualKeyMap[$temp]=$key;
		}
		$keys = $finalKeys;
		
		// LOOK IN NEW CLUSTER FIRST
		$ret = @$this->_Memcache_cluster_new->getMulti ($keys);

		if($ret == '' && ENABLE_MEMCACHE_SERVER_MIRRORING)
		{
			 $ret = @$this->_Memcache_cluster_new_mirror->getMulti ($keys);
		}
		//User level compression
		$ret = $this->_unCompressMultiData($ret,$hashKeyToActualKeyMap);

		return $ret;
	}
	
	/**
	 * Get a value from standalone cache
	 */
	public function get_standalone($key) 
	{
		if ((defined('DISABLE_CACHE') && (DISABLE_CACHE)) || !$this->_connected_standalone ) 
		{
			return false;
		}
		
		$key = $this->getKey($key);
		
		return @$this->_Memcache_standalone->get($key);
	}

	/**
	 * Remove value from cache
	 */
	public function delete($key) 
	{
		if ((defined('DISABLE_CACHE') && (DISABLE_CACHE)) || !$this->_connected_cluster_new || (ENABLE_MEMCACHE_SERVER_MIRRORING && !$this->_connected_cluster_new_mirror)) 
		{
			return false;
		}
		
		$key = $this->getKey($key);
		
		if(ENABLE_MEMCACHE_SERVER_MIRRORING)
		{
			@$this->_Memcache_cluster_new_mirror->delete($key);	
		}
		return @$this->_Memcache_cluster_new->delete($key);
	}

	/*
		User Level compression
	*/
	private function _compressData($resultSet)
	{
		if($this->serverConf['enable_user_level_compression']===1)
		{
			$finalResSet = gzcompress(serialize($resultSet),9);
			return $finalResSet;
		}
		else
		{
			return $resultSet;
		}
	}

	/*
		User Level compression
	*/
	private function _unCompressData($resultSet)
	{
		if($this->serverConf['enable_user_level_compression']===1)
		{
			$finalResSet =  unserialize(gzuncompress($resultSet));
			return $finalResSet;
		}
		else
		{
			return $resultSet;
		}
	}
	
	/*
		User Level compression
	*/
	private function _compressMultiData($resultSet)
	{
		$finalResSet = array();
		foreach($resultSet as $memcacheKey => $memcacheValue)
		{
			$memcacheKey = $this->getKey($memcacheKey);
			if($this->serverConf['enable_user_level_compression']===1)
			{
				$finalResSet[$memcacheKey] = gzcompress(json_encode($memcacheValue),9);
			}
			else
			{
				$finalResSet[$memcacheKey] = $memcacheValue;
			}
		}
		return $finalResSet;
	}
	
	/*
		User Level compression
	*/
	private function _unCompressMultiData($resultSet,$hashKeyToActualKeyMap)
	{
		foreach($resultSet as $memcacheKey => $memcacheValue)
		{
			$actualKey = $hashKeyToActualKeyMap[$memcacheKey];
			if($this->serverConf['enable_user_level_compression']===1)
			{
				$finalResSet[$actualKey] = json_decode(gzuncompress($memcacheValue),true); 
			}
			else
			{
				$finalResSet[$actualKey] = $memcacheValue;
			}
		}
		return $finalResSet;		
	}
}
?>