<?php
//记录错误日志的静态类
//需要开启main.php中的LOG权限
class Log {
	//DEBUG
	public static function debug($var = NULL) {
		echo"<pre>";print_r($var);exit;
	}
	//info级的LOG
	public static function simpleLog($var = NULL, $level = 'info') {
		if (is_array ( $var ) || is_object ( $var )) {
			Yii::log ( ':' . json_encode ( $var ), $level );
		} else {
			Yii::log ( ':' . $var, $level );
		}
	}
	//info级的TRACE
	public static function traceLog($var = NULL, $level = 'info') {
		if (is_array ( $var ) || is_object ( $var )) {
			Yii::trace ( ':' . json_encode ( $var ), $level );
		} else {
			Yii::trace ( ':' . $var, $level );
		}
	}
	//email级的LOG
	public static function emailLog($var = NULL, $level = 'email') {
		if (is_array ( $var ) || is_object ( $var )) {
			Yii::log ( ':' . json_encode ( $var ), $level );
		} else {
			Yii::log ( ':' . $var, $level );
		}
	}
}

?>