<?php
namespace api\v1\models;

use Yii;
use common\components\ActiveDataProvider;
use common\models\PublicKey as BasePublicKey;

/**
 * PublicKey Model
 * 公钥 模型
 * ---------------
 * @version 1.0.0
 * @author Verdient。
 */
class PublicKey extends BasePublicKey
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
			[['user_id', 'signature', 'instruction_id'], 'filter', 'filter' => 'trim', 'on' => ['activateMember', 'activateExternal']],
			['user_id', 'required', 'message' => 35001, 'on' => ['activateMember', 'activateExternal']],
			['signature', 'required', 'message' => 35004, 'on' => ['activateMember', 'activateExternal']],
			['instruction_id', 'required', 'message' => 35005, 'on' => ['activateMember', 'activateExternal']],
			['user_id', 'integer', 'message' => 35002, 'on' => ['activateMember', 'activateExternal']],
			['user_id', 'validateUserID', 'params' => ['filter' => ['status' => [self::STATUS_WAITING_SIGN_UP, self::STATUS_SIGN_UP_FAILED]]], 'on' => ['activateMember', 'activateExternal']],
			['page', 'default', 'value' => 1, 'on' => 'waitingActivateList'],
			['pageSize', 'default', 'value' => 5, 'on' => 'waitingActivateList'],
			['page', 'integer', 'min' => 1, 'message' => 35006, 'tooSmall' => 35006, 'on' => 'waitingActivateList'],
			['pageSize', 'integer', 'min' => 1, 'message' => 35007, 'tooSmall' => 35007, 'on' => 'waitingActivateList'],
		];
	}

	/**
	 * generateRandom()
	 * 生成随机码
	 * ----------------
	 * @return String
	 * @author Verdient。
	 */
	public function generateRandom(){
		return Yii::$app->security->generateRandomString(self::RANDOM_LENGTH);
	}

	/**
	 * waitingActivateList()
	 * 待激活列表
	 * ---------------------
	 * @return String
	 * @author Verdient。
	 */
	public function waitingActivateList($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select(['user_id', 'status', 'public_key'])
				->with('user')
				->asArray(),
			'sort' => [
				'defaultOrder' => [
					'updated_at' => SORT_ASC
				]
			]
		]);
		$data = $provider->models;
		$statusMap = eval(self::STATUS_MAP);
		foreach($data as $key => $value){
			$data[$key]['enterprse_name'] = isset($value['user']['tradeCompany']) ? $value['user']['tradeCompany']['orgName'] : 'Unknown';
			$data[$key]['user_name'] = isset($value['user']) ? $value['user']['name'] : 'Unknown';
			$data[$key]['status'] = isset($statusMap[$value['status']]) ? $statusMap[$value['status']] : 'Unknown';
			unset($data[$key]['user']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}
}