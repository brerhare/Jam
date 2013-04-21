<?php

/**
 * This is the model class for table "ticket_ticket_type".
 *
 * The followings are the available columns in table 'ticket_ticket_type':
 * @property integer $id
 * @property integer $uid
 * @property string $description
 * @property string $price
 * @property integer $places_per_ticket
 * @property integer $max_tickets_per_order
 * @property integer $ticket_event_id
 *
 * The followings are the available model relations:
 * @property Area[] $ticketAreas
 * @property Event $ticketEvent
 */
class TicketType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TicketType the static model class
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
		return 'ticket_ticket_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, description, ticket_event_id', 'required'),
			array('uid, places_per_ticket, max_tickets_per_order, ticket_event_id', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			array('price', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, description, price, places_per_ticket, max_tickets_per_order, ticket_event_id', 'safe', 'on'=>'search'),
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
			'ticketAreas' => array(self::MANY_MANY, 'Area', 'ticket_area_has_ticket_ticket_type(ticket_ticket_type_id, ticket_area_id)'),
			'ticketEvent' => array(self::BELONGS_TO, 'Event', 'ticket_event_id'),
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
			'description' => 'Description',
			'price' => 'Price',
			'places_per_ticket' => 'Places Per Ticket',
			'max_tickets_per_order' => 'Max Tickets Per Order',
			'ticket_event_id' => 'Ticket Event',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('places_per_ticket',$this->places_per_ticket);
		$criteria->compare('max_tickets_per_order',$this->max_tickets_per_order);
		//$criteria->compare('ticket_event_id',$this->ticket_event_id);
		$criteria->addCondition("ticket_event_id = " . Yii::app()->session['event_id']);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}