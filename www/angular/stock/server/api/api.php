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

		$allColumns  = array('id', 'uid', 'name', 'address1', 'address2', 'address3', 'post_code', 'contact', 'telephone', 'mobile', 'fax', 'email', 'discount_percent', 'balance', 'link_field', 'notes', 'CIF', 'forma_de_pago', /*****/ 'stock_markup_group_id', 'stock_area_id');
		$postColumns  = array(             'name', 'address1', 'address2', 'address3', 'post_code', 'contact', 'telephone', 'mobile', 'fax', 'email', 'discount_percent', 'balance', 'link_field', 'notes', 'CIF', 'forma_de_pago', /*****/ 'stock_markup_group_id', 'stock_area_id');
		$putColumns  = array('id',        'name', 'address1', 'address2', 'address3', 'post_code', 'contact', 'telephone', 'mobile', 'fax', 'email', 'discount_percent', 'balance', 'link_field', 'notes', 'CIF', 'forma_de_pago', /*****/ 'stock_markup_group_id', 'stock_area_id');
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
	if ($ix >= 100) break;	// @@TODO: remove!
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
			$idArr = array();
			DB::insert('stock_customer', $values);
			$idArr['id'] = DB::insertId();
			return $idArr;
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
			DB::update('stock_customer', $values, "id=%i", $id);
			return 'ok';
		}
		else if ($this->method == 'DELETE')
		{
			$id = (int) $this->args[0];
			if ($id != 0)
			{
				DB::delete('stock_customer', "id=%i", $id);
				return 'ok';
			}
			return 'ok';
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
				logWrite("GET a markup group, id = " . $id);
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
			$idArr = array();
			DB::insert('stock_markup_group', $values);
			$idArr['id'] = DB::insertId();
			return $idArr;
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

// STOCK_AREA

	protected function stock_area()
	{
		logWrite("Method = " . $this->method);
		$uid = 1;	//@@NB: hardcoded

		$allColumns  = array('id', 'uid', 'name');
		$postColumns = array(             'name');
		$putColumns  = array('id',        'name');

		if ($this->method == 'GET')
		{
			$id = trim($this->args[0]);
			if ($id)
			{
				// Single item
				logWrite("GET an area, id = " . $id);
			}
			else
			{
				// All items
				$arr = array();
				$ix = 0;
				$query = DB::query("SELECT * FROM stock_area WHERE uid=%i", $uid);
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
			$idArr = array();
			DB::insert('stock_area', $values);
			$idArr['id'] = DB::insertId();
			return $idArr;
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
			DB::update('stock_area', $values, "id=%i", $id);
			return 'ok';
		}
		else if ($this->method == 'DELETE')
		{
			$id = (int) $this->args[0];
			if ($id != 0)
			{
				DB::delete('stock_area', "id=%i", $id);
				return 'ok';
			}
			return 'ok';
		}
	}

function my_error_handler($params) {
	logWrite("Error: " . $params["error"]);
	logWrite("Query: " . $params["query"]);
}

// STOCK_PRODUCT

	protected function stock_product()
	{
DB::$error_handler = 'my_error_handler';
		logWrite("Method = " . $this->method);
		$uid = 1;	//@@NB: hardcoded

		$allColumns  = array('id', 'uid', 'name', 'description', 'cost', 'weight', 'height', 'width', 'depth', 'volume',  /*****/ 'stock_group_id', 'stock_vat_id');
		$postColumns = array(             'name', 'description', 'cost', 'weight', 'height', 'width', 'depth', 'volume',  /*****/ 'stock_group_id', 'stock_vat_id');
		$putColumns  = array('id',        'name', 'description', 'cost', 'weight', 'height', 'width', 'depth', 'volume',  /*****/ 'stock_group_id', 'stock_vat_id');
		$showColumns = array('id', 'uid', 'name', 'cost', 'price');

		if ($this->method == 'GET')
		{
			$id = trim($this->args[0]);
			if ($id)
			{
				// Single item
				$arr = array();
				$query = DB::query("SELECT * FROM stock_product WHERE uid=%i AND id=%i", $uid, $id);
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
				$query = DB::query("SELECT * FROM stock_product WHERE uid=%i", $uid);
				foreach ($query as $q) {
					foreach ($showColumns as $column)
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
			$idArr = array();
logWrite("write not=" . print_r($values,true));
			DB::insert('stock_product', $values);
logWrite("write done, now to check it");
			$idArr['id'] = DB::insertId();
logWrite("write done, id checked");
			return $idArr;
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
			DB::update('stock_product', $values, "id=%i", $id);
			return 'ok';
		}
		else if ($this->method == 'DELETE')
		{
			$id = (int) $this->args[0];
			if ($id != 0)
			{
				DB::delete('stock_product', "id=%i", $id);
				return 'ok';
			}
			return 'ok';
		}
	}

// STOCK_VAT

	protected function stock_vat()
	{
		logWrite("Method = " . $this->method);
		$uid = 1;	//@@NB: hardcoded

		$allColumns  = array('id', 'uid', 'description', 'rate', 'is_default');
		$postColumns = array(             'description', 'rate', 'is_default');
		$putColumns  = array('id',        'description', 'rate', 'is_default');

		if ($this->method == 'GET')
		{
			$id = trim($this->args[0]);
			if ($id)
			{
				// Single item
				logWrite("GET a vat rate, id = " . $id);
			}
			else
			{
				// All items
				$arr = array();
				$ix = 0;
				$query = DB::query("SELECT * FROM stock_vat WHERE uid=%i", $uid);
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
			$idArr = array();
			DB::insert('stock_vat', $values);
			$idArr['id'] = DB::insertId();
			return $idArr;
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
			DB::update('stock_vat', $values, "id=%i", $id);
			return 'ok';
		}
		else if ($this->method == 'DELETE')
		{
			$id = (int) $this->args[0];
			if ($id != 0)
			{
				DB::delete('stock_vat', "id=%i", $id);
				return 'ok';
			}
			return 'ok';
		}
	}

// STOCK_GROUP

	protected function stock_group()
	{
		logWrite("Method = " . $this->method);
		$uid = 1;	//@@NB: hardcoded

		$allColumns  = array('id', 'uid', 'name', 'parent_id');
		$postColumns = array(             'name', 'parent_id');
		$putColumns  = array('id',        'name', 'parent_id');

		if ($this->method == 'GET')
		{
			$id = trim($this->args[0]);
			if ($id)
			{
				// Single item
				logWrite("GET an area, id = " . $id);
			}
			else
			{
				// All items
				$arr = array();
				$ix = 0;
				$query = DB::query("SELECT * FROM stock_group WHERE uid=%i", $uid);
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
			$idArr = array();
			DB::insert('stock_group', $values);
			$idArr['id'] = DB::insertId();
			return $idArr;
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
			DB::update('stock_group', $values, "id=%i", $id);
			return 'ok';
		}
		else if ($this->method == 'DELETE')
		{
			$id = (int) $this->args[0];
			if ($id != 0)
			{
				DB::delete('stock_group', "id=%i", $id);
				return 'ok';
			}
			return 'ok';
		}
	}

	protected function stock_product_price()
	{
		logWrite("Method = " . $this->method);
		$uid = 1;	//@@NB: hardcoded

		$allColumns  = array('id', 'uid', 'start_date', 'end_date', 'price', 'stock_product_id', 'stock_markup_group_id');
		$postColumns = array(             'start_date', 'end_date', 'price', 'stock_product_id', 'stock_markup_group_id');
		$putColumns  = array('id',        'start_date', 'end_date', 'price', 'stock_product_id', 'stock_markup_group_id');

		if ($this->method == 'GET')
		{
			$id = trim($this->args[0]);
			if ($id)
			{
				// Single item
				logWrite("GET a product price, id = " . $id);
			}
			else
			{
				// All items
				$arr = array();
				$ix = 0;
				$query = DB::query("SELECT * FROM stock_product_price WHERE uid=%i", $uid);
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
			$idArr = array();
			DB::insert('stock_product_price', $values);
			$idArr['id'] = DB::insertId();
			return $idArr;
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
			DB::update('stock_product_price', $values, "id=%i", $id);
			return 'ok';
		}
		else if ($this->method == 'DELETE')
		{
			$id = (int) $this->args[0];
			if ($id != 0)
			{
				DB::delete('stock_product_price', "id=%i", $id);
				return 'ok';
			}
			return 'ok';
		}
	}


} // class MyAPI

// ----------------------------------------------------------------------

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
