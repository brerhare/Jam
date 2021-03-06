<?php

/**
 * This is the model class for table "accordion_block".
 *
 * The followings are the available columns in table 'accordion_block':
 * @property integer $id
 * @property integer $sequence
 * @property string $title
 * @property string $url
 * @property string $content
 * @property string $image
 */
class AccordionBlock extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AccordionBlock the static model class
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
		return 'accordion_block';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, url', 'required'),
			array('sequence', 'numerical', 'integerOnly'=>true),
			array('title, url, image', 'length', 'max'=>255),
			array('content', 'safe'),

            array('image','unsafe'),
            array('image', 'file', 'types'=>'jpg, jpeg, gif, png','safe'=>true, 'maxSize'=>10000*1024, 'allowEmpty'=>true, 'tooLarge'=>'{attribute} is too large to be uploaded. Maximum size is 10MB.'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sequence, title, url, content, image', 'safe', 'on'=>'search'),
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
			'sequence' => 'Sequence',
			'title' => 'Title',
			'url' => 'Target Url',
			'content' => 'Content',
			'image' => 'Image',
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
		$criteria->compare('sequence',$this->sequence);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('image',$this->image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
