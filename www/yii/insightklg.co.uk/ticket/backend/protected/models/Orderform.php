<?php

/**
 * This is the model class for table "orderform".
 *
 * The followings are the available columns in table 'orderform':
 * @property string $hash
 * @property string $email
 * @property integer $friday
 * @property integer $saturday
 * @property integer $weekend
 * @property integer $vip
 * @property string $telephone
 */
class Orderform extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Orderform the static model class
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
		return 'orderform';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hash, email, friday, saturday, weekend, vip, telephone', 'required'),
			array('friday, saturday, weekend, vip', 'numerical', 'integerOnly'=>true),
			array('hash, email, telephone', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('hash, email, friday, saturday, weekend, vip, telephone', 'safe', 'on'=>'search'),
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
			'hash' => 'Hash',
			'email' => 'Email',
			'friday' => 'Friday',
			'saturday' => 'Saturday',
			'weekend' => 'Weekend',
			'vip' => 'Weekend VIP',
			'telephone' => 'Telephone',
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

		$criteria->compare('hash',$this->hash,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('friday',$this->friday);
		$criteria->compare('saturday',$this->saturday);
		$criteria->compare('weekend',$this->weekend);
		$criteria->compare('vip',$this->vip);
		$criteria->compare('telephone',$this->telephone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
