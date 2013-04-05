<?php

/**
 * This is the model class for table "transaction".
 *
 * The followings are the available columns in table 'transaction':
 * @property integer $id
 * @property string $timeStamp
 * @property string $ip
 * @property string $email
 * @property integer $adults
 * @property integer $children
 * @property string $telephone
 * @property string $orderNum
 * @property integer $amount
 * @property string $cardName
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $address4
 * @property string $city
 * @property string $state
 * @property string $postCode
 * @property string $countryShort
 * @property string $message
 */
class Transaction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
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
		return 'transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('adults, children amount', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>128),
			array('email, telephone, orderNum, cardName, address1, address2, address3, address4, city, state, postCode, countryShort, message', 'length', 'max'=>255),
			array('timeStamp', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, timeStamp, ip, email, adults, children, telephone, orderNum, amount, cardName, address1, address2, address3, address4, city, state, postCode, countryShort, message', 'safe', 'on'=>'search'),
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
			'id' => 'Id',
			'timeStamp' => 'Time Stamp',
			'ip' => 'Ip',
			'email' => 'Email',
			'adults' => 'Adults',
			'children' => 'Children',
			'telephone' => 'Telephone',
			'orderNum' => 'Order Num',
			'amount' => 'Amount',
			'cardName' => 'Card Name',
			'address1' => 'Address1',
			'address2' => 'Address2',
			'address3' => 'Address3',
			'address4' => 'Address4',
			'city' => 'City',
			'state' => 'County',	/* kim changed (was 'State') */
			'postCode' => 'Post Code',
			'countryShort' => 'Country Short',
			'message' => 'Message',
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

		$criteria->compare('timeStamp',$this->timeStamp,true);

		$criteria->compare('ip',$this->ip,true);

		$criteria->compare('email',$this->email,true);

		$criteria->compare('adults',$this->adults);

		$criteria->compare('children',$this->children);

		$criteria->compare('telephone',$this->telephone,true);

		$criteria->compare('orderNum',$this->orderNum,true);

		$criteria->compare('amount',$this->amount);

		$criteria->compare('cardName',$this->cardName,true);

		$criteria->compare('address1',$this->address1,true);

		$criteria->compare('address2',$this->address2,true);

		$criteria->compare('address3',$this->address3,true);

		$criteria->compare('address4',$this->address4,true);

		$criteria->compare('city',$this->city,true);

		$criteria->compare('state',$this->state,true);

		$criteria->compare('postCode',$this->postCode,true);

		$criteria->compare('countryShort',$this->countryShort,true);

		$criteria->compare('message',$this->message,true);

		return new CActiveDataProvider('Transaction', array(
			'criteria'=>$criteria,
		));
	}
}
