<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property integer $id
 * @property string $title
 * @property string $start_date
 * @property string $address
 * @property string $post_code
 * @property string $web_link
 * @property integer $max_tickets
 * @property string $ticket_text
 * @property string $ticket_logo_path
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
		return 'event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, start_date, address, post_code', 'required'),
			array('max_tickets', 'numerical', 'integerOnly'=>true),
			array('title, start_date, web_link, ticket_logo_path', 'length', 'max'=>128),
			array('post_code', 'length', 'max'=>10),
			array('ticket_text', 'safe'),
/*
			array('ticket_logo_path', 'file','on'=>'insert',
				'types'=> 'jpg, jpeg, gif, png',
				'maxSize' => 1024 * 1024 * 10, // 10MB
				'tooLarge' => 'The file was bigger than 10MB. Please upload a smaller file.'
			),
			array('ticket_logo_path', 'file','on'=>'update',
				'types'=> 'jpg, jpeg, gif, png',
				'allowEmpty' => true,
				'maxSize' => 1024 * 1024 * 10, // 10MB
				'tooLarge' => 'The file was larger than 10MB. Please upload a smaller file.'
			),
*/
			array('ticket_logo_path', 'unsafe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, start_date, address, post_code, web_link, max_tickets, ticket_text, ticket_logo_path', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'start_date' => 'Start Date',
			'address' => 'Address',
			'post_code' => 'Post Code',
			'web_link' => 'Web Link',
			'max_tickets' => 'Max Tickets',
			'ticket_text' => 'Ticket Text',
			'ticket_logo_path' => 'Ticket Logo Path',
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
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('web_link',$this->web_link,true);
		$criteria->compare('max_tickets',$this->max_tickets);
		$criteria->compare('ticket_text',$this->ticket_text,true);
		$criteria->compare('ticket_logo_path',$this->ticket_logo_path,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
