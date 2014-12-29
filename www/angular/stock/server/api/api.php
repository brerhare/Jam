<?php


/*****
This .htaccess is required in the current directory ...
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule (.*)$ api.php?request=$1 [QSA,NC,L]
*****/

require_once 'api.class.php';
require_once 'log.php';
require_once 'meekrodb.2.3.class.php';

DB::$user = 'stock';
DB::$password = 'stock,';
DB::$dbName = 'stock';

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

        if (!array_key_exists('apiKey', $this->request))
            throw new Exception('No API Key provided');
        else if (!$APIKey->verifyKey($this->request['apiKey'], $origin))
            throw new Exception('Invalid API Key');
        else if (array_key_exists('token', $this->request) && !$User->get('token', $this->request['token']))
            throw new Exception('Invalid User Token');
        $this->User = $User;
*****/
    }

    /**
     * Endpoints
     */

	protected function stock_markup_group()
	{
		logWrite("method = " . $this->method);
		$uid = 1;	//@@NB: hardcoded
		if ($this->method == 'GET')
		{
			$arr = array();
			$ix = 0;
			$query = DB::query("SELECT * FROM stock_markup_group WHERE uid=%i", $uid);
			foreach ($query as $q) {
    			$arr[$ix]['id'] = $q['id'];
    			$arr[$ix]['description'] = $q['description'];
    			$arr[$ix]['percent'] = $q['percent'];
    			$arr[$ix]['isDefault'] = $q['is_default'];
				$ix++;
			}
			return $arr;
		}
		else
		{
			return "Only accepts GET requests";
		}
	}
}

// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER))
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];

try {
	$API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
	echo $API->processAPI();
} catch (Exception $e) {
	echo json_encode(Array('error' => $e->getMessage()));
}


?>
