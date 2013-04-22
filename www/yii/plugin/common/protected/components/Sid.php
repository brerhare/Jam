<?php

/**
 * Preprocessing before a controller/action is called. We use it to check/manage the Sid session
 * @@EG: Event handler. In this case 'onBeginRequest'. See config/main for the setup
 */
class Sid extends CBehavior
{
	public function attach($owner)
	{
		$owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
	}

	public function handleBeginRequest($event)
	{
		Yii::log("Preprocess request started " , CLogger::LEVEL_WARNING, 'system.test.kim');
		
		//Yii::app()->session['uid'] = 3;
		//unset(Yii::app()->session['uid']);

		if (isset($_GET['sid']))
		{
			unset(Yii::app()->session['uid']);
			Yii::log("Preprocess request - We have been given new sid " . $_GET['sid'], CLogger::LEVEL_WARNING, 'system.test.kim');

			$criteria = new CDbCriteria;
			$criteria->addCondition("sid = '" . $_GET['sid'] . "'");
			$user = User::model()->find($criteria);
			if ($user == null)
			{
				Yii::log("Preprocess request - This sid is invalid. Aborting" , CLogger::LEVEL_WARNING, 'system.test.kim');
				throw new CHttpException(500,'Cannot continue without a valid sid');
			}
			Yii::app()->session['uid'] = $user->id;
			Yii::log("Preprocess request - sid validated. Setting uid to " . Yii::app()->session['uid'], CLogger::LEVEL_WARNING, 'system.test.kim');
		}

		if (!isset(Yii::app()->session['uid']))
		{
			Yii::log("Preprocess request - uid is not set. Aborting" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(500,'Cannot continue without a uid');
		}
	}
}
?>
