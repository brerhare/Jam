<?php

/**
 * This is the model class for table "event_event_has_event_program".
 *
 * The followings are the available columns in table 'event_event_has_event_program':
 * @property integer $id
 * @property integer $program_id
 * @property integer $event_event_id
 * @property integer $approved
 */
class EventHasProgram extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EventHasProgram the static model class
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
		return 'event_event_has_event_program';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('program_id, event_event_id', 'required'),
			array('program_id, event_event_id, approved', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, program_id, event_event_id, approved', 'safe', 'on'=>'search'),
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
			'event_event' => array(self::BELONGS_TO, 'EventEvent', 'event_event_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'program_id' => 'Program',
			'event_event_id' => 'Event Event',
			'approved' => 'Approved',
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

		$criteria->compare('program_id',$this->program_id);

		$criteria->compare('event_event_id',$this->event_event_id);

		$criteria->compare('approved',$this->approved);

		return new CActiveDataProvider('EventHasProgram', array(
			'criteria'=>$criteria,
		));
	}
}
