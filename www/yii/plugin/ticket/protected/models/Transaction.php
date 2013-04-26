<?php

/**
 * This is the model class for table "ticket_transaction".
 *
 * The followings are the available columns in table 'ticket_transaction':
 * @property integer $id
 * @property integer $uid
 * @property string $ip
 * @property string $order_number
 * @property string $timestamp
 * @property string $email
 * @property string $telephone
 * @property string $amount
 * @property integer $vendor_id
 * @property integer $event_id
 * @property integer $area_id
 * @property integer $ticket_type_id
 * @property string $ticket_number
 * @property string $auth_code
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
			array('uid, vendor_id, event_id, area_id, ticket_type_id', 'numerical', 'integerOnly'=>true),
			array('ip, order_number, email, telephone, ticket_number', 'length', 'max'=>255),
			array('amount', 'length', 'max'=>10),
			array('auth_code', 'length', 'max'=>45),
			array('timestamp', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, ip, order_number, timestamp, email, telephone, amount, vendor_id, event_id, area_id, ticket_type_id, ticket_number, auth_code', 'safe', 'on'=>'search'),
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
			'order_number' => 'Order Number',
			'timestamp' => 'Timestamp',
			'email' => 'Email',
			'telephone' => 'Telephone',
			'amount' => 'Amount',
			'vendor_id' => 'Vendor',
			'event_id' => 'Event',
			'area_id' => 'Area',
			'ticket_type_id' => 'Ticket Type',
			'ticket_number' => 'Ticket Number',
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
		$criteria->compare('order_number',$this->order_number,true);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('area_id',$this->area_id);
		$criteria->compare('ticket_type_id',$this->ticket_type_id);
		$criteria->compare('ticket_number',$this->ticket_number,true);
		$criteria->compare('auth_code',$this->auth_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}