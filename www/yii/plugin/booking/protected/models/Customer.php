<?php

/**
 * This is the model class for table "booking_customer".
 *
 * The followings are the available columns in table 'booking_customer':
 * @property integer $id
 * @property integer $uid
 * @property string $ref
 * @property string $address_1
 * @property string $address_2
 * @property string $town
 * @property string $county
 * @property string $post_code
 * @property string $telephone
 * @property string $email
 * @property string $card_name
 * @property string $card_number
 * @property integer $card_expiry_mm
 * @property integer $card_expiry_yy
 * @property integer $card_cvv
	 * @property string $reservation_total
 * @property string $coupon_code
 * @property string $coupon_description
 * @property integer $coupon_type
 * @property string $coupon_value
 */
class Customer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return 'booking_customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, uid, ref, address_1, address_2, post_code, telephone, email, card_name, card_number, card_expiry_mm, card_expiry_yy, card_cvv', 'required'),
			array('uid, card_expiry_mm, card_expiry_yy, card_cvv', 'numerical', 'integerOnly'=>true),
			array('name, ref, address_1, address_2, town, county, post_code, telephone, email, card_name, card_number', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, ref, address_1, address_2, town, county, post_code, telephone, email, card_name, card_number, card_expiry_mm, card_expiry_yy, card_cvv', 'safe', 'on'=>'search'),
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
			'ref' => 'Ref',
			'address_1' => 'Address 1',
			'address_2' => 'Address 2',
			'town' => 'Address 3',
			'county' => 'Address 4',
			'post_code' => 'Post Code',
			'telephone' => 'Telephone',
			'email' => 'Email',
			'card_name' => 'Card Name',
			'card_number' => 'Card Number',
			'card_expiry_mm' => 'Card Expiry MM',
			'card_expiry_yy' => 'Card Expiry YY',
			'card_cvv' => 'Last 3 Digits on back',
			'reservation_total' => 'Reservation Total',
			'coupon_code' => 'Coupon Code',
			'coupon_description' => 'Coupon Description',
			'coupon_type' => 'Coupon Type',
			'coupon_value' => 'Coupon Value',
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
		$criteria->compare('ref',$this->ref,true);
		$criteria->compare('address_1',$this->address_1,true);
		$criteria->compare('address_2',$this->address_2,true);
		$criteria->compare('town',$this->town,true);
		$criteria->compare('county',$this->county,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('card_name',$this->card_name,true);
		$criteria->compare('card_number',$this->card_number,true);
		$criteria->compare('card_expiry_mm',$this->card_expiry_mm);
		$criteria->compare('card_expiry_yy',$this->card_expiry_yy);
		$criteria->compare('card_cvv',$this->card_cvv);
/*
		$criteria->compare('reservation_total',$this->reservation_total,true);
		$criteria->compare('coupon_code',$this->coupon_code,true);
		$criteria->compare('coupon_description',$this->coupon_description,true);
		$criteria->compare('coupon_type',$this->coupon_type);
		$criteria->compare('coupon_value',$this->coupon_value,true);
*/

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
