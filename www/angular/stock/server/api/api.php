<?php

/*****
This .htaccess is required in the current directory ...
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule (.*)$ api.php?request=$1 [QSA,NC,L]
*****/

require_once 'API.class.php';
require_once 'log.php';

logClear();
logWrite(print_r('$_SERVER_["REQUEST_METHOD"] = '. $_SERVER['REQUEST_METHOD'], true));
logWrite(print_r('$_SERVER_["REQUEST_URI"] = '. $_SERVER['REQUEST_URI'], true));
logWrite('$_REQUEST = ' . print_r($_REQUEST, true));
logWrite('$_SERVER = ' . print_r($_SERVER, true));


class MyAPI extends API
{
    protected $User;

    public function __construct($request, $origin) {
        parent::__construct($request);

/*****
        // Abstracted out for example
        $APIKey = new Models\APIKey();
        $User = new Models\User();

        if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            throw new Exception('Invalid API Key');
        } else if (array_key_exists('token', $this->request) &&
             !$User->get('token', $this->request['token'])) {

            throw new Exception('Invalid User Token');
        }

        $this->User = $User;
*****/
    }


    /**
     * Example of an Endpoint
     */
     protected function group() {
logWrite($this->method);
        if ($this->method == 'GET') {

$arr = array();
$arr[0]['Id'] = 1;
$arr[0]['Name'] = 'aaa';
$arr[1]['Id'] = 2;
$arr[1]['Name'] = 'bbb';
return $arr;
			//return "[{'Id':1, 'Name':'aaa'},{'Id':2, 'Name':'bbb'},{'Id':3, 'Name':'ccc'}]";
            //return "Your name is " . $this->User->name;
        } else {
            return "Only accepts GET requests";
        }
    }
}

// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

logWrite('start');

try {
logWrite('abt to try');
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    $result = $API->processAPI();
	logWrite($result);
	echo $result;
} catch (Exception $e) {
logWrite('uh oh - excep');
    logWrite( json_encode(Array('Error' => $e->getMessage())) );
    echo json_encode(Array('error' => $e->getMessage()));

}


?>