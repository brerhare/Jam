<?php

/**
 * This is the model class for table "booking_reservation_extra".
 *
 * The followings are the available columns in table 'booking_reservation_extra':
 * @property integer $id
 * @property integer $uid
 * @property string $ref
 * @property integer $booking_room_id
 * @property integer $booking_room_room_id
 *
 * The followings are the available model relations:
 * @property ReservationRoom $bookingRoom
 * @property ReservationRoom $bookingRoomRoom
 */
class ReservationExtra extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ReservationExtra the static model class
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
		return 'booking_reservation_extra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, ref, booking_room_id, booking_room_room_id', 'required'),
			array('uid, booking_room_id, booking_room_room_id', 'numerical', 'integerOnly'=>true),
			array('ref', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, ref, booking_room_id, booking_room_room_id', 'safe', 'on'=>'search'),
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
			'bookingRoom' => array(self::BELONGS_TO, 'ReservationRoom', 'booking_room_id'),
			'bookingRoomRoom' => array(self::BELONGS_TO, 'ReservationRoom', 'booking_room_room_id'),
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
			'booking_room_id' => 'Booking Room',
			'booking_room_room_id' => 'Booking Room Room',
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
		$criteria->compare('ref',$this->ref,true);
		$criteria->compare('booking_room_id',$this->booking_room_id);
		$criteria->compare('booking_room_room_id',$this->booking_room_room_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}