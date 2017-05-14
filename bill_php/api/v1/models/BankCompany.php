<?php
namespace api\v1\models;

use Yii;
use common\models\BankCompany as baseBankCompany;
use common\validators\BankCardValidator;

/**
 * BankCompany Model
 * 银行模型
 * -----------------
 * @version 1.0.0
 * @author Verdient。
 */
class BankCompany extends baseBankCompany
{
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
			['companyId', 'required', 'message' => 22028, 'on' => 'bankInfo'],
			['companyId', 'integer', 'message' => 22041, 'on' => 'bankInfo'],
			['companyId', 'validateCompanyID', 'on' => 'bankInfo'],
			['userName', 'required', 'message' => 22020, 'on' => 'create'],
			['account', 'required', 'message' => 22021, 'on' => 'create'],
			['bankName', 'required', 'message' => 22022, 'on' => 'create'],
			['sBankCode', 'required', 'message' => 22023, 'on' => 'create'],
			['bankCode', 'required', 'message' => 22024, 'on' => 'create'],
			['province', 'required', 'message' => 22025, 'on' => 'create'],
			['city', 'required', 'message' => 22026, 'on' => 'create'],
			['country', 'required', 'message' => 22027, 'on' => 'create'],
			['companyId', 'required', 'message' => 22028, 'on' => 'create'],
			['name', 'required', 'message' => 22029, 'on' => 'create'],
			['bank_card_number', 'required', 'message' => 22030, 'on' => 'create'],
			['bank_code', 'required', 'message' => 22031, 'on' => 'create'],
			['companyId', 'validateEnterprise', 'on' => 'create'],
			['companyId', 'validateCompanyID', 'on' => 'bankInfo'],
			['account', BankCardValidator::className(), 'message' => 22034, 'on' => 'create'],
			['bank_card_number', BankCardValidator::className(), 'message' => 22035, 'on' => 'create'],
			['sameBank', 'default', 'value' => 1, 'on' => 'create']
		];
	}
}