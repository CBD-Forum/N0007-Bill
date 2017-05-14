<?php
namespace api\v1\models;

use yii\base\Model;
use common\behaviors\ExceptionBehavior;

class Data extends Model
{
	/**
	 * @var public $node
	 * 节点
	 * -----------------
	 * @method Get
	 * @author Verdient。
	 */
	public $node;

	/**
	 * @var public $bank
	 * 银行代码
	 * -----------------
	 * @method Get
	 * @author Verdient。
	 */
	public $bank;

	/**
	 * @var public $city
	 * 城市代码
	 * -----------------
	 * @method Get
	 * @author Verdient。
	 */
	public $city;

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
			'ExceptionBehavior' => ExceptionBehavior::className()
		];
	}

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
			['node', 'filter', 'filter' => 'trim', 'on' => ['city', 'district']],
			['node', 'required', 'message' => 34000, 'on' => ['city', 'district']],
			['node', 'integer', 'message' => 34003, 'on' => ['city', 'district']],
			[['bank', 'city'], 'filter', 'filter' => 'trim', 'on' => 'bankBranch'],
			['bank', 'required', 'message' => 34001, 'on' => 'bankBranch'],
			['city', 'required', 'message' => 34002, 'on' => 'bankBranch'],
			['bank', 'integer', 'message' => 34004, 'on' => 'bankBranch'],
			['city', 'integer', 'message' => 34005, 'on' => 'bankBranch'],
		];
	}
}