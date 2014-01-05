<?php

/**
 * This is the model class for table "content_block".
 *
 * The followings are the available columns in table 'content_block':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $sequence
 * @property string $title
 * @property string $url
 * @property string $content
 * @property integer $active
 * @property integer $home
 * @property integer $meta_title
 * @property integer $meta_description
 * @property integer $meta_keywords
 */
class ContentBlock extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContentBlock the static model class
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
		return 'content_block';
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
			array('parent_id, sequence, active, home', 'numerical', 'integerOnly'=>true),
			array('title, url', 'length', 'max'=>255),
			array('content, meta_title, meta_description, meta_keywords', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent_id, sequence, title, url, content, active, home, meta_title, meta_description, meta_keywords', 'safe', 'on'=>'search'),
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
			'parent_id' => 'Parent',
			'sequence' => 'Sequence',
			'title' => 'Title',
			'url' => 'Url',
			'content' => 'Content',
			'active' => 'Active',
			'home' => 'Is Home Page',
			'meta_title' => 'Title',
			'meta_description' => 'Description',
			'meta_keywords' => 'Keywords',
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('sequence',$this->sequence);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('home',$this->home);
		$criteria->compare('meta_title',$this->meta_title);
		$criteria->compare('meta_description',$this->meta_description);
		$criteria->compare('meta_keywords',$this->meta_keywords);

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
}
