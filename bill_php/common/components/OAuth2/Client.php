<?php
namespace common\components\OAuth2;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\components\ActiveRecord;
use common\components\Exception;
use common\helpers\DatabaseHelper;
use common\migration\CreateTable;

/**
 * Client Model
 * 客户端模型
 * ------------
 * @version 1.0.0
 * @author Verdient。
 */
class Client extends ActiveRecord
{
	/**
	 * @var public static $cache
	 * 缓存时间
	 * -------------------------
	 * @method Config
	 * @author Verdient。
	 */
	public $cacheDuration = 3600;

	/**
	 * tableName()
	 * 设置数据表名
	 * ------------
	 * @inheritdoc
	 * -----------
	 * @return String
	 * @author Verdient。
	 */
	public static function tableName(){
		if(!DatabaseHelper::hasTable('oauth2_client')){
			CreateTable::OAuth2Client();
		}
		return '{{oauth2_client}}';
	}

	/**
	 * rules()
	 * 校验规则
	 * --------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function rules(){
		return [
			[['client_id', 'client_secret', 'redirect_uri', 'status'], 'required'],
			[['status' ,'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
			[['client_id', 'client_secret', 'grant_type'], 'string', 'max' => 80],
			[['redirect_uri'], 'string', 'max' => 2000]
		];
	}

	/**
	 * behaviors()
	 * 行为设置
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function behaviors(){
		return [
			TimestampBehavior::className(),
			BlameableBehavior::className(),
		];
	}

	/**
	 * attributeLabels()
	 * 设置别名
	 * -----------------
	 * @inheritdoc
	 * -----------
	 * @return Array
	 * @author Verdient。
	 */
	public function attributeLabels(){
		return [
			'client_id' => Yii::t('OAuth2', 'client_id'),
			'status' => Yii::t('common', 'status'),
			'client_secret' => Yii::t('OAuth2', 'client_secret'),
			'redirect_uri' => Yii::t('OAuth2', 'redirect_uri'),
			'grant_type' => Yii::t('OAuth2', 'grant_type'),
			'created_at' => Yii::t('common', 'created_at')
		];
	}

	/**
	 * getClientInformationByClientID(Integer $clientId[, Boolean $asArray = false, Array / String $select = []])
	 * 通过客户端ID获取客户端信息
	 * ----------------------------------------------------------------------------------------------------------
	 * @param Integer $clientId 客户端ID
	 * @param Boolean $asArray 是否返回数组，默认返回对象
	 * @param Array / String $select 选择的字段
	 * --------------------------------------------------
	 * @return Object / Array
	 * @author Verdient。
	 */
	public static function getClientInformationByClientID($clientId, $asArray = false, $select = []){
		return self::getActiveRecordInformation(['client_id' => $clientId], $asArray, $select);
	}
}
