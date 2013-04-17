<?php

/**
 * This is the model class for table "booking_reservation_room".
 *
 * The followings are the available columns in table 'booking_reservation_room':
 * @property integer $id
 * @property integer $uid
 * @property string $ref
 * @property string $start_date
 * @property string $end_date
 * @property integer $num_adult
 * @property integer $num_child
 * @property string $total
 * @property integer $room_id
 *
 * The followings are the available model relations:
 * @property ReservationExtra[] $reservationExtras
 * @property ReservationExtra[] $reservationExtras1
 * @property Room $room
 */
class ReservationRoom extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ReservationRoom the static model class
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
		return 'booking_reservation_room';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, ref, start_date, end_date, room_id', 'required'),
			array('uid, num_adult, num_child, room_id', 'numerical', 'integerOnly'=>true),
			array('ref', 'length', 'max'=>255),
			array('total', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, ref, start_date, end_date, num_adult, num_child, total, room_id', 'safe', 'on'=>'search'),
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
			'reservationExtras' => array(self::HAS_MANY, 'ReservationExtra', 'booking_room_id'),
			'reservationExtras1' => array(self::HAS_MANY, 'ReservationExtra', 'booking_room_room_id'),
			'room' => array(self::BELONGS_TO, 'Room', 'room_id'),
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
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'num_adult' => 'Num Adult',
			'num_child' => 'Num Child',
			'total' => 'Total',
			'room_id' => 'Room',
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
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('num_adult',$this->num_adult);
		$criteria->compare('num_child',$this->num_child);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('room_id',$this->room_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}