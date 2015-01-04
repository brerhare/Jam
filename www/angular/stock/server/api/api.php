<?php


/*****
This .htaccess is required in the current directory ...
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-s
RewriteRule ^(.*)$ api.php?request=$1 [QSA,NC,L]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^(.*)$ api.php [QSA,NC,L]
RewriteCond %{REQUEST_FILENAME} -s
RewriteRule ^(.*)$ api.php [QSA,NC,L]
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
logWrite('$_GET = '. print_r($_GET, true));
logWrite('$_POST = '. print_r($_POST, true));
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

// STOCK_CUSTOMER

	protected function stock_customer()
	{
		logWrite("Method = " . $this->method);
		$uid = 1;	//@@NB: hardcoded

		$allColumns  = array('id', 'uid', 'name', 'address1', 'address2', 'address3', 'post_code', 'contact_title', 'contact_first_name', 'contact_last_name', 'telephone', 'mobile', 'fax', 'email', 'discount_percent', 'balance', 'link_field', 'notes', 'CIF', 'forma_de_pago', /*****/ 'stock_markup_group_id', 'stock_area_id');
		$showColumns = array('id', 'uid', 'name', 'discount_percent', 'telephone', 'forma_de_pago');

		if ($this->method == 'GET')
		{
			$id = trim($this->args[0]);
			if ($id)
			{
				// Single item
				$arr = array();
				$query = DB::query("SELECT * FROM stock_customer WHERE uid=%i AND id=%i", $uid, $id);
				if ($query)
				{
					foreach ($allColumns as $column)
						$arr[$column] = $query[0][$column];
				}
logWrite('arr = ' . print_r($arr, true));
				return $arr;
			}
			else
			{
				// All items
				$arr = array();
				$ix = 0;
				$query = DB::query("SELECT * FROM stock_customer WHERE uid=%i", $uid);
				foreach ($query as $q) {
					foreach ($showColumns as $column)
						$arr[$ix][$column] = $q[$column];
					$ix++;
	if ($ix > 100) break;	// @@TODO: remove!
				}
				return $arr;
			}
		}
	}

// STOCK_MARKUP_GROUP

	protected function stock_markup_group()
	{
		logWrite("Method = " . $this->method);
		$uid = 1;	//@@NB: hardcoded

		$allColumns  = array('id', 'uid', 'description', 'percent', 'is_default');
		$postColumns = array(             'description', 'percent', 'is_default');
		$putColumns  = array('id',        'description', 'percent', 'is_default');

		if ($this->method == 'GET')
		{
			$id = trim($this->args[0]);
			if ($id)
			{
				// Single item
				logWrite("GET a customer, id = " . $id);
			}
			else
			{
				// All items
				$arr = array();
				$ix = 0;
				$query = DB::query("SELECT * FROM stock_markup_group WHERE uid=%i", $uid);
				foreach ($query as $q) {
					foreach ($allColumns as $column)
    					$arr[$ix][$column] = $q[$column];
					$ix++;
				}
				return $arr;
			}
		}
		else if ($this->method == 'POST')
		{
			$obj = json_decode(file_get_contents("php://input"),true);
logWrite("got json obj=".print_r($obj, true));
            $keys = array_keys($obj);
			$values = array();
			$values['uid'] = $uid;
			foreach ($postColumns as $column) {
				if (!in_array($column, $keys)) {
logWrite("missing key for col=".print_r($column, true));
					$values[$column] = NULL;
				}
				else {
logWrite("matched key for col=".print_r($column, true));
					$values[$column] = $obj[$column];
				}
			}
			DB::insert('stock_markup_group', $values);
			return 'ok';
		}
		else if ($this->method == 'PUT')
		{
			$obj = json_decode(file_get_contents("php://input"),true);
logWrite("got json obj=".print_r($obj, true));
			// Pick up original
			$id = (int) $this->args[0];
			if ($id == 0)
				return "fail";
           	$keys = array_keys($obj);
			$values = array();
			foreach ($putColumns as $column) {
				if (in_array($column, $keys)) {
logWrite("matched key for col=".print_r($column, true));
					$values[$column] = $obj[$column];
				}
			}
			DB::update('stock_markup_group', $values, "id=%i", $id);
			return 'ok';
		}
		else if ($this->method == 'DELETE')
		{
			$id = (int) $this->args[0];
			if ($id != 0)
			{
				DB::delete('stock_markup_group', "id=%i", $id);
				return 'ok';
			}
			return 'ok';
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
