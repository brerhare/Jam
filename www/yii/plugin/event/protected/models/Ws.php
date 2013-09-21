<?php

/**
 * This is the model class for table "event_ws".
 *
 * The followings are the available columns in table 'event_ws':
 * @property integer $id
 * @property integer $event_id
 * @property string $os_grid_ref
 * @property string $grade
 * @property integer $booking_essential
 * @property integer $min_age
 * @property integer $max_ageI
 * @property string $child_ages_restrictions
 * @property string $additional_venue_info
 * @property string $full_price_notes
 * @property string $short_description
 * @property integer $wheelchair_accessible
 */
class Ws extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Ws the static model class
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
		return 'event_ws';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, os_grid_ref, grade', 'required'),
			array('event_id, booking_essential, min_age, max_ageI, wheelchair_accessible', 'numerical', 'integerOnly'=>true),
			array('os_grid_ref, grade, child_ages_restrictions, additional_venue_info, full_price_notes, short_description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, os_grid_ref, grade, booking_essential, min_age, max_ageI, child_ages_restrictions, additional_venue_info, full_price_notes, short_description, wheelchair_accessible', 'safe', 'on'=>'search'),
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
			'event_id' => 'Event',
			'os_grid_ref' => 'Os Grid Ref',
			'grade' => 'Grade',
			'booking_essential' => 'Booking Essential',
			'min_age' => 'Min Age',
			'max_ageI' => 'Max Age I',
			'child_ages_restrictions' => 'Child Ages Restrictions',
			'additional_venue_info' => 'Additional Venue Info',
			'full_price_notes' => 'Full Price Notes',
			'short_description' => 'Short Description',
			'wheelchair_accessible' => 'Wheelchair Accessible',
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
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('os_grid_ref',$this->os_grid_ref,true);
		$criteria->compare('grade',$this->grade,true);
		$criteria->compare('booking_essential',$this->booking_essential);
		$criteria->compare('min_age',$this->min_age);
		$criteria->compare('max_ageI',$this->max_ageI);
		$criteria->compare('child_ages_restrictions',$this->child_ages_restrictions,true);
		$criteria->compare('additional_venue_info',$this->additional_venue_info,true);
		$criteria->compare('full_price_notes',$this->full_price_notes,true);
		$criteria->compare('short_description',$this->short_description,true);
		$criteria->compare('wheelchair_accessible',$this->wheelchair_accessible);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}