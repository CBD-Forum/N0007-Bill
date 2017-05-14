<?php
namespace common\components\upload;

use Yii;

/**
 * Office Model
 * Office文件模型
 * -----------------
 * @version 1.0.0
 * @author Verdient。
 */
class Office extends Upload
{
	/**
	 * @var public $savePath
	 * 保存文件夹名称
	 * -------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $savePath = 'upload/office';

	/**
	 * @var public $enabledFormat
	 * 允许上传的格式
	 * --------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enabledFormat = ['DOC', 'DOCX', 'PDF'];

	/**
	 * @var public $enableType
	 * 允许上传的类型
	 * -----------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enabledType = [
		self::TYPE_DISCOUNT_CONTRACT, self::TYPE_ENDORSED_CONTRACT, self::TYPE_POSSESSOR_CONTRACT, self::TYPE_INVESTOR_CONTRACT
	];

	/**
	 * rules()
	 * 数据验证规则
	 * ------------
	 * @inheritdoc
	 * ------------
	 * @return Array
	 * @author Verdient。
	 */
	public function rules(){
		return [
			['file', 'required', 'message' => 37000, 'on' => 'create'],
			['type', 'required', 'message' => 37005, 'on' => 'create'],
			['user_id', 'required', 'message' => 37007, 'on' => 'create'],
			['original_name', 'required', 'message' => 37008, 'on' => 'create'],
			['name', 'required', 'message' => 37009, 'on' => 'create'],
			['format', 'required', 'message' => 37010, 'on' => 'create'],
			['size', 'required', 'message' => 37011, 'on' => 'create'],
			['status', 'default', 'value' => 1, 'on' => 'create'],
			['path', 'default', 'value' => $this->savePath . DIRECTORY_SEPARATOR . $this->name, 'on' => 'create'],
			['type', 'in', 'range' => $this->enabledType, 'message' => 37006, 'on' => 'create'],
			['format', 'validateFormat', 'on' => 'create'],
			['size', 'validateSize', 'on' => 'create'],
			['original_name', 'string', 'max' => 255, 'message' => 37012, 'tooLong' => 37013, 'on' => 'create']
		];
	}

	/**
	 * validateFormat(String $attribute, Mixed $params)
	 * 检查上传文件格式是否合法
	 * ------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateFormat($attribute, $params){
		$this->format = strtoupper($this->getExtension());
		if(!in_array($this->format, array_map('strtoupper', $this->enabledFormat))){
			$this->addError($attribute, 37001);
		}else {
			$format = FileFormat::getFileFormat($this->file->tempName);
			if(!in_array($this->format, array_map('strtoupper', explode('/', $format)))){
				$this->addError($attribute, 37002);
			}
		}
	}
}