<?php

/**
 * This is the model class for table "ticket_vendor".
 *
 * The followings are the available columns in table 'ticket_vendor':
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property string $address
 * @property string $post_code
 * @property string $email
 * @property integer $notify_sale
 * @property string $telephone
 * @property string $vat_number
 * @property string $bank_account_name
 * @property string $bank_account_number
 * @property string $bank_sort_code
 *
 * The followings are the available model relations:
 * @property Event[] $events
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
		return 'ticket_vendor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, name', 'required'),
			array('uid, notify_sale', 'numerical', 'integerOnly'=>true),
			array('name, email, telephone, bank_account_name, bank_account_number', 'length', 'max'=>255),
			array('post_code, vat_number, bank_sort_code', 'length', 'max'=>45),
			array('address', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, name, address, post_code, email, notify_sale, telephone, vat_number, bank_account_name, bank_account_number, bank_sort_code', 'safe', 'on'=>'search'),
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
			'events' => array(self::HAS_MANY, 'Event', 'ticket_vendor_id'),
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
			'name' => 'Name',
			'address' => 'Address',
			'post_code' => 'Post Code',
			'email' => 'Email',
			'notify_sale' => 'Notify Sale',
			'telephone' => 'Telephone',
			'vat_number' => 'Vat Number',
			'bank_account_name' => 'Bank Account Name',
			'bank_account_number' => 'Bank Account Number',
			'bank_sort_code' => 'Bank Sort Code',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('notify_sale',$this->notify_sale,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('vat_number',$this->vat_number,true);
		$criteria->compare('bank_account_name',$this->bank_account_name,true);
		$criteria->compare('bank_account_number',$this->bank_account_number,true);
		$criteria->compare('bank_sort_code',$this->bank_sort_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
