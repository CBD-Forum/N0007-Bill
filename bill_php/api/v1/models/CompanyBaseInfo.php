<?php
namespace api\v1\models;

use Yii;
use common\components\upload\Image;
use common\models\CompanyBaseInfo as BaseCompanyBaseInfo;
use common\validators\BusinessLicenseNumberValidator;
use common\validators\CaptchaValidator;
use common\validators\MobileValidator;

/**
 * CompanyBaseInfo Model
 * 基本企业模型
 * ---------------------
 * @version 1.0.0
 * @author Verdient。
 */
class CompanyBaseInfo extends BaseCompanyBaseInfo
{
	/**
	 * @var public $captcha
	 * 验证码
	 * --------------------
	 * @method POST
	 * @author Verdient。
	 */
	public $captcha;

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
			['name', 'required', 'message' => 23000, 'on' => 'create'],
			['enterprise_type', 'required', 'message' => 23001, 'on' => 'create'],
			['address', 'required', 'message' => 23002, 'on' => 'create'],
			['industry', 'required', 'message' => 23004, 'on' => 'create'],
			['area', 'required', 'message' => 23005, 'on' => 'create'],
			['licence', 'required', 'message' => 23006, 'on' => 'create'],
			['socialCreditCode', 'required', 'message' => 23007, 'on' => 'create'],
			['code_org', 'required', 'message' => 23027, 'on' => 'create'],
			['reg_date', 'required', 'message' => 23008, 'on' => 'create'],
			['reg_capital', 'required', 'message' => 23009, 'on' => 'create'],
			['currency', 'required', 'message' => 23010, 'on' => 'create'],
			['legalPersonNm', 'required', 'message' => 23011, 'on' => ['create', 'authorizerUpdate']],
			['contacts', 'required', 'message' => 23029, 'on' => ['create', 'authorizerUpdate']],
			['contact_number', 'required', 'message' => 23028, 'on' => ['create', 'authorizerUpdate']],
			['mailAddress', 'required', 'message' => 23014, 'on' => ['create', 'authorizerUpdate']],
			['business_license_path', 'required', 'message' => 23021, 'on' => 'create'],
			['organization_code_certificate_path', 'required', 'message' => 23022, 'on' => 'create'],
			['licence_for_opening_accounts_path', 'required', 'message' => 23023, 'on' => 'create'],
			['corporate_ID_card_path', 'required', 'message' => 23024, 'on' => ['create', 'authorizerUpdate']],
			['power_of_attorney_path', 'required', 'message' => 23025, 'on' => ['create', 'authorizerUpdate']],
			['captcha', 'required', 'message' => 23037, 'on' => ['create', 'authorizerUpdate']],
			['enterprise_id', 'validateEnterpriseID', 'params' => ['filter' => ['status' => [Enterprise::STATUS_WAITING_ENTERPRISE_SUBMIT, Enterprise::STATUS_WAITING_APPLY, Enterprise::STATUS_AUDIT_FAILED, Enterprise::STATUS_REMIT_FAILED]]], 'on' => 'create'],
			['enterprise_id', 'validateEnterpriseID', 'params' => ['filter' => ['status' => Enterprise::STATUS_REGULAR]], 'on' => 'authorizerUpdate'],
			['licence', BusinessLicenseNumberValidator::className(), 'message' => 23030, 'on' => 'create'],
			['socialCreditCode', BusinessLicenseNumberValidator::className(), 'message' => 23031, 'on' => 'create'],
			['code_org', BusinessLicenseNumberValidator::className(), 'message' => 23032, 'on' => 'create'],
			['reg_date', 'date', 'format' => 'yyyy-MM-dd', 'message' => 23035, 'on' => 'create'],
			['contact_number', MobileValidator::className(), 'message' => 23033, 'on' => ['create', 'authorizerUpdate']],
			['mailAddress', 'email', 'message' => 23034, 'on' => ['create', 'authorizerUpdate']],
			['captcha', CaptchaValidator::className(), 'mobileAttribute' => 'contact_number', 'message' => 16029, 'on' => ['create', 'authorizerUpdate']],
			['business_license_path', 'exist', 'targetClass' => Image::className(), 'targetAttribute' => 'path', 'filter' => ['type' => Image::TYPE_BUSINESS_LICENSE], 'message' => 23039, 'on' => 'create'],
			['organization_code_certificate_path', 'exist', 'targetClass' => Image::className(), 'targetAttribute' => 'path', 'filter' => ['type' => Image::TYPE_ORGANIZATION_CODE_CERTIFICATE], 'message' => 23040, 'on' => 'create'],
			['licence_for_opening_accounts_path', 'exist', 'targetClass' => Image::className(), 'targetAttribute' => 'path', 'filter' => ['type' => Image::TYPE_LICENCE_FOR_OPENING_ACCOUNTS], 'message' => 23041, 'on' => 'create'],
			['corporate_ID_card_path', 'exist', 'targetClass' => Image::className(), 'targetAttribute' => 'path', 'filter' => ['type' => Image::TYPE_CORPORATE_ID_CARD], 'message' => 23042, 'on' => ['create', 'authorizerUpdate']],
			['power_of_attorney_path', 'exist', 'targetClass' => Image::className(), 'targetAttribute' => 'path', 'filter' => ['type' => Image::TYPE_POWER_OF_ATTORNEY], 'message' => 23043, 'on' => ['create', 'authorizerUpdate']]
		];
	}
}
