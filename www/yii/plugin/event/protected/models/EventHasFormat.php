<?php

/**
 * This is the model class for table "event_event_has_event_format".
 *
 * The followings are the available columns in table 'event_event_has_event_format':
 * @property integer $event_event_id
 * @property integer $event_format_id
 */
class EventHasFormat extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EventHasFormat the static model class
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
		return 'event_event_has_event_format';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_event_id, event_format_id', 'required'),
			array('event_event_id, event_format_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('event_event_id, event_format_id', 'safe', 'on'=>'search'),
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
			'event_event_id' => 'Event Event',
			'event_format_id' => 'Event Format',
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

		$criteria->compare('event_event_id',$this->event_event_id);
		$criteria->compare('event_format_id',$this->event_format_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}