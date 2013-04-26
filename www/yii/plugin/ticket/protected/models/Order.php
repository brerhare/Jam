<?php

/**
 * This is the model class for table "ticket_order".
 *
 * The followings are the available columns in table 'ticket_order':
 * @property integer $id
 * @property integer $uid
 * @property string $ip
 * @property string $sid
 * @property string $order_number
 * @property integer $vendor_id
 * @property integer $event_id
 * @property string $http_ticket_type_area
 * @property string $http_ticket_type_id
 * @property string $http_ticket_type_qty
 * @property string $http_ticket_type_price
 * @property string $http_ticket_type_total
 * @property string $http_total
 * @property string $auth_code
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
		return 'ticket_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, ip, vendor_id, event_id', 'required'),
			array('uid, vendor_id, event_id', 'numerical', 'integerOnly'=>true),
			array('ip, sid, order_number', 'length', 'max'=>255),
			array('http_ticket_type_area, http_ticket_type_id, http_ticket_type_qty, http_ticket_type_price, http_ticket_type_total, http_total, auth_code', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, ip, sid, order_number, vendor_id, event_id, http_ticket_type_area, http_ticket_type_id, http_ticket_type_qty, http_ticket_type_price, http_ticket_type_total, http_total, auth_code', 'safe', 'on'=>'search'),
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
			'vendor_id' => 'Vendor',
			'event_id' => 'Event',
			'http_ticket_type_area' => 'Http Ticket Type Area',
			'http_ticket_type_id' => 'Http Ticket Type',
			'http_ticket_type_qty' => 'Http Ticket Type Qty',
			'http_ticket_type_price' => 'Http Ticket Type Price',
			'http_ticket_type_total' => 'Http Ticket Type Total',
			'http_total' => 'Http Total',
			'auth_code' => 'Auth Code',
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
		$criteria->compare('sid',$this->sid,true);
		$criteria->compare('order_number',$this->order_number,true);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('http_ticket_type_area',$this->http_ticket_type_area,true);
		$criteria->compare('http_ticket_type_id',$this->http_ticket_type_id,true);
		$criteria->compare('http_ticket_type_qty',$this->http_ticket_type_qty,true);
		$criteria->compare('http_ticket_type_price',$this->http_ticket_type_price,true);
		$criteria->compare('http_ticket_type_total',$this->http_ticket_type_total,true);
		$criteria->compare('http_total',$this->http_total,true);
		$criteria->compare('auth_code',$this->auth_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}