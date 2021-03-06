<?php

/**
 * This is the model class for table "size".
 *
 * The followings are the available columns in table 'size':
 * @property integer $id
 * @property string $text
 * @property integer $is_a_default
 * @property integer $category_id
 *
 * The followings are the available model relations:
 * @property Category $category
 */
class Size extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Size the static model class
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
		return 'size';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id', 'required'),
			array('is_a_default, category_id', 'numerical', 'integerOnly'=>true),
			array('text', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, text, is_a_default, category_id', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'text' => 'Text',
			'is_a_default' => 'Is A Default',
			'category_id' => 'Category',
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
		$criteria->compare('text',$this->text,true);
		$criteria->compare('is_a_default',$this->is_a_default);
		// $criteria->compare('category_id',$this->category_id);        kim modified as below, to use the session var category_id
		$criteria->compare('category_id',Yii::app()->session['category_id']);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}