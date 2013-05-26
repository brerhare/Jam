<?php

/**
 * This is the model class for table "booking_coupon".
 *
 * The followings are the available columns in table 'booking_coupon':
 * @property integer $id
 * @property integer $uid
 * @property string $code
 * @property string $description
 * @property integer $type
 * @property string $value
 */
class Coupon extends CActiveRecord
{
	// A getter for valid values for the 'type' field
	public function getTypes()
	{
		return array(
		0 => 'Amount',
		1 => 'Percentage',
		2 => 'Input Amount',
		);
	}

    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Coupon the static model class
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
		return 'booking_coupon';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, code, type', 'required'),
			array('uid, type', 'numerical', 'integerOnly'=>true),
			array('code, description', 'length', 'max'=>255),
			array('value', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, code, description, type, value', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'description' => 'Description',
			'type' => 'Type',
			'value' => 'Value',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}