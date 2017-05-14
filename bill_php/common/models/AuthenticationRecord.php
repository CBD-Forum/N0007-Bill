<?php
namespace common\models;

use Yii;
use common\components\ActiveRecord;

/**
 * EnterpriseAuthenticationLog Model
 * 企业认证打款记录
 * ---------------------------------
 * @version 1.0.0
 * @author Verdient。
 */
class AuthenticationRecord extends ActiveRecord
{
	/**
	 * @type const TYPE_ADUIT_FAILED
	 * 审核成功
	 * -----------------------------
	 * @author Verdient。
	 */
	const TYPE_ADUIT_SUCCESS = 1;

	/**
	 * @type const TYPE_ADUIT_FAILED
	 * 审核失败
	 * -----------------------------
	 * @author Verdient。
	 */
	const TYPE_ADUIT_FAILED = 2;

	/**
	 * @type const TYPE_REMIT_SUCCESS
	 * 打款成功
	 * ------------------------------
	 * @author Verdient。
	 */
	const TYPE_REMIT_SUCCESS = 3;

	/**
	 * @type const TYPE_REMIT_FAILED
	 * 打款失败
	 * -----------------------------
	 * @author Verdient。
	 */
	const TYPE_REMIT_FAILED = 4;

	/**
	 * @type const TYPE_MAP
	 * 类型映射关系
	 * --------------------
	 * @author Verdient。
	 */
	const TYPE_MAP = "return [
		common\models\AuthenticationRecord::TYPE_ADUIT_SUCCESS => '审核通过',
		common\models\AuthenticationRecord::TYPE_ADUIT_FAILED => '审核驳回',
		common\models\AuthenticationRecord::TYPE_REMIT_SUCCESS => '打款成功',
		common\models\AuthenticationRecord::TYPE_REMIT_FAILED => '打款失败',
	];";
}
