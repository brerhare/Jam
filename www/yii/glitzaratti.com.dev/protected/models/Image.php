<?php

/**
 * This is the model class for table "image".
 *
 * The followings are the available columns in table 'image':
 * @property integer $id
 * @property string $filename
 * @property integer $product_id
 *
 * The followings are the available model relations:
 * @property Product $product
 */
class Image extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Image the static model class
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
		return 'image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id', 'required'),
			array('product_id', 'numerical', 'integerOnly'=>true),

            array('filename', 'file','on'=>'insert',
                'types'=> 'jpg, jpeg, gif, png',
                'maxSize' => 1024 * 1024 * 10, // 10MB
                'tooLarge' => 'The file was bigger than 10MB. Please upload a smaller file.'
            ),
            array('filename', 'file','on'=>'update',
                'types'=> 'jpg, jpeg, gif, png',
                'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 10, // 10MB
                'tooLarge' => 'The file was larger than 10MB. Please upload a smaller file.'
            ),
            array('filename', 'unsafe'),
			array('filename', 'length', 'max'=>255),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, filename, product_id', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'filename' => 'Filename',
			'product_id' => 'Product',
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
		$criteria->compare('filename',$this->filename,true);
//		$criteria->compare('product_id',$this->product_id);     kim modified as below, to use the session var product_id
        $criteria->compare('product_id',Yii::app()->session['product_id']);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
