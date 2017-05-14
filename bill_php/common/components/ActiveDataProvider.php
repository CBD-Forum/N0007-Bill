<?php
namespace common\components;

use Yii;
use yii\data\ActiveDataProvider as BaseActiveDataProvider;

/**
 * ActiveRecord Model
 * 活动记录模型
 * ------------------
 * @version 1.0.0
 * @author Verdient。
 */
class ActiveDataProvider extends BaseActiveDataProvider
{
	/**
	 * init()
	 * 初始化
	 * -----------
	 * @inheritdoc
	 * -----------
	 * @author Verdient。
	 */
	public function init(){
		$this->pagination->pageSizeParam = $this->pagination->pageSizeParam == 'per-page' ? 'pageSize' : $this->pagination->pageSizeParam;
		$page = (int)Yii::$app->request->get($this->pagination->pageParam);
		$pageSize = (int)Yii::$app->request->get($this->pagination->pageSizeParam);
		$this->pagination->page = $this->pagination->page ?: ($page > 0 ? $page - 1 : null);
		$this->pagination->pageSize = $this->pagination->pageSize ?: ($pageSize > 0 ? $pageSize : null);
		parent::init();
	}
}
