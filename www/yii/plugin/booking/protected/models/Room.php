<?php

/**
 * This is the model class for table "room".
 *
 * The followings are the available columns in table 'room':
 * @property integer $id
 * @property integer $kid
 * @property string $title
 * @property string $description
 * @property integer $qty
 * @property integer $max_adult
 * @property integer $max_child
 * @property integer $max_total
 *
 * The followings are the available model relations:
 * @property BookingCalendar[] $bookingCalendars
 * @property BookingRoom[] $bookingRooms
 * @property Extra[] $extras
 * @property Facility[] $facilities
 * @property OccupancyType[] $occupancyTypes
 */
class Room extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Room the static model class
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
		return 'room';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kid, title, description, qty', 'required'),
			array('kid, qty, max_adult, max_child, max_total', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, kid, title, description, qty, max_adult, max_child, max_total', 'safe', 'on'=>'search'),
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
			'bookingCalendars' => array(self::HAS_MANY, 'BookingCalendar', 'room_id'),
			'bookingRooms' => array(self::HAS_MANY, 'BookingRoom', 'room_id'),
			'extras' => array(self::MANY_MANY, 'Extra', 'room_has_extra(room_id, extra_id)'),
			'facilities' => array(self::MANY_MANY, 'Facility', 'room_has_facility(room_id, facility_id)'),
			'occupancyTypes' => array(self::MANY_MANY, 'OccupancyType', 'room_has_occupancy_type(room_id, occupancy_type_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kid' => 'Kid',
			'title' => 'Title',
			'description' => 'Description',
			'qty' => 'Qty',
			'max_adult' => 'Max Adult',
			'max_child' => 'Max Child',
			'max_total' => 'Max Total',
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
		$criteria->compare('kid',$this->kid);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('max_adult',$this->max_adult);
		$criteria->compare('max_child',$this->max_child);
		$criteria->compare('max_total',$this->max_total);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}