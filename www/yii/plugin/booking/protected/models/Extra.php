<?php

/**
 * This is the model class for table "booking_extra".
 *
 * The followings are the available columns in table 'booking_extra':
 * @property integer $id
 * @property integer $uid
 * @property string $description
 * @property string $daily_rate
 * @property string $once_rate
 *
 * The followings are the available model relations:
 * @property Room[] $bookingRooms
 */
class Extra extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Extra the static model class
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
		return 'booking_extra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, description', 'required'),
			array('uid', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			array('daily_rate, once_rate', 'length', 'max'=>10),
			// @@EG money regexp in model rules. This works nicely for all known-to-me patterns
			array('daily_rate, once_rate', 'match', 'pattern'=>'/([0-9]+(\.[0-9][0-9]?)?)/'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, description, daily_rate, once_rate', 'safe', 'on'=>'search'),
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
			'bookingRooms' => array(self::MANY_MANY, 'Room', 'booking_room_has_extra(extra_id, room_id)'),
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
			'daily_rate' => 'Daily Rate',
			'once_rate' => 'Once Rate',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('daily_rate',$this->daily_rate,true);
		$criteria->compare('once_rate',$this->once_rate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
