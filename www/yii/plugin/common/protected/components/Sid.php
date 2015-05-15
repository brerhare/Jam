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
		
		//Yii::app()->sess->set('uid', 3);
		//Yii::app()->sess->clear('uid');

		if (isset($_GET['sid']))
		{
			$sid = str_replace('"', '', $_GET['sid']);
			$sid = str_replace("'", '', $sid);
			Yii::app()->sess->clear('uid');
			Yii::log("Preprocess request - We have been given new sid " . $sid, CLogger::LEVEL_WARNING, 'system.test.kim');

			$criteria = new CDbCriteria;
			$criteria->addCondition("sid = '" . $sid . "'");
			$user = User::model()->find($criteria);
			if ($user == null)
			{
				Yii::log("Preprocess request - This sid (" . $sid . ") is invalid. Aborting" , CLogger::LEVEL_WARNING, 'system.test.kim');
				throw new CHttpException(500,'Cannot continue without a valid sid');
			}
			Yii::app()->sess->set('uid', $user->id);
			Yii::app()->sess->set('uid_email', $user->email_address);
			Yii::app()->sess->set('uid_name', $user->display_name);
			Yii::app()->sess->set('sid', $sid);	//@@ Set sid too 'cos iframes not trusted. Google 'P3P'
			Yii::log("Preprocess request - sid validated. Setting uid to " . Yii::app()->sess->get('uid'), CLogger::LEVEL_WARNING, 'system.test.kim');
		}

		if (!(Yii::app()->sess->exists('uid')))
		{
			Yii::log("Preprocess request - uid is not set. Aborting" , CLogger::LEVEL_WARNING, 'system.test.kim');
			throw new CHttpException(500,'Cannot continue without a uid');
		}
	}
}
?>
