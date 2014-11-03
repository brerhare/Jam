<?php

/**
 * This is the model class for table "ticket_auth".
 *
 * The followings are the available columns in table 'ticket_auth':
 * @property integer $id
 * @property integer $uid
 * @property string $order_number
 * @property string $card_name
 * @property string $card_number
 * @property string $expiry_month
 * @property string $expiry_year
 * @property string $cv2
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $address4
 * @property string $city
 * @property string $state
 * @property string $post_code
 * @property string $country_short
 * @property string $amount
 * @property string $currency_short
 * @property string $auth_code
 */
class Auth extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Auth the static model class
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
		return 'ticket_auth';
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
			array('uid', 'numerical', 'integerOnly'=>true),
			array('order_number, card_name, card_number, address1, address2, address3, address4, city, state, post_code, country_short, auth_code', 'length', 'max'=>255),
			array('expiry_month, expiry_year, cv2, amount, currency_short', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, order_number, card_name, card_number, expiry_month, expiry_year, cv2, address1, address2, address3, address4, city, state, post_code, country_short, amount, currency_short, auth_code', 'safe', 'on'=>'search'),
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
			'order_number' => 'Order Number',
			'card_name' => 'Card Name',
			'card_number' => 'Card Number',
			'expiry_month' => 'Expiry Month',
			'expiry_year' => 'Expiry Year',
			'cv2' => 'Cv2',
			'address1' => 'Address1',
			'address2' => 'Address2',
			'address3' => 'Address3',
			'address4' => 'Address4',
			'city' => 'City',
			'state' => 'State',
			'post_code' => 'Post Code',
			'country_short' => 'Country Short',
			'amount' => 'Amount',
			'currency_short' => 'Currency Short',
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
		$criteria->compare('order_number',$this->order_number,true);
		$criteria->compare('card_name',$this->card_name,true);
		$criteria->compare('card_number',$this->card_number,true);
		$criteria->compare('expiry_month',$this->expiry_month,true);
		$criteria->compare('expiry_year',$this->expiry_year,true);
		$criteria->compare('cv2',$this->cv2,true);
		$criteria->compare('address1',$this->address1,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('address3',$this->address3,true);
		$criteria->compare('address4',$this->address4,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('country_short',$this->country_short,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('currency_short',$this->currency_short,true);
		$criteria->compare('auth_code',$this->auth_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}