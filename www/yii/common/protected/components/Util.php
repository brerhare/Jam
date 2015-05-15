<?php

/**
 * Util contains mostly static functions
 */

class Util
{
	public function encrypt($str)
	{
		$key = "password to (en/de)crypt";
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $str, MCRYPT_MODE_CBC, md5(md5($key))));
	}

	public function decrypt($str)
	{
		$key = "password to (en/de)crypt";
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($str), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	}

}
