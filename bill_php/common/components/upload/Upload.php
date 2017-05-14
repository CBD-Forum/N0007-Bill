<?php
namespace common\components\upload;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use common\components\ActiveRecord;

/**
 * Upload Model
 * 上传文件模型
 * ------------
 * @version 1.0.0
 * @author Verdient。
 */
class Upload extends ActiveRecord
{
	/**
	 * @type const TYPE_BILL_FRONT
	 * 票据正面
	 * ---------------------------
	 * @author Verdient。
	 */
	const TYPE_BILL_FRONT = 10001;

	/**
	 * @type const TYPE_BILL_BACK
	 * 票据反面
	 * --------------------------
	 * @author Verdient。
	 */
	const TYPE_BILL_BACK = 10002;

	/**
	 * @type const TYPE_DISCOUNT_CONTRACT
	 * 贴现协议文件
	 * ----------------------------------
	 * @author Verdient。
	 */
	const TYPE_DISCOUNT_CONTRACT = 10003;

	/**
	 * @type const TYPE_ENDORSED_CONTRACT
	 * 背书协议文件
	 * -------------------------
	 * @author Verdient。
	 */
	const TYPE_ENDORSED_CONTRACT = 10004;

	/**
	 * @type const TYPE_POSSESSOR_CONTRACT
	 * 拥有者协议文件
	 * -----------------------------------
	 * @author Verdient。
	 */
	const TYPE_POSSESSOR_CONTRACT = 10005;

	/**
	 * @type const TYPE_INVESTOR_CONTRACT
	 * 投资者协议文件
	 * ----------------------------------
	 * @author Verdient。
	 */
	const TYPE_INVESTOR_CONTRACT = 10006;

	/**
	 * @type const TYPE_BUSINESS_LICENSE
	 * 营业执照
	 * ---------------------------------
	 * @author Verdient。
	 */
	const TYPE_BUSINESS_LICENSE = 10007;

	/**
	 * @type const TYPE_ORGANIZATION_CODE_CERTIFICATE
	 * 组织机构代码证
	 * ----------------------------------------------
	 * @author Verdient。
	 */
	const TYPE_ORGANIZATION_CODE_CERTIFICATE = 10008;

	/**
	 * @type const TYPE_LICENCE_FOR_OPENING_ACCOUNTS
	 * 开户许可证
	 * ---------------------------------------------
	 * @author Verdient。
	 */
	const TYPE_LICENCE_FOR_OPENING_ACCOUNTS = 10009;

	/**
	 * @type const TYPE_CORPORATE_ID_CARD
	 * 法人身份证
	 * ----------------------------------
	 * @author Verdient。
	 */
	const TYPE_CORPORATE_ID_CARD = 10010;

	/**
	 * @type const TYPE_POWER_OF_ATTORNEY
	 * 授权书
	 * ----------------------------------
	 * @author Verdient。
	 */
	const TYPE_POWER_OF_ATTORNEY = 10011;

	/**
	 * @var public $maxSize
	 * 大小上限
	 * --------------------
	 * @method Config
	 * @author Verident。
	 */
	public $maxSize = 5242880;

	/**
	 * @var public $minSize
	 * 大小下限
	 * --------------------
	 * @method Config
	 * @author Verident。
	 */
	public $minSize = 0;

	/**
	 * @var public $fileNameLength
	 * 文件名长度
	 * --------------------
	 * @method Config
	 * @author Verident。
	 */
	public $fileNameLength = 10;

	/**
	 * @var protected $file
	 * 文件对象
	 * ---------------------
	 * @author Verident。
	 */
	public $file;

	/**
	 * __construct()
	 * 构造函数
	 * -------------
	 * @inheritdoc
	 * -----------
	 * @author Verdient。
	 */
	public function __construct($fileName = 'file'){
		if($this->file = UploadedFile::getInstanceByName($fileName)){
			$this->user_id = Yii::$app->user->id || Yii::$app->OAuth2->userInfo->id;
			$this->original_name = $this->file->name;
			$this->name = Yii::$app->security->generateRandomString($this->fileNameLength) . '.' . $this->getExtension();
			$this->format =  $this->file->type;
			$this->size = $this->file->size;
		}
	}

	/**
	 * tableName()
	 * 设置表名
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return String
	 * @author Verdient。
	 */
	public static function tableName(){
		return '{{%upload_file}}';
	}

	/**
	 * beforeSave($insert)
	 * 保存前的操作
	 * -------------------
	 * @inheritdoc
	 * -----------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function beforeSave($insert){
		parent::beforeSave($insert);
		$this->path = str_replace('\\', '/', $this->path);
		return true;
	}

	/**
	 * validateSize(String $attribute, Mixed $params)
	 * 检查上传图片大小
	 * ----------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateSize($attribute, $params){
		if($this->file->size > $this->maxSize){
			$this->addError($attribute, 25000);
		}
		if($this->file->size < $this->minSize){
			$this->addError($attribute, 25001);
		}
	}

	/**
	 * saveAs(String $path[, Boolean $deleteTempFile = true])
	 * 保存图片
	 * ------------------------------------------------------
	 * @param String $path 保存路径
	 * @param Boolean $deleteTempFile 是否删除临时文件
	 * -----------------------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function saveAs($path, $deleteTempFile = true){
		$model = new UploadedFile;
		$model->tempName = $this->file->tempName;
		$path2 = str_replace(DIRECTORY_SEPARATOR . $this->name, '', $path);
		if(!is_dir($path2)){
			mkdir($path2, 0777, true);
		}
		return $model->saveAs($path, $deleteTempFile);
	}

	/**
	 * getExtension()
	 * 获取后缀名
	 * --------------
	 * @return String
	 * @author Verdient。
	 */
	public function getExtension(){
		$model = new UploadedFile;
		$model->name = $this->file->name;
		return $model->getExtension();
	}

	/**
	 * getUploadFileInformationByPath(String $path[, Boolean $asArray = false])
	 * 通过path获取上传文件信息
	 * -------------------------------------------------------------------
	 * @param Integer $code 上传文件代码
	 * @param Boolean $asArray 是否返回数组，默认返回对象
	 * --------------------------------------------------
	 * @return Object / Array
	 * @author Verdient。
	 */
	public static function getUploadFileInformationByPath($path, $asArray = false, $select = []){
		return self::getActiveRecordInformation(['path' => $path], $asArray, $select);
	}

	/**
	 * getUploadFileInformationById(Integer $id[, Boolean $asArray = false, Array $select = []])
	 * 通过id获取上传文件信息
	 * -----------------------------------------------------------------------------------------
	 * @param Integer $code 上传文件ID
	 * @param Boolean $asArray 是否返回数组，默认返回对象
	 * --------------------------------------------------
	 * @return Object / Array
	 * @author Verdient。
	 */
	public static function getUploadFileInformationById($id, $asArray = false, $select = []){
		return self::getActiveRecordInformation(['id' => $id], $asArray, $select);
	}
}
