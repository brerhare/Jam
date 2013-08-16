<?php

/**
 * This is the model class for table "product_product_has_product_feature".
 *
 * The followings are the available columns in table 'product_product_has_product_feature':
 * @property integer $product_product_id
 * @property integer $product_feature_id
 */
class ProductHasFeature extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductHasFeature the static model class
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
		return 'product_product_has_product_feature';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_product_id, product_feature_id', 'required'),
			array('product_product_id, product_feature_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('product_product_id, product_feature_id', 'safe', 'on'=>'search'),
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
			'product_feature_id' => 'Product Feature',
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
		$criteria->compare('product_feature_id',$this->product_feature_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}