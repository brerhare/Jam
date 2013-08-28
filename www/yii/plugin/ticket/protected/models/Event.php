<?php

/**
 * This is the model class for table "ticket_event".
 *
 * The followings are the available columns in table 'ticket_event':
 * @property integer $id
 * @property integer $uid
 * @property string $title
 * @property string $date
 * @property string $time
 * @property string $address
 * @property string $post_code
 * @property string $banner_text
 * @property string $ticket_logo_path
 * @property string $ticket_text
 * @property string $ticket_terms
 * @property integer $active
 * @property string $active_start_date
 * @property string $active_start_time
 * @property string $active_end_date
 * @property string $active_end_time
 * @property integer $optional_start_ticket_number
 * @property integer $optional_next_ticket_number
 * @property integer $ticket_vendor_id
 *
 * The followings are the available model relations:
 * @property Area[] $areas
 * @property Vendor $ticketVendor
 * @property TicketType[] $ticketTypes
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
		return 'ticket_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, title, date, address, post_code, active, ticket_vendor_id', 'required'),
			array('uid, active, optional_start_ticket_number, optional_next_ticket_number, ticket_vendor_id', 'numerical', 'integerOnly'=>true),
			array('title, ticket_logo_path', 'length', 'max'=>255),
			array('date, time, post_code', 'length', 'max'=>45),
			array('banner_text, ticket_text, ticket_terms, active_start_date, active_start_time, active_end_date, active_end_time', 'safe'),

			// @@EG: Image upload. Next two lines. See also controller create/update/delete and remember to set enctype in the view!!!!!
			array('ticket_logo_path','unsafe'),
			array('ticket_logo_path', 'file', 'types'=>'jpg, jpeg, gif, png','safe'=>true, 'maxSize'=>1000*1024, 'allowEmpty'=>true, 'tooLarge'=>'{attribute} is too large to be uploaded. Maximum size is 1MB.'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, title, date, time. address, post_code, banner_text, ticket_logo_path, ticket_text, ticket_terms, active, active_start_date, active_start_time, active_end_date, active_end_time, optional_start_ticket_number, optional_next_ticket_number, ticket_vendor_id', 'safe', 'on'=>'search'),
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
			'areas' => array(self::HAS_MANY, 'Area', 'ticket_event_id'),
			'ticketVendor' => array(self::BELONGS_TO, 'Vendor', 'ticket_vendor_id'),
			'ticketTypes' => array(self::HAS_MANY, 'TicketType', 'ticket_event_id'),
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
			'title' => 'Title',
			'date' => 'Date',
			'time' => 'Time',
			'address' => 'Address',
			'post_code' => 'Post Code',
			'banner_text' => 'Online Description',
			'ticket_logo_path' => 'Ticket Logo Path',
			'ticket_text' => 'Ticket Text',
			'ticket_terms' => 'Ticket Terms',
			'active' => 'Active',
			'active_start_date' => 'Active Start Date',
			'active_start_time' => 'Active Start Time',
			'active_end_date' => 'Active End Date',
			'active_end_time' => 'Active End Time',
			'optional_start_ticket_number' => 'Optional Start Ticket Number',
			'optional_next_ticket_number' => 'Optional Next Ticket Number',
			'ticket_vendor_id' => 'Ticket Vendor',
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
		//$criteria->compare('uid',$this->uid);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('banner_text',$this->banner_text,true);
		$criteria->compare('ticket_logo_path',$this->ticket_logo_path,true);
		$criteria->compare('ticket_text',$this->ticket_text,true);
		$criteria->compare('ticket_terms',$this->ticket_terms,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('active_start_date',$this->active_start_date,true);
		$criteria->compare('active_start_time',$this->active_start_time,true);
		$criteria->compare('active_end_date',$this->active_end_date,true);
		$criteria->compare('active_end_time',$this->active_end_time,true);
		$criteria->compare('optional_start_ticket_number',$this->optional_start_ticket_number);
		$criteria->compare('optional_next_ticket_number',$this->optional_next_ticket_number);
		$criteria->compare('ticket_vendor_id',$this->ticket_vendor_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
