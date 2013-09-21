<?php

/**
 * This is the model class for table "event_event".
 *
 * The followings are the available columns in table 'event_event':
 * @property integer $id
 * @property string $title
 * @property string $start
 * @property string $end
 * @property string $address
 * @property string $post_code
 * @property string $web
 * @property string $contact
 * @property string $description
 * @property string $thumb_path
 * @property integer $approved
 * @property integer $member_id
 * @property integer $program_id
 *
 * The followings are the available model relations:
 * @property Program $program
 * @property Member $member
 * @property Facility[] $eventFacilities
 * @property Format[] $eventFormats
 * @property Interest[] $eventInterests
 * @property PriceBand[] $eventPriceBands
 */
class Event extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Event the static model class
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
		return 'event_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, start, description, member_id, program_id', 'required'),
			array('approved, member_id, program_id', 'numerical', 'integerOnly'=>true),
			array('title, post_code, web, thumb_path', 'length', 'max'=>255),
			array('end, address, contact', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, start, end, address, post_code, web, contact, description, thumb_path, approved, member_id, program_id', 'safe', 'on'=>'search'),
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
			'program' => array(self::BELONGS_TO, 'Program', 'program_id'),
			'member' => array(self::BELONGS_TO, 'Member', 'member_id'),
			'eventFacilities' => array(self::MANY_MANY, 'Facility', 'event_event_has_event_facility(event_event_id, event_facility_id)'),
			'eventFormats' => array(self::MANY_MANY, 'Format', 'event_event_has_event_format(event_event_id, event_format_id)'),
			'eventInterests' => array(self::MANY_MANY, 'Interest', 'event_event_has_event_interest(event_event_id, event_interest_id)'),
			'eventPriceBands' => array(self::MANY_MANY, 'PriceBand', 'event_event_has_event_price_band(event_event_id, event_price_band_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'start' => 'Start',
			'end' => 'End',
			'address' => 'Address',
			'post_code' => 'Post Code',
			'web' => 'Web',
			'contact' => 'Contact',
			'description' => 'Description',
			'thumb_path' => 'Thumb Path',
			'approved' => 'Approved',
			'member_id' => 'Member',
			'program_id' => 'Program',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('web',$this->web,true);
		$criteria->compare('contact',$this->contact,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('thumb_path',$this->thumb_path,true);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('program_id',$this->program_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}