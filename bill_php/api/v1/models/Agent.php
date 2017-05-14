<?php
namespace api\v1\models;

use Yii;
use common\components\OAuth2\AccessToken;
use common\helpers\Helper;
use common\helpers\ValidateHelper;
use common\models\Agent as BaseAgent;

/**
 * Agent Model
 * 经办人模型
 * -----------
 * @version 1.0.0
 * @author Verdient。
 */
class Agent extends BaseAgent
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
			[['name', 'id_number', 'bank_card_number', 'mobile'], 'filter', 'filter' => 'trim', 'on' => ['create']],
			['user_id', 'required', 'message' => 22000, 'on' => 'create'],
			['name', 'required', 'message' => 22001, 'on' => ['create', 'update']],
			['id_number', 'required', 'message' => 22002, 'on' => ['create', 'update']],
			['mobile', 'required', 'message' => 22003, 'on' => ['create', 'update']],
			['bank_card_number', 'required', 'message' => 22004, 'on' => ['create', 'update']],
			['user_id', 'integer', 'message' => 22005,'on' => ['create']],
			['name', 'string', 'min' => 1, 'max' => 30, 'message' => 22006, 'tooLong' => 22006, 'tooShort' => 22006, 'on' => ['create']],
			['id_number', 'string', 'min' => 1, 'max' => 20, 'message' => 22007, 'tooLong' => 22007, 'tooShort' => 22007, 'on' => ['create']],
			['mobile', 'string', 'min' => 1, 'max' => 20, 'message' => 22008, 'tooLong' => 22008, 'tooShort' => 22008, 'on' => ['create']],
			['bank_card_number', 'string', 'min' => 1, 'max' => 20, 'message' => 22009, 'tooLong' => 22009, 'tooShort' => 22009, 'on' => ['create']],
		];
	}
}