<?php
namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\behaviors\ExceptionBehavior;
use common\components\ActiveRecord;
use common\components\Exception;
use common\helpers\ValidateHelper;

/**
 * CompanyBaseInfo Model
 * 基本企业信息模型
 * ---------------------
 * @version 1.0.0
 * @author Verdient。
 */
class CompanyBaseInfo extends ActiveRecord
{
	/**
	 * @var public $business_license_path
	 * 营业执照照片路径
	 * ----------------------------------
	 * @author Verdient。
	 */
	public $business_license_path;

	/**
	 * @var public $organization_code_certificate_path
	 * 组织机构代码证照片路径
	 * -----------------------------------------------
	 * @author Verdient。
	 */
	public $organization_code_certificate_path;

	/**
	 * @var public $licence_for_opening_accounts_path
	 * 开户许可证照片路径
	 * -----------------------------------------------
	 * @author Verdient。
	 */
	public $licence_for_opening_accounts_path;

	/**
	 * @var public $corporate_ID_card_path
	 * 法人身份证照片路径
	 * -----------------------------------
	 * @author Verdient。
	 */
	public $corporate_ID_card_path;

	/**
	 * @var public $power_of_attorney_path
	 * 授权书照片路径
	 * -----------------------------------
	 * @author Verdient。
	 */
	public $power_of_attorney_path;

	/**
	 * tableName()
	 * 设置表名
	 * -----------
	 * @return String
	 * @author Verdient。
	 */
	public static function tableName(){
		return '{{t_scf_company_base_info}}';
	}

	/**
	 * getDb()
	 * 获取数据库信息
	 * --------------
	 * @return String
	 * @author Verdient。
	 */
	public static function getDb(){
		return Yii::$app->db2;
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
			'ExceptionBehavior' => ExceptionBehavior::className()
		];
	}

	/**
	 * validateEnterpriseID(String $attribute, Mixed $params)
	 * 验证企业ID
	 * ------------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateEnterpriseID($attribute, $params){
		if(!$this->hasErrors()){
			$query = Enterprise::find()->where(['enterprise_id' => $this->enterprise_id]);
			if(isset($params['filter']) && is_array($params['filter'])){
				if(ArrayHelper::isAssociative($params['filter'])){
					$query->andFilterWhere($params['filter']);
				}else{
					foreach($params['filter'] as $key => $value){
						$query->andFilterWhere($value);
					}
				}
			}
			if(!$this->instance = $query->one()){
				$this->addError($attribute, 23026);
			}
		}
	}

	/**
	 * sendBaseInfo()
	 * 发送企业基本信息
	 * ----------------
	 * @param Array $data 待添加的数据
	 * -------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public function sendBaseInfo(){
		$data = $this->attributes;
		foreach($data as $key => $value){
			if(empty($value)){
				unset($data[$key]);
			}
		}
		$data['enterprise_id'] = $this->enterprise_id;
		$data['code_org'] = $data['socialCreditCode'];
		$result = Yii::$app->haipiaohui->sendBaseCompanyInfo($data);
		if(isset($result['success']) && $result['success'] == true){
			return true;
		}
		throw new Exception(23036);
	}
}
