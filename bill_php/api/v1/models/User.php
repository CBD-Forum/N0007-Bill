<?php
namespace api\v1\models;

use Yii;
use common\components\ActiveDataProvider;
use common\models\User as baseUser;

/**
 * User Model
 * 用户模型
 * ----------
 * @version 1.0.0
 * @author Verdient。
 */
class User extends baseUser
{
	/**
	 * @var public $enterprise_name
	 * 企业名称
	 * ----------------------------
	 * @method POST
	 * @author Verident。
	 */
	public $enterprise_name;

	/**
	 * @var public $user_name
	 * 登录名称
	 * ----------------------
	 * @method POST
	 * @author Verident。
	 */
	public $user_name;

	/**
	 * @var public $password
	 * 密码
	 * ---------------------
	 * @method POST
	 * @author Verident。
	 */
	public $password;

	/**
	 * @var public $new_password
	 * 新密码
	 * -------------------------
	 * @method POST
	 * @author Verident。
	 */
	public $new_password;

	/**
	 * @var public $repeat_password
	 * 重复密码
	 * ----------------------------
	 * @method POST
	 * @author Verident。
	 */
	public $repeat_password;

	/**
	 * @var public $public_key
	 * 用户公钥
	 * -----------------------
	 * @author Verdient。
	 */
	public $public_key;

	/**
	 * @var public $user_id
	 * 用户ID
	 * --------------------
	 * @author Verdient。
	 */
	public $user_id;

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
			['enterprise_name', 'required', 'message' => 31000, 'on' => ['addMember', 'publicKeyByEnterpriseName']],
			['user_name', 'required', 'message' => 31015, 'on' => 'addMember'],
			['enterprise_name', 'string', 'max' => 50, 'message' => 31001, 'tooLong' => 31001, 'on' => ['addMember', 'publicKeyByEnterpriseName']],
			['user_name', 'string', 'max' => 50, 'message' => 31016, 'tooLong' => 31016, 'on' => 'addMember'],
			['enterprise_name', 'match', 'pattern' => '/^[A-z\s0-9.,()]+$|^[\x80-\xff]+$/', 'message' => 31014, 'on' => ['addMember']],
			['user_name', 'match', 'pattern' => "/^[A-Za-z0-9_.@]+$/", 'message' => 31017, 'on' => 'addMember'],
			['enterprise_name', 'unique', 'targetClass' => TradeCompany::className(), 'targetAttribute' => 'orgName', 'message' => 31002, 'on' => 'addMember'],
			['user_name', 'unique', 'targetClass' => User::className(), 'targetAttribute' => 'email', 'message' => 31018, 'on' => 'addMember'],
			['password', 'required', 'message' => 31005, 'on' => 'changePassword'],
			['new_password', 'required', 'message' => 31006, 'on' => 'changePassword'],
			['repeat_password', 'required', 'message' => 31007, 'on' => 'changePassword'],
			['signature', 'required', 'message' => 31022, 'on' => ['changePassword', 'setAdmin']],
			['instruction_id', 'required', 'message' => 31023, 'on' => ['changePassword', 'setAdmin']],
			['new_password', 'compare', 'compareAttribute' => 'repeat_password', 'message' => 31009, 'on' => 'changePassword'],
			['new_password', 'compare', 'compareAttribute' => 'password', 'operator' => '!=', 'message' => 31010, 'on' => 'changePassword'],
			['password', 'validatePassword', 'on' => 'changePassword'],
			['public_key', 'required', 'message' => 31025, 'on' => 'setAdmin'],
			['public_key', 'validateUAdress', 'on' => 'setAdmin'],
			['query', 'string', 'message' => 31013, 'on' => ['addMemberHistory']],
			['page', 'default', 'value' => 1, 'on' => ['addMemberHistory']],
			['pageSize', 'default', 'value' => 5, 'on' => ['addMemberHistory']],
			['page', 'integer', 'min' => 1, 'message' => 31003, 'tooSmall' => 31003, 'on' => ['addMemberHistory']],
			['pageSize', 'integer', 'min' => 1, 'message' => 31004, 'tooSmall' => 31004, 'on' => ['addMemberHistory']]
		];
	}

	/**
	 * validateUAdress(String $attribute, Mixed $params)
	 * 验证用户公钥
	 * -------------------------------------------------
	 * @param String $attribute 当前字段名
	 * @param Mixed $params rules中设置的params
	 * ----------------------------------------
	 * @author Verdient。
	 */
	public function validateUAdress($attribute, $params){
		if(!$this->hasErrors()){
			if(!$data = PublicKey::find()->where(['public_key' => $this->public_key, 'status' => PublicKey::STATUS_REGULAR])->asArray()->one()){
				return $this->addError($attribute, 31026);
			}
			$this->user_id = $data['user_id'];
		}
	}

	/**
	 * addMemberHistory(Object $query)
	 * 验证用户公钥
	 * -------------------------------
	 * @param Object $query 查询对象
	 * -----------------------------
	 * @return Array
	 * @author Verdient。
	 */
	public function addMemberHistory($query){
		$provider = new ActiveDataProvider([
			'query' => $query
				->select(['id', 'name as enterprise_name', 'email', 'create_date as created_at'])
				->with('publicKey')
				->asArray(),
			'sort' => [
				'defaultOrder' => [
					'create_date' => SORT_DESC
				]
			]
		]);
		$data = $provider->models;
		$statusMap = eval(PublicKey::STATUS_MAP);
		foreach($data as $key => $value) {
			$data[$key]['public_key'] = isset($value['publicKey']) ? $value['publicKey']['public_key'] : null;
			$data[$key]['status'] = isset($value['publicKey']) ? $value['publicKey']['status'] : null;
			$data[$key]['status_label'] = isset($value['publicKey']) && isset($statusMap[$value['publicKey']['status']]) ? $statusMap[$value['publicKey']['status']] : 'Unknown';
			unset($data[$key]['publicKey']);
		}
		return ['count' => $provider->totalCount, 'data' => $data];
	}
}