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
		$defaultUsername = "admin";
		$defaultPassword = "backend";
		$this->errorCode = self::ERROR_NONE;
		Yii::app()->session['admin_user_id'] = 0;
		Yii::app()->session['admin_user_display_name'] = "";

		// check for the hardcoded values
		if (($this->username == $defaultUsername) && ($this->password == $defaultPassword))
		return !$this->errorCode;

		// Check the db plugin password (defines are in protected/backend/config/main.php)
		$hostName = Yii::app()->params['dbHost'];
		$dbName = Yii::app()->params['dbName'];
		$dbUser = Yii::app()->params['dbUser'];
		$dbPass = Yii::app()->params['dbPass'];
		$connect_id = mysql_connect($hostName, $dbUser, $dbPass);
		if ($connect_id) {
			if (mysql_select_db($dbName)) {
				$query = "select * from admin_user where email_address = '" . $this->username . "'";
				$result = mysql_query($query, $connect_id); 
				if (!$result)
					throw new CHttpException(400,'Cant query on password database');
				$row = mysql_fetch_assoc($result);
				if (!$row)
					$this->errorCode = self::ERROR_USERNAME_INVALID;
				else if ($row['password'] != $this->password)
					$this->errorCode = self::ERROR_PASSWORD_INVALID;
				else {
					$this->username = "admin";      // Hardcode to bypass Yii's controller permissions @@FIX
					Yii::app()->session['admin_user_id'] = $row['id'];        
					Yii::app()->session['admin_user_display_name'] = $row['display_name'];
					Yii::app()->session['admin_user_sid'] = $row['sid'];
				}
			} else throw new CHttpException(400,'Cant select on password database');
		} else throw new CHttpException(400,'Cant connect to password database');
		return !$this->errorCode;
	}
}
