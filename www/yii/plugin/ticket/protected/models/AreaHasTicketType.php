<?php

/**
 * This is the model class for table "ticket_area_has_ticket_ticket_type".
 *
 * The followings are the available columns in table 'ticket_area_has_ticket_ticket_type':
 * @property integer $ticket_area_id
 * @property integer $ticket_ticket_type_id
 */
class AreaHasTicketType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AreaHasTicketType the static model class
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
		return 'ticket_area_has_ticket_ticket_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ticket_area_id, ticket_ticket_type_id', 'required'),
			array('ticket_area_id, ticket_ticket_type_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ticket_area_id, ticket_ticket_type_id', 'safe', 'on'=>'search'),
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
			'ticket_area_id' => 'Ticket Area',
			'ticket_ticket_type_id' => 'Ticket Ticket Type',
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

		$criteria->compare('ticket_area_id',$this->ticket_area_id);
		$criteria->compare('ticket_ticket_type_id',$this->ticket_ticket_type_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}