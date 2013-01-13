<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $name
 * @property string $price
 * @property string $description
 * @property string $weight_kg
 * @property integer $pack_height_mm
 * @property integer $pack_width_mm
 * @property integer $pack_depth_mm
 * @property integer $category_id
 *
 * The followings are the available model relations:
 * @property Gallery[] $galleries
 * @property Image[] $images
 * @property Category $category
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
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, price, description, category_id', 'required'),
			array('pack_height_mm, pack_width_mm, pack_depth_mm, category_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('price', 'length', 'max'=>10),
			array('weight_kg', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, price, description, weight_kg, pack_height_mm, pack_width_mm, pack_depth_mm, category_id', 'safe', 'on'=>'search'),
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
			'galleries' => array(self::MANY_MANY, 'Gallery', 'gallery_has_product(product_id, gallery_id)'),
			'images' => array(self::HAS_MANY, 'Image', 'product_id'),
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
			'name' => 'Name',
			'price' => 'Price',
			'description' => 'Description',
			'weight_kg' => 'Weight Kg',
			'pack_height_mm' => 'Pack Height Mm',
			'pack_width_mm' => 'Pack Width Mm',
			'pack_depth_mm' => 'Pack Depth Mm',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('weight_kg',$this->weight_kg,true);
		$criteria->compare('pack_height_mm',$this->pack_height_mm);
		$criteria->compare('pack_width_mm',$this->pack_width_mm);
		$criteria->compare('pack_depth_mm',$this->pack_depth_mm);
		$criteria->compare('category_id',$this->category_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}