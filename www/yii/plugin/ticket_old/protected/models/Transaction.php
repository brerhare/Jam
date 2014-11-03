<?php

/**
 * This is the model class for table "ticket_transaction".
 *
 * The followings are the available columns in table 'ticket_transaction':
 * @property integer $id
 * @property integer $uid
 * @property string $ip
 * @property string $timestamp
 * @property string $order_number
 * @property string $auth_code
 * @property string $email
 * @property string $telephone
 * @property integer $vendor_id
 * @property integer $event_id
 * @property string $http_area_id
 * @property string $http_ticket_type_id
 * @property string $http_ticket_qty
 * @property string $http_ticket_price
 * @property string $http_ticket_total
 * @property string $http_total
 */
class Transaction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Transaction the static model class
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
		return 'ticket_transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid', 'required'),
			array('uid, vendor_id, event_id', 'numerical', 'integerOnly'=>true),
			array('ip, order_number, email, telephone, http_area_id, http_ticket_type_id, http_ticket_qty, http_ticket_price, http_ticket_total, http_total', 'length', 'max'=>255),
			array('auth_code', 'length', 'max'=>45),
			array('timestamp', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, ip, timestamp, order_number, auth_code, email, telephone, vendor_id, event_id, http_area_id, http_ticket_type_id, http_ticket_qty, http_ticket_price, http_ticket_total, http_total', 'safe', 'on'=>'search'),
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
			'timestamp' => 'Timestamp',
			'order_number' => 'Order Number',
			'auth_code' => 'Auth Code',
			'email' => 'Email',
			'telephone' => 'Telephone',
			'vendor_id' => 'Vendor',
			'event_id' => 'Event',
			'http_area_id' => 'Http Area',
			'http_ticket_type_id' => 'Http Ticket Type',
			'http_ticket_qty' => 'Http Ticket Qty',
			'http_ticket_price' => 'Http Ticket Price',
			'http_ticket_total' => 'Http Ticket Total',
			'http_total' => 'Http Total',
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
		$criteria->compare('uid',$this->uid);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('order_number',$this->order_number,true);
		$criteria->compare('auth_code',$this->auth_code,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('http_area_id',$this->http_area_id,true);
		$criteria->compare('http_ticket_type_id',$this->http_ticket_type_id,true);
		$criteria->compare('http_ticket_qty',$this->http_ticket_qty,true);
		$criteria->compare('http_ticket_price',$this->http_ticket_price,true);
		$criteria->compare('http_ticket_total',$this->http_ticket_total,true);
		$criteria->compare('http_total',$this->http_total,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}