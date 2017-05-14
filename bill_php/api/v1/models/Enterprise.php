<?php
namespace api\v1\models;

use Yii;
use common\components\ActiveDataProvider;
use common\models\Enterprise as baseEnterprise;

/**
 * Enterprise Model
 * 企业模型
 * ----------------
 * @version 1.0.0
 * @author Verdient。
 */
class Enterprise extends baseEnterprise
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
			['enterprise_id', 'required', 'message' => 26000, 'on' => ['apply', 'confirm', 'auditConfirm', 'auditFailed', 'remitConfirm', 'remitFailed', 'ecdsInfo', 'legalPersonInfo', 'businessInfo', 'enterpriseInfo', 'bankInfo']],
			['amount', 'required', 'message' => 26004, 'on' => 'confirm'],
			['enterprise_id', 'validateEnterpriseId', 'params' => ['filter' => ['status' => self::STATUS_WAITING_APPLY]], 'on' => 'apply'],
			['enterprise_id', 'validateEnterpriseId', 'params' => ['filter' => ['status' => self::STATUS_WAITING_AUTHENTICATION]], 'on' => 'confirm'],
			['enterprise_id', 'validateEnterpriseId', 'params' => ['filter' => ['status' => self::STATUS_WAITING_AUDIT]], 'on' => ['auditConfirm', 'auditFailed']],
			['enterprise_id', 'validateEnterpriseId', 'params' => ['filter' => ['status' => self::STATUS_WAITING_REMIT]], 'on' => ['remitConfirm', 'remitFailed']],
			['amount', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/', 'message' => 26005, 'on' => 'confirm'],
			['amount', 'validateAmount', 'on' => 'confirm'],
			['page', 'default', 'value' => 1, 'on' => ['waitingRemitList', 'waitingCheckList']],
			['pageSize', 'default', 'value' => 10, 'on' => ['waitingRemitList', 'waitingCheckList']],
			['page', 'integer', 'message' => 26008, 'on' => ['waitingRemitList', 'waitingCheckList']],
			['pageSize', 'integer', 'message' => 26009, 'on' => ['waitingRemitList', 'waitingCheckList']]
		];
	}

	/**
	 * waitingCheckList(Object $query)
	 * 待审核列表
	 * -------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function waitingCheckList($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select(['enterprise_id'])
				->with('tradeCompany')
				->with('bankCompany')
				->with('user')
				->asArray(),
			'sort' => [
				'defaultOrder' => [
					'updated_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		foreach($data as $key => $value){
			$data[$key]['enterprise_name'] = isset($value['tradeCompany']) ? $value['tradeCompany']['orgName'] : null;
			$data[$key]['user_name'] = isset($value['user']) ? $value['user']['name'] : null;
			$data[$key]['bankName'] = isset($value['bankName']) ? $value['bankCompany']['bankName'] : null;
			unset($data[$key]['tradeCompany'], $data[$key]['bankCompany'], $value['user']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}

	/**
	 * waitingRemitList(Object $query)
	 * 待打款列表
	 * -------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function waitingRemitList($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select(['enterprise_id', 'amount'])
				->with('tradeCompany')
				->with('bankCompany')
				->with('user')
				->asArray(),
			'sort' => [
				'defaultOrder' => [
					'updated_at' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		foreach($data as $key => $value){
			$data[$key]['enterprise_name'] = isset($value['tradeCompany']) ? $value['tradeCompany']['orgName'] : null;
			$data[$key]['user_name'] = isset($value['user']) ? $value['user']['name'] : null;
			$data[$key]['bankCode'] = isset($value['bankCompany']) ? $value['bankCompany']['bankCode'] : null;
			$data[$key]['sBankCode'] = isset($value['bankCompany']) ? $value['bankCompany']['sBankCode'] : null;
			$data[$key]['account'] = isset($value['bankCompany']) ? $value['bankCompany']['account'] : null;
			unset($data[$key]['tradeCompany'], $data[$key]['bankCompany'], $value['user']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}
}