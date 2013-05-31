<?php

/**
 * This is the model class for table "booking_param".
 *
 * The followings are the available columns in table 'booking_param':
 * @property integer $id
 * @property integer $uid
 * @property string $cc_email_address
 * @property string $deposit_percent
 */
class Param extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Param the static model class
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
		return 'booking_param';
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
			array('cc_email_address', 'length', 'max'=>255),
			array('deposit_percent', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, cc_email_address, deposit_percent', 'safe', 'on'=>'search'),
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
			'cc_email_address' => 'Booking Bcc Email',
			'deposit_percent' => 'Deposit %',
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
		$criteria->compare('cc_email_address',$this->cc_email_address,true);
		$criteria->compare('deposit_percent',$this->deposit_percent,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}