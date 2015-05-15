<?php

/**
 * This is the model class for table "jelly_gallery_image".
 *
 * The followings are the available columns in table 'jelly_gallery_image':
 * @property integer $id
 * @property integer $sequence
 * @property string $text
 * @property string $image
 * @property string $url
 * @property integer $jelly_gallery_id
 *
 * The followings are the available model relations:
 * @property JellyGallery $jellyGallery
 */
class JellyGalleryImage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JellyGalleryImage the static model class
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
		return 'jelly_gallery_image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('image, jelly_gallery_id', 'required'),
			array('sequence, jelly_gallery_id', 'numerical', 'integerOnly'=>true),

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

			array('image, url', 'length', 'max'=>255),
			array('text', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sequence, text, image, url, jelly_gallery_id', 'safe', 'on'=>'search'),
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
			'jellyGallery' => array(self::BELONGS_TO, 'JellyGallery', 'jelly_gallery_id'),
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
			'text' => 'Text',
			'image' => 'Image',
			'url' => 'Url',
			'jelly_gallery_id' => 'Jelly Gallery',
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
		$criteria->compare('text',$this->text,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('jelly_gallery_id',Yii::app()->session['gallery_id']);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,

            // @@EG: admin gridview default sort
            'sort'=>array(
                'defaultOrder'=>'sequence ASC, id DESC',
            ),

        	// @@EG: Change cgridview pagination
        	'pagination' => array(
            	'pageSize' => 50,
        	),

		));
	}
}
