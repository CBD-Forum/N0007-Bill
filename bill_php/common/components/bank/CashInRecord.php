<?php
namespace common\components\bank;

use common\components\ActiveRecord;

/**
 * CashInRecord Model
 * 入金记录模型
 * ------------------
 * @version 1.0.0
 * @author CGA
 */
class CashInRecord extends ActiveRecord
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
			[['custNo', 'subAccNo', 'HOSTFLW', 'OPPBANKNO', 'XTSFAM', 'TRANAMT', 'TRANDATE', 'TRANTIME', 'OPPACCNAME', 'OPPACCNO', 'CRYTYPE', 'OPPBRANCHNAME', 'status'], 'required'],
			[['HOSTFLW'], 'unique'],
		];
	}

	/**
	 * getStartRecord(String $custNo)
	 * 获取起始记录号
	 * ------------------------------
	 * @param  String $custNo 用户企业Id
	 * @return Integer
	 */
	public static function getStartRecord($custNo){
		$startDate = date('Ymd', strtotime("-30 day"));
		$query = static::find()->where(['custNo' => (string)$custNo]);
		$query->andWhere(['>', 'TRANDATE', $startDate]);
		$count = $query->count();
		return $count + 1;
	}
}
