<?php

// Determine which version of the controller to load. The logged in member might be locked to a program

$member=Member::model()->findByPk(Yii::app()->session['eid']);
Yii::app()->session['pid'] = 0;
if ($member != null)
{
	Yii::app()->session['pid'] = $member->lock_program_id;
	if ($member->lock_program_id == 6)	// WS Wild Seasons
		require 'EventController_wildseasons_co_uk.php';
	else
		require 'EventController_default.php';
}
else
	throw new CHttpException(400, 'The request cannot be fulfilled. Please logout and login again');

//die('pid='.Yii::app()->session['pid']);
