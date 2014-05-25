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
 * @property integer $ticket_event_id
 * @property integer $member_id
 * @property integer $program_id
 * @property integer $event_price_band_id
 *
 * The followings are the available model relations:
 * @property PriceBand $eventPriceBand
 * @property Member $member
 * @property Program $program
 * @property Facility[] $eventFacilities
 * @property Format[] $eventFormats
 * @property Interest[] $eventInterests
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
			array('title, start, description, member_id, program_id, event_price_band_id, address, post_code', 'required'),
			array('approved, ticket_event_id, member_id, program_id, event_price_band_id', 'numerical', 'integerOnly'=>true),
			array('title, post_code, web, thumb_path', 'length', 'max'=>255),
			array('end, address, contact', 'safe'),

            array('thumb_path','unsafe'),
            array('thumb_path', 'file', 'types'=>'jpg, jpeg, gif, png','safe'=>true, 'maxSize'=>10000*1024, 'allowEmpty'=>true, 'tooLarge'=>'{attribute} is too large to be uploaded. Maximum size is 10MB.'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, start, end, address, post_code, web, contact, description, thumb_path, approved, ticket_event_id, member_id, program_id, event_price_band_id', 'safe', 'on'=>'search'),
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
			'eventPriceBand' => array(self::BELONGS_TO, 'PriceBand', 'event_price_band_id'),
			'member' => array(self::BELONGS_TO, 'Member', 'member_id'),
			'program' => array(self::BELONGS_TO, 'Program', 'program_id'),
			'eventFacilities' => array(self::MANY_MANY, 'Facility', 'event_event_has_event_facility(event_event_id, event_facility_id)'),
			'eventFormats' => array(self::MANY_MANY, 'Format', 'event_event_has_event_format(event_event_id, event_format_id)'),
			'eventInterests' => array(self::MANY_MANY, 'Interest', 'event_event_has_event_interest(event_event_id, event_interest_id)'),
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
			'address' => 'Venue',
			'post_code' => 'Post Code',
			'web' => 'Web',
			'contact' => 'Contact',
			'description' => 'Description',
			'thumb_path' => 'Thumb (140w x 115h)',
			'approved' => 'Approved',
			'ticket_event_id' => 'Create Ticket Event?',
			'member_id' => 'Member',
			'program_id' => 'Program',
			'event_price_band_id' => 'Event Price Band',
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
		$criteria->compare('ticket_event_id',$this->ticket_event_id);
		//$criteria->compare('member_id',$this->member_id);
		$criteria->addCondition("member_id = " . Yii::app()->session['eid']);
		$criteria->compare('program_id',$this->program_id);
		$criteria->compare('event_price_band_id',$this->event_price_band_id);

//@@ EG: Ordering model records on the admin crud
        $criteria->order = "title ASC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,

            // @@EG: Change cgridview pagination
            'pagination' => array(
                'pageSize' => 50,
            ),

		));
	}

	public function searchAllProgramsImAdminOrModFor()
	{
		// Add all my own events
		$flt = "member_id = " . Yii::app()->session['eid'];

		// Also add all events in all programs I'm admin on
		$criteria=new CDbCriteria;
	//$criteria->addCondition("lock_program_id = " . Yii::app()->session['pid']);
/*
		$criteria->compare('id',$this->id);
		//$criteria->compare('title',$this->title,true);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('web',$this->web,true);
		$criteria->compare('contact',$this->contact,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('thumb_path',$this->thumb_path,true);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('ticket_event_id',$this->ticket_event_id);
		//$criteria->compare('member_id',$this->member_id);
*/
		$criteria->addCondition("event_member_id = " . Yii::app()->session['eid']);
		$criteria->addCondition("privilege_level = " . 4);	//@@TODO Privilege level hardcoding
		$memberHasPrograms = MemberHasProgram::model()->findAll($criteria);
		foreach ($memberHasPrograms as $memberHasProgram)
		{
			$flt .= " or program_id = " . $memberHasProgram->event_program_id;
		}
		// Now apply the conditions
		$criteria=new CDbCriteria;
		$criteria->addCondition($flt);

//@@ EG: Ordering model records on the admin crud
        $criteria->order = "title ASC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,

            // @@EG: Change cgridview pagination
            'pagination' => array(
                'pageSize' => 50,
            ),

		));
	}

// @@EG CJuiDatePicker. See also the form where the widget is applied

    protected function afterFind(){
        parent::afterFind();
        $this->start=date('d-m-Y H:i', strtotime(str_replace("-", "", $this->start)));
        $this->end=date('d-m-Y H:i', strtotime(str_replace("-", "", $this->end)));
    }

    protected function beforeSave(){
        if(parent::beforeSave()){
            if (empty($this->end))
                $this->end = $this->start;
            $this->start=date('Y-m-d H:i:s', strtotime(str_replace(",", "", $this->start)));
            $this->end=date('Y-m-d H:i:s', strtotime(str_replace(",", "", $this->end)));
            return TRUE;
        }
       else
            return false;
    }

}
