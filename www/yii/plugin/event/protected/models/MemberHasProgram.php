<?php

/**
 * This is the model class for table "event_member_has_event_program".
 *
 * The followings are the available columns in table 'event_member_has_event_program':
 * @property integer $event_member_id
 * @property integer $event_program_id
 * @property integer $privilege_level
 */
class MemberHasProgram extends CActiveRecord
{
		public $PRIVILEGE_ADMIN = 4;
		public $PRIVILEGE_MOD = 3;
		public $PRIVILEGE_TRUSTED = 2;
		public $PRIVILEGE_MEMBER = 1;
		public $PRIVILEGE_NONE = 0;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MemberHasProgram the static model class
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
		return 'event_member_has_event_program';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_member_id, event_program_id', 'required'),
			array('event_member_id, event_program_id, privilege_level', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('event_member_id, event_program_id, privilege_level', 'safe', 'on'=>'search'),
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
			'event_member_id' => 'Event Member',
			'event_program_id' => 'Event Program',
			'privilege_level' => 'Privilege Level',
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

		$criteria->compare('event_member_id',$this->event_member_id);
		$criteria->compare('event_program_id',$this->event_program_id);
		$criteria->compare('privilege_level',$this->privilege_level);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}