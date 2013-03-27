<?php

/**
 * This is the model class for table "admin_plugin".
 *
 * The followings are the available columns in table 'admin_plugin':
 * @property integer $id
 * @property string $description
 * @property string $container_url
 * @property integer $container_width
 * @property integer $container_height
 *
 * The followings are the available model relations:
 * @property Image[] $images
 * @property User[] $adminUsers
 */
class Plugin extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Plugin the static model class
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
		return 'admin_plugin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description', 'required'),
			array('container_width, container_height', 'numerical', 'integerOnly'=>true),
			array('description, container_url', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, description, container_url, container_width, container_height', 'safe', 'on'=>'search'),
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
			'images' => array(self::HAS_MANY, 'Image', 'plugin_id'),
			'adminUsers' => array(self::MANY_MANY, 'User', 'admin_user_has_plugin(plugin_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'description' => 'Description',
			'container_url' => 'Container Url',
			'container_width' => 'Container Width',
			'container_height' => 'Container Height',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('container_url',$this->container_url,true);
		$criteria->compare('container_width',$this->container_width);
		$criteria->compare('container_height',$this->container_height);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}