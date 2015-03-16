<?php

/**
 * Session management
 */

class Sess extends CComponent
//class Sess extends CApplicationComponent 
{
	private $cookies = true;

	public function init()
	{
	}

	public function set($key, $value)
	{
		if (trim($value) == "")
			return $this->clear($key);

		if ($this->cookies)
			Yii::app()->session[$key] = $value;

		$ownerString = $this->buildOwnerString();
		$criteria = new CDbCriteria;
		$criteria->addCondition("owner_string = '" . $ownerString . "'");
		$criteria->addCondition("key_string = '" . $key . "'");
		$session = Session::model()->find($criteria);
		if ($session)
		{
			$session->value_string = $value;
			$session->modified = date("Y-m-d H:i:s");
			if (!( $session->save()))
			{
				Yii::log("Session set - update error! Key value was : " . $key , CLogger::LEVEL_WARNING, 'system.test.kim');
				throw new CHttpException(400,'Error updating session record');
			}
		}
		else
		{
			$session = new Session;
			$session->owner_string = $ownerString;
			$session->key_string = $key;
			$session->value_string = $value;
			$session->created = date("Y-m-d H:i:s");
			$session->modified = date("Y-m-d H:i:s");
			if (!$session->save())
			{
				Yii::log("Session set - Write error! Key value was : " . $key , CLogger::LEVEL_WARNING, 'system.test.kim');
				throw new CHttpException(400,'Error creating session record');
			}
		}
	}

	public function clear($key)
	{
		if ($this->cookies)
			Yii::app()->session[$key] = "";

		$criteria = new CDbCriteria;
		$criteria->addCondition("owner_string = '" . $this->buildOwnerString() . "'");
		$criteria->addCondition("key_string = '" . $key . "'");
		$session = Session::model()->find($criteria);
		if ($session)
			$session->delete();
	}

	public function get($key)
	{
		if (($this->cookies) || ($key == "uid") || ($key == "sid"))
			$value = Yii::app()->session[$key];

		//if (trim($value) == "")
			//return;

		$criteria = new CDbCriteria;
		$criteria->addCondition("owner_string = '" . $this->buildOwnerString() . "'");
		$criteria->addCondition("key_string = '" . $key . "'");
		$session = Session::model()->find($criteria);
		if ($session == null)
			Yii::log("Session get - couldnt find key '" . $key . "'", CLogger::LEVEL_WARNING, 'system.test.kim');
		else
			$value = $session->value_string;
		return $value;
	}

	public function exists($key)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("owner_string = '" . $this->buildOwnerString() . "'");
		$criteria->addCondition("key_string = '" . $key . "'");
		$session = Session::model()->find($criteria);
		if ($session == null)
			return false;
		else
			return true;
	}

	private function buildOwnerString()
	{
		return $this->getIP();
		//return $this->getIP() . "|^^|" . $_SERVER['HTTP_USER_AGENT'];
	}

	public function getIP()
	{
		$ip = "UNKNOWN-" . rand();
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		return $ip;
	}
}
