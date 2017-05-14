<?php
namespace common\models;

use Yii;
use common\components\ActiveRecord;

/**
 * Agent Model
 * 代理人模型
 * -----------
 * @version 1.0.0
 * @author Verdient。
 */
class Agent extends ActiveRecord
{
	/**
	 * @status const STATUS_REGULAR
	 * 正常
	 * ----------------------------
	 * @author Verdient。
	 */
	const STATUS_REGULAR = 1;
}