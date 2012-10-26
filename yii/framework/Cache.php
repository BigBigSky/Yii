<?php
//设置缓存的静态类
//需要开启main.php中的Cache项
class Cache {
	
	public static $_cache;
	
	public static $_id_prefix = 'mem_';
	
	public static $_month = 2592000;
	
	public static $_week = 604800;
	
	public static $_day = 86400;
	
	public static function _Cache() {
		if(!self::$_cache)
			self::$_cache = Yii::app ()->cache;
		return self::$_cache;
	}
	
	//设cache
	public static function set($id = NULL, $value = NULL, $time = 86400) {
		if ($id)
			self::_Cache()->set ( self::$_id_prefix . $id, $value, $time );
		else
			Log::traceLog ( 'set cache failed! id=' . $id . ' value=' . $value );
	}
	
	//取cache(一次取一个id)
	public static function get($id = NULL) {
		if ($id) {
			$get = self::_Cache()->get ( self::$_id_prefix . $id );
			if (! $get)
				return NULL;
			return $get;
		}
		return NULL;
	}
	
	//取cache(一次取多个id)
	public static function gets(array $ids) {
		if (! empty ( $ids )) {
			foreach ( $ids as $id ) {
				$gets [$id] = self::get ( $id );
			}
			return $gets;
		}
		return NULL;
	}
	
	//删除cache
	public static function delete($id = NULL) {
		if ($id) {
			self::_Cache()->delete ( self::$_id_prefix . $id );
		}
	}
	
	//全部刷新cache,这个要谨慎使用
	public static function flush() {
		self::_Cache()->flush ();
	}

}

?>