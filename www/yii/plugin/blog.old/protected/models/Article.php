<?php

/**
 * This is the model class for table "blog_article".
 *
 * The followings are the available columns in table 'blog_article':
 * @property integer $id
 * @property integer $uid
 * @property string $date
 * @property string $title
 * @property string $intro
 * @property string $content
 * @property string $thumbnail_path
 * @property integer $blog_category_id
 */
class Article extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Article the static model class
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
		return 'blog_article';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, date, title, blog_category_id', 'required'),
			array('uid, blog_category_id', 'numerical', 'integerOnly'=>true),
			array('title, thumbnail_path', 'length', 'max'=>255),
			array('intro, content', 'safe'),

            // @@EG: Image upload. Next two lines. See also controller create/update/delete and remember to set enctype in the view!!!!!
            array('thumbnail_path','unsafe'),
            array('thumbnail_path', 'file', 'types'=>'jpg, jpeg, gif, png','safe'=>true, 'maxSize'=>10000*1024, 'allowEmpty'=>true, 'tooLarge'=>'{attribute} is too large to be uploaded. Maximum size is 10MB.'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, date, title, thumbnail_path, intro, content, blog_category_id', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
			'date' => 'Date',
			'title' => 'Title',
			'intro' => 'Intro',
			'content' => 'Content',
			'thumbnail_path' => 'Thumbnail Path',
			'blog_category_id' => 'Blog Category',
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
		//$criteria->compare('uid',$this->uid);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('intro',$this->intro,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('thumbnail_path',$this->thumbnail_path,true);
		$criteria->compare('blog_category_id',$this->blog_category_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

// @@EG CJuiDatePicker. See also the form where the widget is applied

	protected function afterFind(){
		parent::afterFind();
		$this->date=date('d-m-Y', strtotime(str_replace("-", "", $this->date)));       
	}

	protected function beforeSave(){
	if(parent::beforeSave()){
		$this->date=date('Y-m-d', strtotime(str_replace(",", "", $this->date)));
		return TRUE;
	}
	else
		return false;
	}
}
