<?php
namespace common\helpers;

use Yii;
use yii\helpers\FileHelper as baseFileHelper;

/**
 * FileHelper Model
 * 文件助手模型
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class FileHelper extends baseFileHelper
{
	/**
	 * removeFolder(String $path, Boolean $removeFolder = true, Boolean $removeSelf = true)
	 * 移除文件夹
	 * ------------------------------------------------------------------------------------
	 * @param String $path 路径
	 * @param Boolean $removeFolder 是否移除文件夹
	 * @param Boolean $removeSelf 是否移除自身
	 * -------------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function removeFolder($path, $removeFolder = true, $removeSelf = true){
		$path = Yii::getAlias($path);
		$files = is_dir($path) ? self::findFiles($path) : [];
		foreach($files as $key => $value){
			unlink($value);
		}
		if($removeFolder === true){
			$folders = self::findFolders($path, false, false);
			rsort($folders);
			foreach($folders as $key => $value){
				rmdir($value);
			}
			if($removeSelf === true){
				rmdir($path);
			}
		}
		return true;
	}

	/**
	 * findFolders(String $path, Boolean $sameLevel = true, Boolean $asTree = true)
	 * 查找文件夹
	 * ----------------------------------------------------------------------------
	 * @param String $path 路径
	 * @param Boolean $sameLevel 是否只查找本级目录
	 * @param Boolean $asTree 是否以树的形式返回
	 * --------------------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public static function findFolders($path, $sameLevel = false, $asTree = true){
		$path = Yii::getAlias($path);
		$model = dir($path);
		$folders = [];
		while($file = $model->read()){
			if(is_dir($path . DIRECTORY_SEPARATOR . $file) && $file != '.' && $file != '..'){
				if($sameLevel === true){
					$folders[] = $path . DIRECTORY_SEPARATOR . $file;
				}else{
					$folders[] = $path . DIRECTORY_SEPARATOR . $file;
					$folders[] = self::findFolders($path . DIRECTORY_SEPARATOR . $file, false);
				}
			}
		}
		$model->close();
		$folders = array_filter($folders);
		if($asTree !== true){
			$folders = self::convertTreeToArray($folders);
			sort($folders);
		}
		return $folders;
	}

	/**
	 * convertTreeToArray(Array $tree)
	 * 将树转换为一维数组
	 * -------------------------------
	 * @param Array $tree 树数组
	 * -------------------------
	 * @return Array
	 * @author Verdient。
	 */
	private static function convertTreeToArray(Array $tree){
		$array = [];
		foreach($tree as $key => $value){
			if(is_array($value)){
				$array = array_merge($array, self::convertTreeToArray($value));
			}else{
				$array[] = $value;
			}
		}
		return $array;
	}
}