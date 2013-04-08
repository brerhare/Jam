<?php

/**
 * This is the model class for table "booking_room_has_occupancy_type".
 *
 * The followings are the available columns in table 'booking_room_has_occupancy_type':
 * @property integer $room_id
 * @property integer $occupancy_type_id
 * @property integer $uid
 * @property string $adult_rate
 * @property string $child_rate
 * @property string $single_rate
 * @property string $double_rate
 * @property string $any_rate
 */
class RoomHasOccupancyType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RoomHasOccupancyType the static model class
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
		return 'booking_room_has_occupancy_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('room_id, occupancy_type_id, uid', 'required'),
			array('room_id, occupancy_type_id, uid', 'numerical', 'integerOnly'=>true),
			array('adult_rate, child_rate, single_rate, double_rate, any_rate', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('room_id, occupancy_type_id, uid, adult_rate, child_rate, single_rate, double_rate, any_rate', 'safe', 'on'=>'search'),
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
			'room_id' => 'Room',
			'occupancy_type_id' => 'Occupancy Type',
			'uid' => 'Uid',
			'adult_rate' => 'Adult Rate',
			'child_rate' => 'Child Rate',
			'single_rate' => 'Single Rate',
			'double_rate' => 'Double Rate',
			'any_rate' => 'Any Rate',
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

		$criteria->compare('room_id',$this->room_id);
		$criteria->compare('occupancy_type_id',$this->occupancy_type_id);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('adult_rate',$this->adult_rate,true);
		$criteria->compare('child_rate',$this->child_rate,true);
		$criteria->compare('single_rate',$this->single_rate,true);
		$criteria->compare('double_rate',$this->double_rate,true);
		$criteria->compare('any_rate',$this->any_rate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}