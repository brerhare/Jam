<?php

/**
 * This is the model class for table "member".
 *
 * The followings are the available columns in table 'member':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $approved
 * @property string $business_name
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $address4
 * @property string $postcode
 * @property string $contact
 * @property string $web
 * @property string $email
 * @property string $phone
 * @property string $opening_hours
 * @property string $html_content
 * @property string $logo_path
 * @property string $slider_image_path
 * @property integer $public
 *
 * The followings are the available model relations:
 * @property Category[] $categories
 * @property FoodType[] $foodTypes
 */
class Member extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Member the static model class
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
		return 'member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, business_name', 'required'),
			array('approved, public', 'numerical', 'integerOnly'=>true),
			array('username, password, business_name, address1, address2, address3, address4, contact, web, email, phone, opening_hours, logo_path, slider_image_path', 'length', 'max'=>255),
			array('postcode', 'length', 'max'=>10),
			array('html_content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, approved, business_name, address1, address2, address3, address4, postcode, contact, web, email, phone, opening_hours, html_content, logo_path, slider_image_path, public', 'safe', 'on'=>'search'),
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
			'categories' => array(self::MANY_MANY, 'Category', 'member_has_category(member_id, category_id)'),
			'foodTypes' => array(self::MANY_MANY, 'FoodType', 'member_has_food_type(member_id, food_type_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'approved' => 'Approved',
			'business_name' => 'Business Name',
			'address1' => 'Address1',
			'address2' => 'Address2',
			'address3' => 'Address3',
			'address4' => 'Address4',
			'postcode' => 'Postcode',
			'contact' => 'Contact',
			'web' => 'Web',
			'email' => 'Email',
			'phone' => 'Phone',
			'opening_hours' => 'Opening Hours',
			'html_content' => 'Html Content',
			'logo_path' => 'Logo Path',
			'slider_image_path' => 'Slider Image Path',
			'public' => 'Public',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('business_name',$this->business_name,true);
		$criteria->compare('address1',$this->address1,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('address3',$this->address3,true);
		$criteria->compare('address4',$this->address4,true);
		$criteria->compare('postcode',$this->postcode,true);
		$criteria->compare('contact',$this->contact,true);
		$criteria->compare('web',$this->web,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('opening_hours',$this->opening_hours,true);
		$criteria->compare('html_content',$this->html_content,true);
		$criteria->compare('logo_path',$this->logo_path,true);
		$criteria->compare('slider_image_path',$this->slider_image_path,true);
		$criteria->compare('public',$this->public);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}