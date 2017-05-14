<?php
namespace common\components\upload;

use yii\base\Model;

/**
 * FileFormat Model
 * 文件格式模型
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class FileFormat extends Model
{
	/**
	 * @var public static $typeList
	 * 类型映射关系
	 * ----------------------------
	 * @author Verdient。
	 */
	public static $typeList = [
		'FFD8FFE1' => 'jpg',
		'89504E47' => 'png',
		'47494638' => 'gif',
		'49492A00' => 'tif',
		'424D' => 'bmp',
		'41433130' => 'dwg',
		'38425053' => 'psd',
		'7B5C727466' => 'rtf',
		'3C3F786D6C' => 'xml',
		'68746D6C3E' => 'html',
		'44656C69766572792D646174' => 'eml',
		'CFAD12FEC5FD746F' => 'dbx',
		'2142444E' => 'pst',
		'D0CF11E0' => 'xls/doc',
		'5374616E64617264204A' => 'mdb',
		'FF575043' => 'wpd',
		'252150532D41646F6265' => 'eps/ps',
		'255044462D312E' => 'pdf',
		'E3828596' => 'pwl',
		'504B0304' => 'zip/docx',
		'52617221' => 'rar',
		'57415645' => 'wav',
		'41564920' => 'avi',
		'2E7261FD' => 'ram',
		'2E524D46' => 'rm',
		'000001BA' => 'mpg',
		'000001B3' => 'mpg',
		'6D6F6F76' => 'mov',
		'3026B2758E66CF11' => 'asf',
		'4D546864' => 'mid'
	];

	/**
	 * getFileFormat(Sting $filepath)
	 * 获取文联类型
	 * ------------------------------
	 * @param Sting $filepath 文件路径
	 * -------------------------------
	 * @return String
	 * @author Verdient。
	 */
	public static function getFileFormat($filepath){
		if(!file_exists($filepath)){
			throw new Exception(18000);
		}
		if(!$file = @fopen($filepath, "rb")){
			throw new Exception(18001);
		}
		$fileHead = fread($file, 15);
		fclose($file);
		$typelist = self::$typeList;
		foreach ($typelist as $key => $value){
			$length = strlen(pack("H*", $key));
			$fileHead2 = substr($fileHead, 0, intval($length));
			$fileHead2 = unpack("H*", $fileHead2);
			$filehead2 = array_shift($fileHead2);
			if(strtolower($key) == strtolower($filehead2)){
				return $value;
			}
		}
		return 'Unknown';
	}
}