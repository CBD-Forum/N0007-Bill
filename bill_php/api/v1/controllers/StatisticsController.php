<?php
namespace api\v1\controllers;

use Yii;
use api\v1\components\ApiController;
use api\v1\models\StatisticsRealTime;
use api\v1\models\Bill;
use api\v1\models\User;

/**
 * Statistics Controller
 * 统计控制器
 * ---------------------
 * @version 1.0.0
 * @author Verdient。
 */
class StatisticsController extends ApiController
{
	/**
	 * actionRealTime()
	 * 实时统计
	 * ----------------
	 * @return Array
	 * @author Verdient。
	 */
	public function actionRealTime(){
		$userInfo = $this->checkPermission([User::TYPE_FINANCE, User::TYPE_REVIEW, User::TYPE_MEMBER, User::TYPE_EXTERNAL], [User::TYPE_MEMBER, User::TYPE_EXTERNAL]);
		$model = new StatisticsRealTime;
		switch($userInfo->type){
			case User::TYPE_FINANCE: case User::TYPE_REVIEW:
				$data['member']['waiting_check'] = StatisticsRealTime::waitingCheckAmount($userInfo->members);
				$data['member']['holding'] = StatisticsRealTime::holdingAmount($userInfo->members);
				$data['member']['bank_entering'] = StatisticsRealTime::bankEnteringQuantity($userInfo->members);
				$data['member']['commercial_entering'] = StatisticsRealTime::commercialEnteringQuantity($userInfo->members);
				$data['member']['transaction'] = StatisticsRealTime::transactionAmount($userInfo->members);
				$data['finance']['waiting_check'] = StatisticsRealTime::waitingCheckAmount($userInfo->parent);
				$data['finance']['holding'] = StatisticsRealTime::holdingAmount($userInfo->parent);
				$data['finance']['bank_entering'] = StatisticsRealTime::bankEnteringQuantity($userInfo->parent);
				$data['finance']['commercial_entering'] = StatisticsRealTime::commercialEnteringQuantity($userInfo->parent);
				$data['finance']['transaction'] = StatisticsRealTime::transactionAmount($userInfo->parent);
				$data['finance']['invoice'] = StatisticsRealTime::invoiceAmount($userInfo->parent);
				$data['finance']['expense'] = StatisticsRealTime::expenseAmount($userInfo->parent);
				break;

			case User::TYPE_MEMBER: case User::TYPE_EXTERNAL:
				$data['waiting_check'] = StatisticsRealTime::waitingCheckAmount($userInfo->members);
				$data['holding'] = StatisticsRealTime::holdingAmount($userInfo->id);
				$data['bank_entering'] = StatisticsRealTime::bankEnteringQuantity($userInfo->id);
				$data['commercial_entering'] = StatisticsRealTime::commercialEnteringQuantity($userInfo->id);
				$data['transaction'] = StatisticsRealTime::transactionAmount($userInfo->id);
				$data['invoice'] = StatisticsRealTime::invoiceAmount($userInfo->id);
				$data['expense'] = StatisticsRealTime::expenseAmount($userInfo->id);
				$data['assignment'] = StatisticsRealTime::assignmentAmount($userInfo->id);
				$data['earning'] = StatisticsRealTime::earningAmount($userInfo->id);
				$data['frozen'] = StatisticsRealTime::frozenAmount($userInfo->id);
				break;
		}
		return $this->sendResponse($data);
	}
}