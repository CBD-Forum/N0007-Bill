<?php
namespace common\helpers;

use Yii;
use yii\base\Component;
use yii\di\Instance;
use yii\db\Connection;


/**
 * DatabaseHelper Model
 * 数据库助手模型
 * --------------------
 * @version 1.0.0
 * @author Verdient。
 */
class DatabaseHelper extends Component
{
	/**
	 * hasTable(String $table)
	 * 判断数据库中是否含有指定的表
	 * ----------------------------
	 * @param String $table 表名称
	 * ----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function hasTable($table){
		return Yii::$app->db->schema->getTableSchema($table, true) === null ? false : true;
	}

	/**
	 * createTable(String $table, Array $columns[, String $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'])
	 * 创建数据表
	 * --------------------------------------------------------------------
	 * @param String $table 表名称
	 * @param Array $columns 数据表列
	 * @param String $options 选项
	 * --------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function createTable($table, $columns, $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'){
		return Yii::$app->db->createCommand()->createTable($table, $columns, $options)->execute();
	}

	/**
	 * dropTable(String $tableName)
	 * 删除数据表
	 * ----------------------------
	 * @param String $table 表名称
	 * ----------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function dropTable($table){
		return Yii::$app->db->createCommand()->dropTable($table)->execute();
	}

	/**
	 * addForeignKey(String $name, String $table, String $columns, String $refTable, String $refColumns[, String $delete = null, String $update = null])
	 * 创建外键约束
	 * -------------------------------------------------------------------------------------------------------------------------------------------------
	 * @param String $name 名称
	 * @param String $table 表名称
	 * @param String $columns 列名称
	 * @param String $refTable 参考表名称
	 * @param String $refColumns 参考列名称
	 * @param String $delete 删除时的操作
	 * @param String $update 更新时的操作
	 * ------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete = null, $update = null){
		return Yii::$app->db->createCommand()->addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update)->execute();
	}

	/**
	 * dropForeignKey(String $name, String $table)
	 * 删除外键约束
	 * -------------------------------------------
	 * @param String $name 名称
	 * @param String $table 表名称
	 * ---------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function dropForeignKey($name, $table){
		return Yii::$app->db->createCommand()->dropForeignKey($name, $table)->execute();
	}

	/**
	 * createIndex(String $name, String $table, String $columns, Boolean $unique = false)
	 * 创建索引
	 * ----------------------------------------------------------------------------------
	 * @param String $name 名称
	 * @param String $table 表名称
	 * @param String $columns 列名称
	 * @param Boolean $unique 是否唯一索引
	 * ---------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function createIndex($name, $table, $columns, $unique = false){
		return Yii::$app->db->createCommand()->createIndex($name, $table, $columns, $unique)->execute();
	}

	/**
	 * dropIndex(String $name, String $table, String $columns, Boolean $unique = false)
	 * 删除索引
	 * ----------------------------------------------------------------------------------
	 * @param String $name 名称
	 * @param String $table 表名称
	 * @param String $columns 列名称
	 * @param Boolean $unique 是否唯一索引
	 * ---------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function dropIndex($name, $table){
		return Yii::$app->db->createCommand()->dropIndex($name, $table)->execute();
	}

	/**
	 * queryBySql(String $sql)
	 * 运行SQL语句
	 * --------------------------
	 * @param String $sql SQL语句
	 * ---------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public static function queryBySql($sql){
		return Yii::$app->db->createCommand($sql)->execute();
	}

	/**
	 * truncateTable(String $tableName)
	 * 清空表
	 * --------------------------------
	 * @param String $tableName 表名称
	 * -------------------------------
	 * @return Mixed
	 * @author Verdient。
	 */
	public static function truncateTable($tableName){
		return Yii::$app->db->createCommand()->truncateTable($tableName)->execute();
	}
}