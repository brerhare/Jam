<?php

/**
 * This is the model class for table "admin_session".
 *
 * The followings are the available columns in table 'admin_session':
 * @property integer $id
 * @property string $owner_string
 * @property string $key_string
 * @property string $value_string
 * @property string $created
 * @property string $modified
 */
class Session extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Session the static model class
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
		return 'admin_session';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_string, key_string, created, modified', 'required'),
			array('owner_string, key_string', 'length', 'max'=>255),
			array('value_string', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, owner_string, key_string, value_string, created, modified', 'safe', 'on'=>'search'),
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
			'owner_string' => 'Owner String',
			'key_string' => 'Key',
			'value_string' => 'Value',
			'created' => 'Created',
			'modified' => 'Modified',
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
		$criteria->compare('owner_string',$this->owner_string,true);
		$criteria->compare('key_string',$this->key_string,true);
		$criteria->compare('value_string',$this->value_string,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
