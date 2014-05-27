<?php
interface ICache
{
	public function _connect();
	public function set($key, $var, $expires);
	public function multiset($var, $expires);
	public function increment($key, $value);
	public function get($key);
	public function multiget($keys);	
	public function delete($key);
}