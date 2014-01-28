<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property integer $id
 * @property integer $arena
 * @property string $description
 * @property string $start
 * @property string $end
 * @property integer $share
 * @property integer $confirmed
 * @property integer $password
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
			array('arena, description, password', 'required'),
			array('arena, share, confirmed, password', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			array('start, end', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, arena, description, start, end, share, confirmed, password', 'safe', 'on'=>'search'),
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
			'arena' => 'Arena',
			'description' => 'Description',
			'start' => 'Start',
			'end' => 'End',
			'share' => 'Share',
			'confirmed' => 'Confirmed',
			'password' => 'Password',
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
		$criteria->compare('arena',$this->arena);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('share',$this->share);
		$criteria->compare('confirmed',$this->confirmed);
		$criteria->compare('password',$this->password);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}