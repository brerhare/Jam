<?php

/**
 * This is the model class for table "vendor".
 *
 * The followings are the available columns in table 'vendor':
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $post_code
 * @property string $email
 * @property string $telephone
 * @property string $vat_number
 * @property string $bank_name
 * @property string $bank_sort_code
 * @property integer $bank_account_number
 * @property string $venue_address
 * @property string $venue_post_code
 */
class Vendor extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Vendor the static model class
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
		return 'vendor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, address, post_code', 'required'),
			array('bank_account_number', 'numerical', 'integerOnly'=>true),
			array('name, email, bank_name', 'length', 'max'=>128),
			array('post_code, venue_post_code', 'length', 'max'=>10),
			array('telephone', 'length', 'max'=>25),
			array('vat_number', 'length', 'max'=>20),
			array('bank_sort_code', 'length', 'max'=>6),
			array('venue_address', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, address, post_code, email, telephone, vat_number, bank_name, bank_sort_code, bank_account_number, venue_address, venue_post_code', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'address' => 'Address',
			'post_code' => 'Post Code',
			'email' => 'Email',
			'telephone' => 'Telephone',
			'vat_number' => 'Vat Number',
			'bank_name' => 'Bank Name',
			'bank_sort_code' => 'Bank Sort Code',
			'bank_account_number' => 'Bank Account Number',
			'venue_address' => 'Venue Address',
			'venue_post_code' => 'Venue Post Code',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('vat_number',$this->vat_number,true);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('bank_sort_code',$this->bank_sort_code,true);
		$criteria->compare('bank_account_number',$this->bank_account_number);
		$criteria->compare('venue_address',$this->venue_address,true);
		$criteria->compare('venue_post_code',$this->venue_post_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}