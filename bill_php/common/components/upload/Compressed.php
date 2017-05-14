<?php
namespace common\components\upload;

use Yii;

/**
 * Compressed Model
 * 压缩文件模型
 * ----------------------
 * @version 1.0.0
 * @author Verdient。
 */
class Compressed extends Upload
{
	/**
	 * __construct()
	 * 构造函数
	 * -------------
	 * @inheritdoc
	 * -----------
	 * @author Verdient。
	 */
	public function __construct(){
		parent::__construct('compressed_file');
	}

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
			['file', 'required', 'message' => 17000, 'on' => 'create'],
			['type', 'required', 'message' => 17005, 'on' => 'create'],
			['user_id', 'required', 'message' => 17007, 'on' => 'create'],
			['original_name', 'required', 'message' => 17008, 'on' => 'create'],
			['name', 'required', 'message' => 17009, 'on' => 'create'],
			['format', 'required', 'message' => 17010, 'on' => 'create'],
			['size', 'required', 'message' => 17011, 'on' => 'create'],
			['status', 'default', 'value' => 1, 'on' => 'create'],
			['path', 'default', 'value' => Yii::$app->params['upload']['compressed']['path'].DIRECTORY_SEPARATOR.$this->name, 'on' => 'create'],
			['type', 'in', 'range' => array_keys(Yii::$app->params['upload']['compressed']['type']), 'message' => 17006, 'on' => 'create'],
			['format', 'validateFormat', 'on' => 'create'],
			['size', 'validateSize', 'params'=> ['max' => Yii::$app->params['upload']['compressed']['size']['max'], 'min' => Yii::$app->params['upload']['compressed']['size']['min']], 'on' => 'create'],
			['original_name', 'string', 'max' => 255, 'message' => 17012, 'tooLong' => 17013, 'on' => 'create']
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
		$this->format = strtolower($this->getExtension());
		if(!in_array($this->format, array_map('strtolower', Yii::$app->params['upload']['compressed']['format']))){
			$this->addError($attribute, 21001);
		}else {
			$format = FileFormat::getFileFormat($this->file->tempName);
			if(!in_array($this->format, explode('/', $format))){
				$this->addError($attribute, 21002);
			}
		}
	}

	/**
	 * saveCompressed($deleteTempFile = true)
	 * 保存压缩文件
	 * --------------------------------------
	 * @param Boolean $deleteTempFile 是否删除临时文件
	 * -----------------------------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function saveCompressed($deleteTempFile = true){
		$this->setScenario('create');
		$this->validate();
		if($this->validate() && $this->save(false) && $this->saveAs($this->path, $deleteTempFile)){
			return ['code' => 200, 'id' => $this->id];
		}
		$errors = $this->getFirstErrors();
		throw new Exception(reset($errors));
	}

}
