<?php

/**
 * This is the model class for table "ticket_area".
 *
 * The followings are the available columns in table 'ticket_area':
 * @property integer $id
 * @property integer $uid
 * @property string $description
 * @property string $max_places
 * @property integer $ticket_event_id
 *
 * The followings are the available model relations:
 * @property Event $ticketEvent
 * @property TicketType[] $ticketTicketTypes
 */
class Area extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Area the static model class
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
		return 'ticket_area';
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
			array('uid, ticket_event_id', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			array('max_places', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, description, max_places, ticket_event_id', 'safe', 'on'=>'search'),
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
			'ticketEvent' => array(self::BELONGS_TO, 'Event', 'ticket_event_id'),
			'ticketTicketTypes' => array(self::MANY_MANY, 'TicketType', 'ticket_area_has_ticket_ticket_type(ticket_area_id, ticket_ticket_type_id)'),
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
			'max_places' => 'Max Places',
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
		$criteria->compare('max_places',$this->max_places,true);
		//$criteria->compare('ticket_event_id',$this->ticket_event_id);
		$criteria->addCondition("ticket_event_id = " . Yii::app()->session['event_id']);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
