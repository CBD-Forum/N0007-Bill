<?php
namespace api\v1\models;

use Yii;
use common\components\ActiveDataProvider;
use common\models\AuthenticationRecord as BaseAuthenticationRecord;
use api\v1\components\Exception;

/**
 * AuthenticationRecord Model
 * 认证记录模型
 * --------------------------
 * @version 1.0.0
 * @author Verdient。
 */
class AuthenticationRecord extends BaseAuthenticationRecord
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
			['page', 'default', 'value' => 1, 'on' => 'authenticationHistory'],
			['pageSize', 'default', 'value' => 10, 'on' => 'authenticationHistory'],
			['page', 'integer', 'message' => 28000, 'on' => 'authenticationHistory'],
			['pageSize', 'integer', 'message' => 28001, 'on' => 'authenticationHistory'],
		];
	}

	/**
	 * addRecord(Integer $enterpriseId, Integr $type, Float $amount = null)
	 * 添加记录
	 * --------------------------------------------------------------------
	 * @param Integer $enterpriseId 企业ID
	 * @param Integr $type 类型
	 * @param Float $amount 金额
	 * -----------------------------------
	 * @return Boolean
	 * @author Verdient。
	 */
	public static function addRecord($enterpriseId, $type, $amount = null){
		$tradeCompany = TradeCompany::find()->where(['id' => $enterpriseId])->asArray()->one();
		$companyBaseInfo = CompanyBaseInfo::find()->where(['enterprise_id' => $enterpriseId])->asArray()->one();
		$bankCompany = BankCompany::find()->where(['companyId' => $enterpriseId])->asArray()->one();
		$enterprise = Enterprise::find()->where(['enterprise_id' => $enterpriseId])->asArray()->one();
		$user = User::find()->where(['enterprise_id' => $enterpriseId])->asArray()->one();
		if(empty($tradeCompany) || empty($companyBaseInfo) || empty($bankCompany) || empty($enterprise) || empty($user)){
			throw new Exception(28002);
		}
		$model = new self;
		$model->type = $type;
		$model->enterprise_name = $tradeCompany['orgName'];
		$model->user_name = $user['name'];
		$model->legal_person = $companyBaseInfo['legalPersonNm'];
		$model->bank = $bankCompany['bankCode'];
		$model->bank_branch = $bankCompany['sBankCode'];
		$model->bank_card_number = $bankCompany['account'];
		$model->amount = $amount;
		$model->company_info = json_encode($tradeCompany);
		$model->base_company_info = json_encode($companyBaseInfo);
		$model->bank_info = json_encode($bankCompany);
		$model->enterprise_info = json_encode($enterprise);
		$model->user_info = json_encode($user);
		return $model->save(false);
	}

	/**
	 * authenticationList(Object $query)
	 * 认证记录列表
	 * ---------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function authenticationList($query){
		$provider = new ActiveDataProvider([
			'query' => $query->select(['id', 'type', 'enterprise_name', 'user_name', 'legal_person', 'bank', 'bank_branch', 'bank_card_number', 'amount', 'created_at'])->asArray(),
			'sort' => [
				'defaultOrder' => [
					'created_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		$typeMap = eval(self::TYPE_MAP);
		foreach($data as $key => $value){
			$data[$key]['type'] = isset($typeMap[$data[$key]['type']]) ? $typeMap[$data[$key]['type']] : 'Unknown';
			$data[$key]['created_at'] = date('Y-m-d H:i:s', $value['created_at']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}
}