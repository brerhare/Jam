<?php

/**
 * This is the model class for table "jelly_gallery".
 *
 * The followings are the available columns in table 'jelly_gallery':
 * @property integer $id
 * @property integer $sequence
 * @property string $title
 * @property string $text
 * @property string $image
 * @property integer $active
 *
 * The followings are the available model relations:
 * @property JellyGalleryImage[] $jellyGalleryImages
 */
class JellyGallery extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JellyGallery the static model class
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
		return 'jelly_gallery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('sequence, active', 'numerical', 'integerOnly'=>true),

            array('image', 'file','on'=>'insert',
                'types'=> 'jpg, jpeg, gif, png',
                'maxSize' => 1024 * 1024 * 20, // 20MB
                'tooLarge' => 'The file was bigger than 20MB. Please upload a smaller file.'
            ),
            array('image', 'file','on'=>'update',
                'types'=> 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 20, // 20MB
                'tooLarge' => 'The file was larger than 20MB. Please upload a smaller file.'
            ),
            array('image', 'unsafe'),
			array('text', 'safe'),

			array('title, image', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sequence, title, text, image, active', 'safe', 'on'=>'search'),
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
			'jellyGalleryImages' => array(self::HAS_MANY, 'JellyGalleryImage', 'jelly_gallery_id'),
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
			'text' => 'Text',
			'image' => 'Image',
			'active' => 'Active',
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
		$criteria->compare('text',$this->text,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,

            // @@EG: Change cgridview pagination
            'pagination' => array(
                'pageSize' => 50,
            ),

		));
	}
}
