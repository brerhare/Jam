<?php

/**
 * This is the model class for table "product_order".
 *
 * The followings are the available columns in table 'product_order':
 * @property integer $id
 * @property integer $uid
 * @property string $ip
 * @property string $sid
 * @property string $order_number
 * @property string $vendor_gateway_id
 * @property string $vendor_gateway_password
 * @property string $http_product_id
 * @property string $http_option_id
 * @property string $http_qty
 * @property string $http_price
 * @property string $http_line_total
 * @property string $http_shipping_id
 * @property string $http_total
 * @property string $auth_code
 * @property string $return_url
 * @property string $gu
 * @property string $gp
 * @property string $email_address
 * @property string $delivery_address1
 * @property string $delivery_address2
 * @property string $delivery_address3
 * @property string $delivery_address4
 * @property string $delivery_post_code
 * @property string $notes
 * @property string $telephone
 * @property string $card_name
 * @property string $card_number
 * @property string $card_expiry_month
 * @property string $card_expiry_year
 * @property string $card_cv2
 * @property string $card_address1
 * @property string $card_address2
 * @property string $card_address3
 * @property string $card_address4
 * @property string $card_city
 * @property string $card_state
 * @property string $card_post_code
 * @property string $card_country_short
 * @property string $card_currency_short
 * @property string $card_amount
 */
class Order extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, ip', 'required'),
			array('uid', 'numerical', 'integerOnly'=>true),
			array('ip, sid, order_number, vendor_gateway_id, vendor_gateway_password, return_url, gu, gp, email_address, delivery_address1, delivery_address2, delivery_address3, delivery_address4, delivery_post_code, telephone, card_name, card_number, card_cv2, card_address1, card_address2, card_address3, card_address4, card_city, card_state', 'length', 'max'=>255),
			array('http_product_id, http_option_id, http_qty, http_price, http_line_total, http_shipping_id, http_total, auth_code, card_expiry_month, card_expiry_year, card_post_code, card_country_short, card_currency_short, card_amount', 'length', 'max'=>45),
			array('notes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, ip, sid, order_number, vendor_gateway_id, vendor_gateway_password, http_product_id, http_option_id, http_qty, http_price, http_line_total, http_shipping_id, http_total, auth_code, return_url, gu, gp, email_address, delivery_address1, delivery_address2, delivery_address3, delivery_address4, delivery_post_code, notes, telephone, card_name, card_number, card_expiry_month, card_expiry_year, card_cv2, card_address1, card_address2, card_address3, card_address4, card_city, card_state, card_post_code, card_country_short, card_currency_short, card_amount', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => 'Uid',
			'ip' => 'Ip',
			'sid' => 'Sid',
			'order_number' => 'Order Number',
			'vendor_gateway_id' => 'Vendor Gateway',
			'vendor_gateway_password' => 'Vendor Gateway Password',
			'http_product_id' => 'Http Product',
			'http_option_id' => 'Http Option',
			'http_qty' => 'Http Qty',
			'http_price' => 'Http Price',
			'http_line_total' => 'Http Line Total',
			'http_shipping_id' => 'Http Shipping',
			'http_total' => 'Http Total',
			'auth_code' => 'Auth Code',
			'return_url' => 'Return Url',
			'gu' => 'Gu',
			'gp' => 'Gp',
			'email_address' => 'Email Address',
			'delivery_address1' => 'Delivery Address1',
			'delivery_address2' => 'Delivery Address2',
			'delivery_address3' => 'Delivery Address3',
			'delivery_address4' => 'Delivery Address4',
			'delivery_post_code' => 'Delivery Post Code',
			'notes' => 'Notes',
			'telephone' => 'Telephone',
			'card_name' => 'Card Name',
			'card_number' => 'Card Number',
			'card_expiry_month' => 'Card Expiry Month',
			'card_expiry_year' => 'Card Expiry Year',
			'card_cv2' => 'Card Cv2',
			'card_address1' => 'Card Address1',
			'card_address2' => 'Card Address2',
			'card_address3' => 'Card Address3',
			'card_address4' => 'Card Address4',
			'card_city' => 'Card City',
			'card_state' => 'Card State',
			'card_post_code' => 'Card Post Code',
			'card_country_short' => 'Card Country Short',
			'card_currency_short' => 'Card Currency Short',
			'card_amount' => 'Card Amount',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		//$criteria->compare('uid',$this->uid);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('sid',$this->sid,true);
		$criteria->compare('order_number',$this->order_number,true);
		$criteria->compare('vendor_gateway_id',$this->vendor_gateway_id,true);
		$criteria->compare('vendor_gateway_password',$this->vendor_gateway_password,true);
		$criteria->compare('http_product_id',$this->http_product_id,true);
		$criteria->compare('http_option_id',$this->http_option_id,true);
		$criteria->compare('http_qty',$this->http_qty,true);
		$criteria->compare('http_price',$this->http_price,true);
		$criteria->compare('http_line_total',$this->http_line_total,true);
		$criteria->compare('http_shipping_id',$this->http_shipping_id,true);
		$criteria->compare('http_total',$this->http_total,true);
		$criteria->compare('auth_code',$this->auth_code,true);
		$criteria->compare('return_url',$this->return_url,true);
		$criteria->compare('gu',$this->gu,true);
		$criteria->compare('gp',$this->gp,true);
		$criteria->compare('email_address',$this->email_address,true);
		$criteria->compare('delivery_address1',$this->delivery_address1,true);
		$criteria->compare('delivery_address2',$this->delivery_address2,true);
		$criteria->compare('delivery_address3',$this->delivery_address3,true);
		$criteria->compare('delivery_address4',$this->delivery_address4,true);
		$criteria->compare('delivery_post_code',$this->delivery_post_code,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('card_name',$this->card_name,true);
		$criteria->compare('card_number',$this->card_number,true);
		$criteria->compare('card_expiry_month',$this->card_expiry_month,true);
		$criteria->compare('card_expiry_year',$this->card_expiry_year,true);
		$criteria->compare('card_cv2',$this->card_cv2,true);
		$criteria->compare('card_address1',$this->card_address1,true);
		$criteria->compare('card_address2',$this->card_address2,true);
		$criteria->compare('card_address3',$this->card_address3,true);
		$criteria->compare('card_address4',$this->card_address4,true);
		$criteria->compare('card_city',$this->card_city,true);
		$criteria->compare('card_state',$this->card_state,true);
		$criteria->compare('card_post_code',$this->card_post_code,true);
		$criteria->compare('card_country_short',$this->card_country_short,true);
		$criteria->compare('card_currency_short',$this->card_currency_short,true);
		$criteria->compare('card_amount',$this->card_amount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
