<?php

/**
 * This is the model class for table "event_program".
 *
 * The followings are the available columns in table 'event_program':
 * @property integer $id
 * @property string $name
 * @property string $thumb_path
 * @property string $icon_path
 * @property integer $event_program_fields_id
 *
 * The followings are the available model relations:
 * @property Event[] $events
 * @property Member[] $eventMembers
 * @property ProgramFields $eventProgramFields
 */
class Program extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Program the static model class
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
		return 'event_program';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, event_program_fields_id', 'required'),
			array('event_program_fields_id', 'numerical', 'integerOnly'=>true),
			array('name, thumb_path, icon_path', 'length', 'max'=>255),

            array('thumb_path', 'file','on'=>'insert',
                'types'=> 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 20, // 1MB
                'tooLarge' => 'The file was bigger than 1MB. Please upload a smaller file.'
            ),
            array('thumb_path', 'file','on'=>'update',
                'types'=> 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 20, // 1MB
                'tooLarge' => 'The file was larger than 1MB. Please upload a smaller file.'
            ),
            array('thumb_path', 'unsafe'),
            array('thumb_path', 'length', 'max'=>255),

            array('icon_path', 'file','on'=>'insert',
                'types'=> 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 20, // 1MB
                'tooLarge' => 'The file was bigger than 1MB. Please upload a smaller file.'
            ),
            array('icon_path', 'file','on'=>'update',
                'types'=> 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 20, // 1MB
                'tooLarge' => 'The file was larger than 1MB. Please upload a smaller file.'
            ),
            array('icon_path', 'unsafe'),
            array('icon_path', 'length', 'max'=>255),


			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, thumb_path, icon_path, event_program_fields_id', 'safe', 'on'=>'search'),
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
			'events' => array(self::HAS_MANY, 'Event', 'program_id'),
			'eventMembers' => array(self::MANY_MANY, 'Member', 'event_member_has_event_program(event_program_id, event_member_id)'),
			'eventProgramFields' => array(self::BELONGS_TO, 'ProgramFields', 'event_program_fields_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'thumb_path' => 'Thumb Path',
			'icon_path' => 'Icon Path',
			'event_program_fields_id' => 'Event Program Fields',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('thumb_path',$this->thumb_path,true);
		$criteria->compare('icon_path',$this->icon_path,true);
		$criteria->compare('event_program_fields_id',$this->event_program_fields_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function searchAllProgramsImAdminFor()
	{
		$flt = "";
		// All programs I'm admin on
		$criteria=new CDbCriteria;
		$criteria->addCondition("event_member_id = " . Yii::app()->session['eid']);
		$criteria->addCondition("privilege_level >= " . 4);	//@@TODO Privilege level hardcoding. This is admin
		$memberHasPrograms = MemberHasProgram::model()->findAll($criteria);
		foreach ($memberHasPrograms as $memberHasProgram)
		{
			if ($flt != "")
				$flt .= " or ";
			$flt .= "id = " . $memberHasProgram->event_program_id;
		}
		// Now apply the conditions
		$criteria=new CDbCriteria;
		$criteria->addCondition($flt);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



}
