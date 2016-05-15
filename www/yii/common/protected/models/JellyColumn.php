<?php

/**
 * This is the model class for table "jelly_slider_html".
 *
 * The followings are the available columns in table 'jelly_column':
 * @property integer $id
 * @property integer $column_name
 * @property integer $sequence
 * @property string $title
 * @property string $content
 */
class JellyColumn extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JellySliderHtml the static model class
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
		return 'jelly_column';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, column_name', 'required'),
			array('sequence', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('column_name, content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, column_name, sequence, title, content', 'safe', 'on'=>'search'),
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
			'column_name' => 'Content ID',
			'sequence' => 'Sequence',
			'title' => 'Description',
			'content' => 'Content',
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
		$criteria->compare('column_name',$this->column_name);
		$criteria->compare('sequence',$this->sequence);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);

        $criteria->order = "column_name ASC, sequence ASC, title ASC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
