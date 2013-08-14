<?php

/**
 * This is the model class for table "product_product".
 *
 * The followings are the available columns in table 'product_product':
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property string $description
 * @property string $weight
 * @property string $height
 * @property string $width
 * @property string $depth
 * @property string $volume
 * @property integer $department_id
 * @property integer $vat_id
 *
 * The followings are the available model relations:
 * @property Image[] $images
 * @property Department $department
 * @property Vat $vat
 * @property Feature[] $productFeatures
 * @property Option[] $productOptions
 */
class Product extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
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
		return 'product_product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, name, department_id, vat_id', 'required'),
			array('uid, department_id, vat_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('weight, height, width, depth, volume', 'length', 'max'=>10),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, name, description, weight, height, width, depth, volume, department_id, vat_id', 'safe', 'on'=>'search'),
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
			'images' => array(self::HAS_MANY, 'Image', 'product_product_id'),
			'department' => array(self::BELONGS_TO, 'Department', 'department_id'),
			'vat' => array(self::BELONGS_TO, 'Vat', 'vat_id'),
			'productFeatures' => array(self::MANY_MANY, 'Feature', 'product_product_has_feature(product_id, feature_id)'),
			'productOptions' => array(self::MANY_MANY, 'Option', 'product_product_has_option(product_id, option_id)'),
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
			'name' => 'Name',
			'description' => 'Description',
			'weight' => 'Weight',
			'height' => 'Height',
			'width' => 'Width',
			'depth' => 'Depth',
			'volume' => 'Volume',
			'department_id' => 'Department',
			'vat_id' => 'Vat',
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
		$criteria->compare('uid',$this->uid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('height',$this->height,true);
		$criteria->compare('width',$this->width,true);
		$criteria->compare('depth',$this->depth,true);
		$criteria->compare('volume',$this->volume,true);
		$criteria->compare('department_id',$this->department_id);
		$criteria->compare('vat_id',$this->vat_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}