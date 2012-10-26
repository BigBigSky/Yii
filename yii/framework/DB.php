<?php
//操作数据库的类
//需要开启main.php中的DB项
abstract class DB {
	
	public static $_db;
	//开启单例模式
	//保证一个用户的一个页面只需要实例化一次DB
	public static function _DB() {
		if(!self::$_db){
			self::$_db = Yii::app()->db;
			//self::$_db = CActiveRecord::model()->getDbConnection();
			//log::simpleLog('重新实例化DB！');
		}
		return self::$_db;
	}
	
	//防注入的sql语句执行函数
	//eg: $sql = "SELECT * FROM `arean WHERE `id`=':id' AND `type`=':type'";
	//eg: $params = array(':id'=>1,':type'=>1);
	public static function queryAllSafe($sql,array $params) {
		$sql = strtr($sql, $params);
		return self::queryAll($sql);
	}
	
	//防注入的sql语句执行函数
	//eg: $sql = "SELECT * FROM `arean WHERE `id`=':id' AND `type`=':type'";
	//eg: $params = array(':id'=>1,':type'=>1);
	public static function querySafe($sql,array $params) {
		$sql = strtr($sql, $params);
		return self::query($sql);
	}
	
	//以模块方式运行
	//eg: $sql = "SELECT * FROM `arean` WHERE `id`=':id' AND `type`=':type'";
	//eg: $params = array(':id'=>1,':type'=>1);
	//返回自增的ID
	public static function saveArray(array $input_files,$model) {
		foreach ($input_files as $key => $value) {
			$model->$key = $value;
		}	
		$model->insert();
		return $model->getPrimaryKey();
	}
	
	//queryAll
	//DB的统一入口函数
	public static function queryAll($sql,$safeMode=false) {
		$type = substr($sql, 0, 1);
		if($type == 'S' || $type == 's' ){
			return self::find($sql);
		}elseif($type == 'U' || $type == 'u' ){
			return self::update($sql);
		}elseif($type == 'I' || $type == 'i' ){
			return self::save($sql);
		}elseif($type == 'D' || $type == 'd' ){
			return self::delete($sql,$safeMode);
		}elseif($type == 'A' || $type == 'a' ){
			return self::alter($sql,$safeMode);
		}elseif($type == 'T' || $type == 't' ){
			return self::truncate($sql,$safeMode);
		}
	}
	
	//queryOne
	public static function query($sql,$safeMode=false) {
		$type = substr($sql, 0, 1);
		if($type == 'S' || $type == 's' ){
			return self::findOne($sql);
		}elseif($type == 'U' || $type == 'u' ){
			return self::update($sql);
		}elseif($type == 'I' || $type == 'i' ){
			return self::save($sql);
		}elseif($type == 'D' || $type == 'd' ){
			return self::delete($sql,$safeMode);
		}elseif($type == 'A' || $type == 'a' ){
			return self::alter($sql,$safeMode);
		}elseif($type == 'T' || $type == 't' ){
			return self::truncate($sql,$safeMode);
		}
	}
	
	//events
	//eg: $sqls = array("UPDATE `account` SET `updateTime`=0 WHERE `identity` = 123456");
	public static function transaction(array $sqlArr){
		$transaction=DB::_DB()->beginTransaction();
		try
		{
			foreach ($sqlArr as $sql) {
				self::_DB()->createCommand($sql)->execute();
			}
		    $transaction->commit();
		}
		catch(Exception $e)
		{
			log::simpleLog($e->getMessage());
		    $transaction->rollBack();
		}
	}
	
	//findOne
	//eg: $sql = "SELECT * FROM `account` WHERE `identity` = 123456";
	public static function findOne($sql) {
		$get = self::_DB()->createCommand($sql)->queryAll();
		if(!empty($get))
			return $get['0'];
		return NULL;
	}
	
	//find
	//eg: $sql = "SELECT * FROM `account` WHERE `identity` = 123456";
	public static function find($sql) {		
		return self::_DB()->createCommand($sql)->queryAll();
	}
	
	//save
	//eg: $sql = "INSERT INTO `account`(`identity`,`name`,...) vALUES ('123','123456',...)";
	public static function save($sql) {
		return self::_DB()->createCommand($sql)->execute();
	}
	
	//update
	//eg: $sql = "UPDATE `account` SET update_time = $time WHERE `identity` = 123456";
	public static function update($sql) {
		return self::_DB()->createCommand($sql)->execute();
	}
	
	//delete
	//eg: $sql = "DELETE FROM `account` WHERE `identity` = 123456";
	public static function delete($sql,$safeMode=false) {
		if($safeMode !== TRUE)return;
		return self::_DB()->createCommand($sql)->execute();
	}
	
	//other
	//eg: $sql = "ALTER TABLE `player` ADD `cost_energy` INT( 10 ) NULL DEFAULT '0' AFTER `energy`";
	public static function alter($sql,$safeMode=false) {
		if($safeMode !== TRUE)return;
		return self::_DB()->createCommand($sql)->execute();
	}
	//eg: $sql = "TRUNCATE TABLE `arena_recent_rank";
	public static function truncate($sql,$safeMode=false) {
		if($safeMode !== TRUE)return;
		return self::_DB()->createCommand($sql)->execute();
	}
	
	//实例化一个query
	public static function createQuery($sql){
		return self::_DB()->createCommand($sql)->query();
	}
	
}

	//对于大量数据的写入，推荐使用下面这种方法
	/*
	$sql = "SELECT * FROM `player`";
			
	$dataReader = DB::createQuery($sql);
	
	while(($row = $dataReader->read())!==false) {
 
		$model = new Player();
		
		unset($row['id']);
		
		DB::saveArray($row, $model);
	}*/
	
	//事务处理
	/*
	$transaction=self::_DB()->beginTransaction();
	try
	{
	   self::_DB()->createCommand($sql1)->execute();
	   self::_DB()->createCommand($sql2)->execute();
	   //.... 其他的SQL操作
	   $transaction->commit();
	}
	catch(Exception $e)
	{
	   $transaction->rollBack();
	}*/
	
		

?>


