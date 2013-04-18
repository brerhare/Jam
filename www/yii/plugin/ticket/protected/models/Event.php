<?php

/**
 * This is the model class for table "ticket_event".
 *
 * The followings are the available columns in table 'ticket_event':
 * @property integer $id
 * @property integer $uid
 * @property string $title
 * @property string $date
 * @property string $address
 * @property string $post_code
 * @property string $text
 * @property integer $max_tickets
 * @property integer $active
 * @property integer $ticket_vendor_id
 *
 * The followings are the available model relations:
 * @property Vendor $ticketVendor
 * @property TicketType[] $ticketTypes
 * @property TicketType[] $ticketTypes1
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
			array('uid, title, date, address, post_code, max_tickets, active', 'required'),
			array('uid, max_tickets, active, ticket_vendor_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('date, post_code', 'length', 'max'=>45),
			array('text', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, title, date, address, post_code, text, max_tickets, active, ticket_vendor_id', 'safe', 'on'=>'search'),
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
			'ticketVendor' => array(self::BELONGS_TO, 'Vendor', 'ticket_vendor_id'),
			'ticketTypes' => array(self::HAS_MANY, 'TicketType', 'ticket_event_id'),
			'ticketTypes1' => array(self::HAS_MANY, 'TicketType', 'ticket_event_ticket_vendor_id'),
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
			'address' => 'Address',
			'post_code' => 'Post Code',
			'text' => 'Text',
			'max_tickets' => 'Max Tickets',
			'active' => 'Active',
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
		$criteria->compare('uid',$this->uid);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('max_tickets',$this->max_tickets);
		$criteria->compare('active',$this->active);
		$criteria->compare('ticket_vendor_id',$this->ticket_vendor_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}