<?php

/**
 * This is the model class for table "product_product_has_product_option".
 *
 * The followings are the available columns in table 'product_product_has_product_option':
 * @property integer $product_product_id
 * @property integer $product_option_id
 * @property string $price
 * @property integer $is_default
 * @property integer $is_poa
 */
class ProductHasOption extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductHasOption the static model class
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
		return 'product_product_has_product_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_product_id, product_option_id', 'required'),
			array('product_product_id, product_option_id, is_default, is_poa', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('product_product_id, product_option_id, price, is_default, is_poa', 'safe', 'on'=>'search'),
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
			'product_product_id' => 'Product Product',
			'product_option_id' => 'Product Option',
			'price' => 'Price',
			'is_default' => 'Is Default?',
			'is_POA' => 'POA',
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

		$criteria->compare('product_product_id',$this->product_product_id);
		$criteria->compare('product_option_id',$this->product_option_id);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('is_poa',$this->is_poa);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
