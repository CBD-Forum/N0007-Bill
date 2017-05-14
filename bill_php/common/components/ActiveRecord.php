<?php
namespace common\components;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord as BaseActiveRecord;
use yii\helpers\ArrayHelper;
use common\behaviors\ExceptionBehavior;

/**
 * ActiveRecord Model
 * 活动记录模型
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class ActiveRecord extends BaseActiveRecord
{
	/**
	 * @var public $page
	 * 页码
	 * -----------------
	 * @method GET
	 * @author Verdient。
	 */
	public $page;

	/**
	 * @var public $pageSize
	 * 分页大小
	 * ---------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $pageSize;

	/**
	 * @var public $startTime
	 * 开始时间
	 * ----------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $startTime;

	/**
	 * @var public $endTime
	 * 结束时间
	 * --------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $endTime;

	/**
	 * @var public $sort
	 * 排序
	 * -----------------
	 * @method GET
	 * @author Verdient。
	 */
	public $sort;

	/**
	 * @var public $query
	 * 查询
	 * ------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $query;

	/**
	 * @var public $signature
	 * 签名字符串
	 * ----------------------
	 * @author Verdient。
	 */
	public $signature;

	/**
	 * @var public $instruction_id
	 * 任务ID
	 * ---------------------------
	 * @author Verdient。
	 */
	public $instruction_id;

	/**
	 * @var public $instance
	 * 类的实例
	 * ---------------------
	 * @method GET
	 * @author Verdient。
	 */
	public $instance;

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
			'ExceptionBehavior' => ExceptionBehavior::className(),
			'TimestampBehavior' => TimestampBehavior::className()
		];
	}
}