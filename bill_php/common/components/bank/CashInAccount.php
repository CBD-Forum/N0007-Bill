<?php
namespace common\components\bank;

use common\components\ActiveRecord;

/**
 * CashInAccount Model
 * 入金账户模型
 * -------------------
 * @version 1.0.0
 * @author CGA
 */
class CashInAccount extends ActiveRecord
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
			['custNo', 'required'],
			[['static', 'created_at', 'updated_at'], 'safe'],
		];
	}
}