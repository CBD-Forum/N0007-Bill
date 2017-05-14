<?php
namespace common\components\upload;

use Yii;

/**
 * Image Model
 * 图片文件模型
 * -----------------
 * @version 1.0.0
 * @author Verdient。
 */
class Image extends Upload
{
	/**
	 * @var public $savePath
	 * 保存文件夹名称
	 * -------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $savePath = 'upload/image';

	/**
	 * @var public $enabledFormat
	 * 允许上传的格式
	 * --------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enabledFormat = ['JPEG', 'PNG', 'BMP'];

	/**
	 * @var public $enableType
	 * 允许上传的类型
	 * -----------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $enabledType = [
		self::TYPE_BILL_FRONT,
		self::TYPE_BILL_BACK,
		self::TYPE_DISCOUNT_CONTRACT,
		self::TYPE_ENDORSED_CONTRACT,
		self::TYPE_POSSESSOR_CONTRACT,
		self::TYPE_INVESTOR_CONTRACT,
		self::TYPE_BUSINESS_LICENSE,
		self::TYPE_ORGANIZATION_CODE_CERTIFICATE,
		self::TYPE_LICENCE_FOR_OPENING_ACCOUNTS,
		self::TYPE_CORPORATE_ID_CARD,
		self::TYPE_POWER_OF_ATTORNEY,
	];

	/**
	 * rules()
	 * 数据验证规则
	 * ------------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function rules(){
		return [
			['type', 'filter', 'filter' => 'trim', 'on' => 'create'],
			['file', 'required', 'message' => 16000, 'on' => 'create'],
			['type', 'required', 'message' => 16005, 'on' => 'create'],
			['user_id', 'required', 'message' => 16007, 'on' => 'create'],
			['original_name', 'required', 'message' => 16008, 'on' => 'create'],
			['name', 'required', 'message' => 16009, 'on' => 'create'],
			['format', 'required', 'message' => 16010, 'on' => 'create'],
			['size', 'required', 'message' => 16011, 'on' => 'create'],
			['status', 'default', 'value' => 1, 'on' => 'create'],
			['path', 'default', 'value' => $this->savePath . DIRECTORY_SEPARATOR . $this->name, 'on' => 'create'],
			['type', 'in', 'range' =>$this->enabledType, 'message' => 16006, 'on' => 'create'],
			['format', 'validateFormat', 'on' => 'create'],
			['size', 'validateSize', 'on' => 'create'],
			['original_name', 'string', 'max' => 255, 'message' => 16012, 'tooLong' => 16013, 'on' => 'create']
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
		$format = strtoupper(explode('/', $this->format)[1]);
		if(!in_array($format, array_map('strtoupper', $this->enabledFormat))){
			$this->addError($attribute, 16001);
		}else if(exif_imagetype($this->file->tempName) != constant('IMAGETYPE_' . $format)){
			$this->addError($attribute, 16002);
		}
	}
}